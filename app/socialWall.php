<?php

namespace socialwall;

use socialwall\Http\Controllers\socialWallController;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp;
use Session;
use Config;
use Facebook;
use stdClass;
use Validator;

class socialWall extends Model {

	public static function buildQuery($socialWall, $channel) {

		$testArray = ['filterParams' => [], 'queries' => []];

   	$oldCharacters = [',', '"'];
   	$newCharacters = [' OR ', ''];

   	$contentOrdering = '&result_type=' . strtolower(str_replace(' ', '', $socialWall['results_order']));

   	if($socialWall['search_hashtags']) {

   		$hashtagsArray = explode(',', $socialWall['search_hashtags']);

   		foreach ($hashtagsArray as $value) {

   			array_push($testArray['filterParams'], str_replace('#', '', $value));
   		}

   		$hashtags = urlencode(str_replace($oldCharacters, $newCharacters, $socialWall['search_hashtags']));
   	}
   	else {

			$hashtags = '';
		}

		if($socialWall['filter_keywords']) {

			$keywordsArray = explode(',', $socialWall['filter_keywords']);
			
			foreach ($keywordsArray as $value) {
   			
   			array_push($testArray['filterParams'], $value);
   		}

			$keywords = urlencode(str_replace(',', ' OR ', $socialWall['filter_keywords']) . ' ');
		}
		else {

			$keywords = '';
		}
		if($channel === 'Facebook' || $channel ===  'Vine') {

			return $testArray['filterParams'];
		}
		if($channel === 'Twitter') {

			$TwitterAccounts = json_decode($socialWall['target_accounts']) -> Twitteraccounts;

			if(!empty($TwitterAccounts[0])) {
				
				$queryArray = [];

				$accountsArray = json_decode($socialWall['target_accounts'])-> Twitteraccounts;

				foreach ($accountsArray as $account) {

					$query = 'statuses/user_timeline.json?screen_name=' . $account . '&include_rts=false' . '&count=200';

					array_push($testArray['queries'], $query);
				}

				array_push($testArray, $queryArray);

				return $testArray;
			}
			else {

				$query = 'search/tweets.json?q=' . $keywords . $hashtags . $contentOrdering;

				return $query;
			}
		}
	}

	public static function makeRequestFB($accountsArray, $filterParams) {

		$fb = new Facebook\Facebook([
		  'app_id' => '146285462443655',
		  'app_secret' => '5cd6cf0a1f8e1d0462eb2ed0485721e1',
		  'default_graph_version' => 'v2.2',
	  ]);

		$fb->setDefaultAccessToken('146285462443655|tXbeHdXhRaR3RFaXlrsYHDevbXI');

		$postsArray = [];
		$responseArray = [];

		foreach($accountsArray as $account) {

			$obj = json_decode($fb->get('/' .$account. '/posts')->getGraphEdge());
			
			foreach($obj as $key => $value) {

				$value -> account = $account;
			}

			array_push($postsArray, $obj);
		}

		foreach($postsArray as $value) {

			foreach($value as $post) {

				$fbObject = new stdClass();
				$post_text;

				$fbObject -> post_username = $post -> account;
				

				if(isset($post -> message)) {

					$post_text = $post -> message;
				}
				elseif(isset($post -> story)) {

					$post_text = $post -> story;
				}
				else {

					$post_text = 'No post text';
				}

				$media = json_decode($fb->get('/' .$post->id. '/attachments')->getGraphEdge());

				$fbObject -> text = $post_text;
				$fbObject -> id = 'FB' . $post->id;

				if(isset($media[0] -> type) && $media[0] -> type === 'video_inline') {

					$fbObject -> mediaUrl = $media[0] -> url;
					$fbObject -> media_type = 'video';
				}
				else {

					if(isset($media[0]->media->image)) {

						$fbObject -> mediaUrl = $media[0]->media->image->src;
						$fbObject -> media_type = 'image';
					}
					else {

						$fbObject -> media_type = '';
					}
				}

				array_push($responseArray, $fbObject);
			}
		}

		if(!empty($filterParams)) {
			
			return socialWall::filetrFunction($responseArray, $filterParams, 'Facebook');
		}
		else {

			return $responseArray;
		}
	}
  
  public static function makeRequestTW($query, $filterParams) {

  	$twitterBearerToken = "Bearer AAAAAAAAAAAAAAAAAAAAAANHvQAAAAAAabl2KF9Ig7CAgBZ6v2ahka2Kf5s%3DqTa8l2czzBRTUysN2wwpcAiSs1TV2XcGnGOABfayAfu1YySRxG";

  	$twitterClient = new GuzzleHttp\Client([
			'base_uri' => 'https://api.twitter.com/1.1/'
		]);

		$response = $twitterClient->request('GET', $query,
			['headers' => ['Authorization' => $twitterBearerToken]
		]);

		$responseObj = json_decode($response->getBody());

		if(isset($responseObj -> statuses)) {

			if(empty($responseObj -> statuses)) {
				
				return $responseObj;
			}
			else {
				$responseObj = $responseObj -> statuses;
			}	
		}

		foreach($responseObj as $value) {

			$value -> id = 'TW' . $value -> id;
			$value -> media_type = 'image';
		}

		if(!empty($filterParams)) {
			
			return socialWall::filetrFunction($responseObj, $filterParams, 'Twitter');
		}
		else {

			return $responseObj;
		}
  }

