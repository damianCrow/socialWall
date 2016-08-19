<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use socialwall\Http\Requests;
use socialwall\socialWall;
use socialwall\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use socialwall\theme;
use GuzzleHttp;
use Validator;
use Session;
use Input;
use View;
use DB;

class socialWallController extends Controller {

	public function __construct() {

    $this->middleware('auth');
  }

  public $responseArray = [];
  public $postsUpdateArray = [
		'fbAccounts' => null,
		'twQuery' => [],
		'viAccounts' => null,
		'filter_params' => '',
		'wall_id' => ''
	];

  public function index() {
     
   	$socialWalls = socialWall::all();

    return View::make('socialWallIndex')
      ->with('socialWalls', $socialWalls);
  }

  public function create() {

  	$themes = theme::where('is_private', '=', '0')
  		->orWhere('user_id', '=', Auth::user()['id'])->get();
    
  	return view('socialWallCreate')
  		->with('themes', $themes);
  }

  public function approvePost($id) {

  	$post = posts::find($id);

  	$post->approved = 1;

  	$post->save();
  }
  public function disApprovePost($id) {
  	
  	$post = posts::find($id);

  	$post->approved = 0;

  	$post->save();
  }

  public function store(Request $request) {

		$this->validate($request, [
			'name' => 'required|unique:social_walls|min:4',
			'mediachannels' => 'required|array',
			'updateinterval' => 'required|numeric|max:100|min:5',
			// 'Facebookaccounts' => 'required_if:mediachannels,0'
		]);


		$name = $request['name'];
		$updateInterval = $request['updateinterval'];
		$media_channels = json_encode($request['mediachannels']);
		$search_hashtags = $request['searchcriteria'];
		$target_accounts = json_encode(socialWall::buildAccountsObj($request));
		$theme = $request['themeselect'];
		$results_order = $request['resultsorder'];
		$filter_keywords = $request['keywordfilter'];
		

		$socialwall = new socialWall();
		$socialwall->user_id = Auth::user()['id'];
		$socialwall->name = $name;
		$socialwall->update_interval = $updateInterval;
		$socialwall->media_channels = $media_channels;
		$socialwall->target_accounts = $target_accounts;
		$socialwall->search_hashtags = $search_hashtags;
		$socialwall->theme = $theme;
		$socialwall->results_order = $results_order;
		$socialwall->filter_keywords = $filter_keywords;

		$socialwall->save();

		Session::flash('message', 'You have successfully created a new socialWall.');

		return redirect()->action('socialWallController@index');
	}

  public function show(Request $request, $id) {

  	$updateInterval = socialWall::find($id) -> update_interval;

		if(posts::where('socialwall_id', '=', $id)->exists()) {
			
			$data = posts::where('socialwall_id', '=', $id)
				->orderBy('created_at', 'desc')
				->paginate(30);
	    
			return View::make('socialWallShow')
	   		->with(['data' => $data, 'socialWallId' => $id, 'updateInterval' => $updateInterval]);
		}
		else {

			$socialWall = socialWall::find($id);

			foreach(json_decode($socialWall['media_channels']) as $channel) {

				if($channel === 'Twitter') {
					
					$returnedQuery = socialWall::buildQuery($socialWall, $channel);

					if(is_array($returnedQuery)) {

						foreach ($returnedQuery['queries'] as $query) {

							array_push($this->postsUpdateArray['twQuery'], $query);
							$this->postsUpdateArray['filter_params'] = $returnedQuery['filterParams'];
							$this->postsUpdateArray['wall_id'] = $id;

							$this->populateResponseArray(socialWall::makeRequestTW($query, $returnedQuery['filterParams']), $id);
						}
					}
					else {

						array_push($this->postsUpdateArray['twQuery'], $returnedQuery);
						$this->postsUpdateArray['wall_id'] = $id;

						$this->populateResponseArray(socialWall::makeRequestTW($returnedQuery, null), $id);
					}
				}
				if($channel === 'Facebook') {

					$accounts = json_decode($socialWall['target_accounts'])->Facebookaccounts;
					
					$filterParams = socialWall::buildQuery($socialWall, $channel);

					$this->postsUpdateArray['fbAccounts'] = $accounts;
					$this->postsUpdateArray['filter_params'] = $filterParams;
					$this->postsUpdateArray['wall_id'] = $id;

					$this->populateResponseArray(socialWall::makeRequestFB($accounts, $filterParams), $id);
				}
				if($channel === 'Vine') {

					$accounts = json_decode($socialWall['target_accounts'])->Vineaccounts;
					$filterParams = socialWall::buildQuery($socialWall, $channel);

					if(!empty($accounts[0])) {

						$this->postsUpdateArray['viAccounts'] = $accounts;
						$this->postsUpdateArray['filter_params'] = $filterParams;
						$this->postsUpdateArray['wall_id'] = $id;

						$this->populateResponseArray(socialWall::makeRequestVI($accounts, $filterParams), $id);
					}
					else {

						$this->postsUpdateArray['filter_params'] = $filterParams;
						$this->postsUpdateArray['wall_id'] = $id;

						$this->populateResponseArray(socialWall::makeRequestVI(null, $filterParams), $id);
					}
				}
			}

			$request->session()->put('wall_id' . $id, json_encode($this->postsUpdateArray));

echo ' after ' . Count($this->responseArray) . ' Tweets';
		
	    $data = posts::where('socialwall_id', '=', $id)
				->orderBy('created_at', 'desc')
				->paginate(30);
	    
			return View::make('socialWallShow')
	   		->with(['data' => $data, 'socialWallId' => $id, 'updateInterval' => $updateInterval]);
		}
  }

