<?php

namespace socialwall;

use socialwall\Http\Controllers\socialWallController;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp;
use Session;
use Config;
use Facebook;
use stdClass;

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
		if($channel === 'Facebook') {

			return $testArray['filterParams'];
		}
		if(isset(json_decode($socialWall['target_accounts'])-> Twitteraccounts) && $channel === 'Twitter') {

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

				if(isset($media[0]->media->image)) {

					$fbObject -> img = $media[0]->media->image->src;
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

		foreach ($responseObj as $value) {

			$value -> id = 'TW' . $value -> id;
		}

		if(!empty($filterParams)) {
			
			return socialWall::filetrFunction($responseObj, $filterParams, 'Twitter');
		}
		else {

			return $responseObj;
		}
  }

  public static function savePosts($array, $wallId) {

  	foreach ($array as $data) {

  		$posts = new twitterPosts();
  		
  		if(strpos($data -> id, 'FB') !== false) {

  			$posts -> post_username = utf8_encode($data -> post_username);

  			if(isset($data -> img)) {

	  			$posts -> post_media = $data	-> img;
	  		}
	  		else {

	  			$posts -> post_media = '';
	  		}
  		}
  		else {

  			$posts -> post_username = utf8_encode($data -> user -> screen_name);

  			if(isset($data -> entities -> media)) {

		  		$posts -> post_media = $data -> entities -> media[0]-> media_url;
		  	}
		  	else {

		  		$posts -> post_media = '';
		  	}
  		}

	  	$posts -> socialwall_id = $wallId;
	  	$posts -> post_id = $data -> id;
	  	$posts -> post_text = utf8_encode($data -> text);
	  	$posts -> approved = '';

	  	$posts -> save();	

	  	Session::flash('message', 'You have successfully saved posts for socialWall '. $wallId);
  	}
  }

  public static function filetrFunction($responseObj, $filterParamsArray, $channel) {
		
		$responseObject = [];

		foreach($responseObj as $key => $post) {

			foreach($filterParamsArray as $filterParam) {

				if(strpos($post->text, $filterParam) !== false) {

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