  public static function makeRequestVI($accountsArray, $filterParams) {

  	$vineClient = new GuzzleHttp\Client([
			'base_uri' => 'https://api.vineapp.com/timelines/'
		]);

		$vineResponseArray = [];

		if($accountsArray === null) {

			foreach($filterParams as $key => $value) {
				
				$response = $vineClient->request('GET', 'tags/' . $value);

				foreach(json_decode($response->getBody()) -> data -> records as $key => $post) {

					$vineObject = new stdClass();
					$vineObject -> id = 'VI'. $post -> postId;
					$vineObject -> post_username = $post -> username;
					$vineObject -> text = $post -> description;
					$vineObject -> media_type = 'video';

					if(isset($post -> videoDashUrl)) {

						$vineObject -> mediaUrl = $post -> videoDashUrl;
					}

					array_push($vineResponseArray, $vineObject);
				}
			}

			return $vineResponseArray;
		}
		else {

			foreach($accountsArray as $key => $value) {
				
				$response = $vineClient->request('GET', 'users/' . $value);

				$posts = json_decode($response->getBody()) -> data -> records;
				
				foreach($posts as $key => $post) {

					$tagsString = '';

					foreach($post -> entities as $tag) {
							
						$tagsString = $tagsString . $tag -> title;	
					}

					$vineObject = new stdClass();
					$vineObject -> id = 'VI'. $post -> postId;
					$vineObject -> post_username = $post -> username;
					$vineObject -> text = $post -> description;
					$vineObject -> media_type = 'video';
					$vineObject -> tags = $tagsString;

					if(isset($post -> videoDashUrl)) {

						$vineObject -> mediaUrl = $post -> videoDashUrl;
					}

					array_push($vineResponseArray, $vineObject);
				}
			}

			if(!empty($filterParams)) {
				
				return socialWall::filetrFunction($vineResponseArray, $filterParams, 'Vine');
			}
			else {

				return $vineResponseArray;
			}
		}
  }

  public static function savePosts($array, $wallId) {

  	foreach ($array as $data) {

  		$post = new posts();
  		
  		if(strpos($data -> id, 'FB') !== false || strpos($data -> id, 'VI') !== false) {

  			$post -> post_username = utf8_encode($data -> post_username);

  			if(isset($data -> mediaUrl) && strlen($data -> mediaUrl) < 250) {

	  			$post -> post_media = $data -> mediaUrl;
	  		}
	  		else {

	  			$post -> post_media = '';
	  		}
  		}
  		else {

  			$post -> post_username = utf8_encode($data -> user -> screen_name);

  			if(isset($data -> entities -> media)) {

		  		$post -> post_media = $data -> entities -> media[0] -> media_url;
		  	}
		  	else {

		  		$post -> post_media = '';
		  	}
  		}

  		if(strlen($data -> text) > 500) {

  			$data -> text = substr($data -> text, 0, 500) . '...';
  		}

	  	$post -> socialwall_id = $wallId;
	  	$post -> post_id = $data -> id;
	  	$post -> media_type = $data -> media_type;
	  	$post -> post_text = utf8_encode($data -> text);
	  	$post -> approved = '';

	  	$rules = ['post_id' => 'unique:posts'];

			$validator = Validator::make(get_object_vars($post)['attributes'], $rules);

			if($validator -> passes()) {

		  	$post -> save();	

		  	Session::flash('message', 'You have successfully saved posts for socialWall '. $wallId);
		  }
		  else {

		  	$post -> delete();

		  	Session::flash('message', 'post '. $post->post_id . 'Deleted');
		  }
  	}
  }

  public static function filetrFunction($responseObj, $filterParamsArray, $channel) {
		
		$responseObject = [];

		foreach($responseObj as $key => $post) {

			foreach($filterParamsArray as $filterParam) {

				if($channel === 'Vine') {
					
					$hayStack = $post -> tags;
				}
				else {

					$hayStack = $post -> text;
				}

				if(strpos($hayStack, $filterParam) !== false) {

					array_push($responseObject, $post);
				}
			}
		}

		return $responseObject;
	}

	public static function buildAccountsObj($request) {

		$targetAccountsStringArray = [
			'Facebookaccounts',
			'Twitteraccounts',
			'Vineaccounts',
			'Instagramaccounts' 
		];

		$targetAccountsArray = [];

		foreach($targetAccountsStringArray as $value) {

			if(isset($request[$value])) {

				$targetAccountsArray[$value] = explode(',', $request[$value]);
			}
		}

		return $targetAccountsArray;
	}
 

// 			$reTweet = 'RT';

// 			foreach ($this->responseArray as $key => $value) {

// 				$test = strpos($value->text, $reTweet);

// 				if($test !== false) {

// 					 unset($this->responseArray[$key]);
// 				}
// 			}
}