  public function socialWallUpdate(Request $request, $id) {

  	$oldTotalPosts = $this->updatePosts(json_decode($request->session()->get('wall_id' . $id)), posts::where('socialwall_id', '=', $id)->count());

  	$newTotalPosts = posts::where('socialwall_id', '=', $id)->count();

  	if($oldTotalPosts < $newTotalPosts) {

			$newPosts = posts::where('socialwall_id', '=', $id)
				->orderBy('created_at', 'asc')
				->skip($oldTotalPosts)->take($newTotalPosts - $oldTotalPosts)->get();

				return json_encode($newPosts);
		}
		else {

			return 'no new posts';
		}	
  }

  public function updatePosts($queryArray, $totalPosts) {

  	if(count($queryArray->twQuery) > 0) {

  		foreach($queryArray->twQuery as $query) {

  			if(count($queryArray->filter_params) > 0) {
  				
  				$this->populateResponseArray(socialWall::makeRequestTW($query, $queryArray->filter_params), $queryArray->wall_id);
  			}
  			else {

  				$this->populateResponseArray(socialWall::makeRequestTW($query, null), $queryArray->wall_id);
  			}
  		}
  	}
  	if(count($queryArray->fbAccounts) > 0) {
  		
  		$this->populateResponseArray(socialWall::makeRequestFB($queryArray->fbAccounts, $queryArray->filter_params), $queryArray->wall_id);
  	}
  	if(count($queryArray->viAccounts) > 0) {
  		
  		$this->populateResponseArray(socialWall::makeRequestVI($queryArray->viAccounts, $queryArray->filter_params), $queryArray->wall_id);
  	}
  	else if(count($queryArray->filter_params) > 0) {

  		$this->populateResponseArray(socialWall::makeRequestVI(null, $queryArray->filter_params), $queryArray->wall_id);
  	}

  	return $totalPosts;
  } 

  public function populateResponseArray($responseObj, $id) {

  	if(count($responseObj) < 1 && !isset($responseObj->statuses)) {
			
			return Session::flash('message', 'There are no posts which contain any of the hashtags or keywords provided');
		}
		else {

			if(isset($responseObj->statuses)) {
				
				$data = $responseObj->statuses;
			}
			else {

				$data = $responseObj;
			}

			foreach ($data as $value) {

				array_push($this->responseArray, $value);
			}

			if(isset($responseObj->search_metadata->next_results)) {

				$this->populateResponseArray(socialWall::makeRequestTW('search/tweets.json' . $responseObj->search_metadata->next_results, null), $id);
			}
			else {
				
				socialWall::savePosts($this->responseArray, $id);
			}
		}
	}

