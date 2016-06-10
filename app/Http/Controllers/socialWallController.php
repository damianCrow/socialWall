<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use socialwall\Http\Requests;
use socialwall\socialWall;
use socialwall\twitterPosts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
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

  public function index() {
     
   	$socialWalls = socialWall::all();

    return View::make('socialWallIndex')
      ->with('socialWalls', $socialWalls);
  }

  public function create() {
      
  	return view('socialWallCreate');
  }

  public function approvePost() {

  	$post = twitterPosts::find($_GET['id']);

  	$post -> approved = 1;

  	$post -> save();
  }
  public function disApprovePost() {
  	
  	$post = twitterPosts::find($_GET['id']);

  	$post -> approved = 0;

  	$post -> save();
  }

  public function store(Request $request) {

		$this -> validate($request, [
			'name' => 'required|unique:social_walls|min:4',
			'mediachannels' => 'required',
			'searchcriteria' => 'required',
		]);

		$name = $request['name'];
		$media_channels = json_encode($request['mediachannels']);
		$search_hashtags = $request['searchcriteria'];
		$target_accounts = $request['targetaccounts'];
		$theme = $request['themeselect'];
		$results_order = $request['resultsorder'];
		$filter_keywords = $request['keywordfilter'];
		

		$socialwall = new socialWall();
		$socialwall -> user_id = Auth::user()['id'];
		$socialwall -> name = $name;
		$socialwall -> media_channels = $media_channels;
		$socialwall -> target_accounts = $target_accounts;
		$socialwall -> search_hashtags = $search_hashtags;
		$socialwall -> theme = $theme;
		$socialwall -> results_order = $results_order;
		$socialwall -> filter_keywords = $filter_keywords;

		$socialwall -> save();

		Session::flash('message', 'You have successfully created a new socialWall.');

		return redirect() -> route('dashboard');
	}

  public function show($id) {

		if(Input::get('page') || twitterPosts::where('socialwall_id', '=', $id)->exists()) {
			
			$data = twitterPosts::where('socialwall_id', '=', $id)->paginate(15);
	    
			return View::make('socialWallShow')
	   		->with('data', $data);
		}
		else {

			$socialWall = socialWall::find($id);

	   	$oldCharacters = [',', '"'];
	   	$newCharacters = [' OR ', ''];

	   	$contentOrdering = '&result_type=' . strtolower(str_replace(' ', '', $socialWall['results_order']));

			$hashtags = urlencode(str_replace($oldCharacters, $newCharacters, $socialWall['search_hashtags']));

			if ($socialWall['filter_keywords']) {

				$keywordsArray = explode(',', $socialWall['filter_keywords']);

				$keywords = urlencode(str_replace(',', ' OR ', $socialWall['filter_keywords']) . ' ');
			}
			else {

				$keywords = '';
			}

			if ($socialWall['target_accounts']) {

				$hashtagsArray = explode(',', $socialWall['search_hashtags']);

				$accountsArray = explode(',', $socialWall['target_accounts']);

				foreach ($accountsArray as $account) {

					$query = 'statuses/user_timeline.json?screen_name=' . $account . '&count=200';

					foreach ($this -> makeRequest($query) as $post) {
						
						foreach ($hashtagsArray as $hashtag) {

							if(strpos($post->text, $hashtag)) {

								array_push($this->responseArray, $post);
							}
							else {

								foreach ($keywordsArray as $keyword) {

									if(strpos($post->text, $keyword)) {

										array_push($this->responseArray, $post);
									}
								}
							}
						}
					}
				}
			}
			else {

				$query = 'search/tweets.json?q=' . $keywords . $hashtags . $contentOrdering;

				$this -> makeRequest($query);
			}
echo 'before ' . Count($this->responseArray) . ' Tweets';
			$reTweet = 'RT';

			foreach ($this->responseArray as $key => $value) {

				$test = strpos($value->text, $reTweet);

				if($test !== false) {

					 unset($this->responseArray[$key]);
				}
			}
echo ' after ' . Count($this->responseArray) . ' Tweets';
			$this->savePosts($this->responseArray, $id);
		
	    $data = twitterPosts::where('socialwall_id', '=', $id)->paginate(15);
	    
			return View::make('socialWallShow')
	   		->with('data', $data);
		}
  }

  public function savePosts($array, $wallId) {

  	foreach ($array as $data) {
  		
  		$posts = new twitterPosts();
	  	$posts -> socialwall_id = $wallId;
	  	$posts -> post_id = $data -> id;
	  	$posts -> post_username = utf8_encode($data -> user -> screen_name);
	  	$posts -> post_text = utf8_encode($data -> text);
	  	$posts -> approved = '';

	  	if(isset($data -> entities -> media)) {

	  		$posts -> post_media = $data -> entities -> media[0]-> media_url;
	  	}
	  	else {

	  		$posts -> post_media = '';
	  	}

	  	$posts -> save();	

	  	Session::flash('message', 'You have successfully saved posts for socialWall '. $wallId);
  	}
  }

  public function makeRequest($query) {

  	$twitterBearerToken = "Bearer AAAAAAAAAAAAAAAAAAAAAANHvQAAAAAAabl2KF9Ig7CAgBZ6v2ahka2Kf5s%3DqTa8l2czzBRTUysN2wwpcAiSs1TV2XcGnGOABfayAfu1YySRxG";

  	$twitterClient = new GuzzleHttp\Client([
			'base_uri' => 'https://api.twitter.com/1.1/'
		]);

		$response = $twitterClient->request('GET', $query,
			['headers' => ['Authorization' => $twitterBearerToken]
		]);

		$responseObj = json_decode($response->getBody());

		if(isset($responseObj -> search_metadata -> next_results)) {

			foreach ($responseObj -> statuses as $value) {

				array_push($this->responseArray, $value);
			}

			$this -> makeRequest('search/tweets.json' . $responseObj -> search_metadata -> next_results);
		}
		else {

			return $responseObj;
		}
  }

  public function edit($id) {

  	$socialWall = socialWall::find($id);

  	$hashtags = explode(",", $socialWall['search_hashtags']);
  	$target_accounts = explode(",", $socialWall['target_accounts']);
  	$filter_keywords = explode(",", $socialWall['filter_keywords']);
  	$media_channels = json_decode($socialWall['media_channels']);

    return View::make('socialWallEdit', [
    	'socialWall' => $socialWall, 
    	'hashtags' => $hashtags,
    	'target_accounts' => $target_accounts,
    	'filter_keywords' => $filter_keywords,
    	'media_channels' => $media_channels
    ]);
  }

  public function update($id) {
    
    $socialWall = socialWall::find($id);
    $request = Input::all();
			
		$rules = [
			'name' => 'required|min:4',
			'mediachannels' => 'required',
			'searchcriteria' => 'required',
		];
            
    $validator = Validator::make($request, $rules);

    if ($validator->fails()) {

	    $hashtags = explode(",", $request['searchcriteria']);
	  	$target_accounts = explode(",", $request['targetaccounts']);
	  	$filter_keywords = explode(",", $request['keywordfilter']);

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
			    	'media_channels' => $media_channels
        	]);         
        
    } 
    else {

    	$name = $request['name'];
			$media_channels = json_encode($request['mediachannels']);
			$search_hashtags = $request['searchcriteria'];
			$target_accounts = $request['targetaccounts'];
			$theme = $request['themeselect'];
			$results_order = $request['resultsorder'];
			$filter_keywords = $request['keywordfilter'];
       
			$socialWall -> user_id = Auth::user()['id'];
			$socialWall -> name = $name;
			$socialWall -> media_channels = $media_channels;
			$socialWall -> target_accounts = $target_accounts;
			$socialWall -> search_hashtags = $search_hashtags;
			$socialWall -> theme = $theme;
			$socialWall -> results_order = $results_order;
			$socialWall -> filter_keywords = $filter_keywords;

			$socialWall -> save();

      Session::flash('message', 'You have successfully updated this socialWall.');

      return redirect() -> route('dashboard');
		}
  }

  public function destroy($id) {
     
    $socialWall = socialWall::find($id);

    $socialWall -> delete();

    Session::flash('message', 'You have successfully deleted this socialWall!');

    return redirect() -> route('dashboard');
  }
}