  public function edit($id) {

  	$themes = theme::where('is_private', '=', '0')
  		->orWhere('user_id', '=', Auth::user()['id'])->get();

  	$socialWall = socialWall::find($id);

  	$target_accounts = [];

  	foreach(json_decode($socialWall['target_accounts']) as $key => $value) {

  		$target_accounts[$key] = $value;
  	}

  	$hashtags = explode(",", $socialWall['search_hashtags']);
  	$filter_keywords = explode(",", $socialWall['filter_keywords']);
  	$media_channels = json_decode($socialWall['media_channels']);

    return View::make('socialWallEdit', [
    	'socialWall' => $socialWall, 
    	'hashtags' => $hashtags,
    	'target_accounts' => $target_accounts,
    	'filter_keywords' => $filter_keywords,
    	'media_channels' => $media_channels,
    	'themes' => $themes
    ]);
  }

  public function update($id) {
    
    $socialWall = socialWall::find($id);
    $request = Input::all();
    $themes = theme::all();
		$rules = [
			'name' => 'required|min:4|unique:social_walls,name,' .$id,
			'mediachannels' => 'required|array',
			'updateinterval' => 'required|numeric|max:100|min:5',
			// 'Facebookaccounts' => 'required_if:mediachannels,0'
		];
            
    $validator = Validator::make($request, $rules);

    if($validator->fails()) {

	    $hashtags = explode(",", $request['searchcriteria']);
	  	$filter_keywords = explode(",", $request['keywordfilter']);

	  	$target_accounts = [];

	  	foreach (json_decode($socialWall['target_accounts']) as $key => $value) {

	  		$target_accounts[$key] = $value;
	  	}

	  	if (Input::get(['mediachannels']) != null) {

	  		$media_channels = $request['mediachannels'];
	  	}
	  	else {

	  		$media_channels = null;
	  	}

	  	$socialWall['name'] = $request['name'];

      return View::make('socialWallEdit')
        ->withErrors($validator)
        ->with([
        		'socialWall' => $socialWall,
        		'hashtags' => $hashtags,
			    	'target_accounts' => $target_accounts,
			    	'filter_keywords' => $filter_keywords,
			    	'media_channels' => $media_channels,
			    	'themes' => $themes
        	]);         
    } 
    else {

    	$name = $request['name'];
    	$target_accounts = json_encode(socialWall::buildAccountsObj($request));
			$media_channels = json_encode($request['mediachannels']);
			$search_hashtags = $request['searchcriteria'];
			$theme = $request['themeselect'];
			$results_order = $request['resultsorder'];
			$filter_keywords = $request['keywordfilter'];
			$updateInterval = $request['updateinterval'];
      
			$socialWall->user_id = Auth::user()['id'];
			$socialWall->name = $name;
			$socialWall->update_interval = $updateInterval;
			$socialWall->media_channels = $media_channels;
			$socialWall->target_accounts = $target_accounts;
			$socialWall->search_hashtags = $search_hashtags;
			$socialWall->theme = $theme;
			$socialWall->results_order = $results_order;
			$socialWall->filter_keywords = $filter_keywords;

			$socialWall->save();

      Session::flash('message', 'You have successfully updated this socialWall.');

     	return redirect()->action('socialWallController@index');
		}
  }

  public function destroy($id) {
     
    $socialWall = socialWall::find($id);

    $socialWall->delete();

    Session::flash('message', 'You have successfully deleted this socialWall!');

    return redirect()->back();
  }

  public function socialWallRun($id) {

  	$themeName = socialWall::find($id)->theme;
  	$updateInterval = socialWall::find($id)->update_interval;

  	$theme = theme::where('name', '=', $themeName)->get();

  	$data = posts::where('socialwall_id', '=', $id) 
  		->where('approved', '=', '1')->get();

  	if(count($data) < 1) {

  		return json_encode("You have not approved any posts for this socialWall!");
  	}
  	else {

  		$response = ['data' => $data, 'theme' => $theme, 'updateInterval' => $updateInterval];
  		return json_encode($response);
  	}
  }
}
