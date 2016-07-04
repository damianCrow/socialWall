<?php

namespace socialwall;

use socialwall\Http\Controllers\socialWallController;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp;
use Session;
use Config;

class socialWall extends Model {

	public static function buildTwitterQuery($socialWall) {

		$testArray = ['filterParams' => [], 'queries' => []];

   	$oldCharacters = [',', '"'];
   	$newCharacters = [' OR ', ''];

   	$contentOrdering = '&result_type=' . strtolower(str_replace(' ', '', $socialWall['results_order']));

   	if ($socialWall['search_hashtags']) {

   		$hashtagsArray = explode(',', $socialWall['search_hashtags']);

   		foreach ($hashtagsArray as $value) {

   			array_push($testArray['filterParams'], $value);
   		}

   		$hashtags = urlencode(str_replace($oldCharacters, $newCharacters, $socialWall['search_hashtags']));
   	}
   	else {

			$hashtags = '';
		}

		if ($socialWall['filter_keywords']) {

			$keywordsArray = explode(',', $socialWall['filter_keywords']);
			
			foreach ($keywordsArray as $value) {
   			
   			array_push($testArray['filterParams'], $value);
   		}

			$keywords = urlencode(str_replace(',', ' OR ', $socialWall['filter_keywords']) . ' ');
		}
		else {

			$keywords = '';
		}

		if ($socialWall['target_accounts']) {

			$queryArray = [];

			$accountsArray = explode(',', $socialWall['target_accounts']);

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
  
  public static function makeRequest($query, $optionalParam) {

  	$twitterBearerToken = "Bearer AAAAAAAAAAAAAAAAAAAAAANHvQAAAAAAabl2KF9Ig7CAgBZ6v2ahka2Kf5s%3DqTa8l2czzBRTUysN2wwpcAiSs1TV2XcGnGOABfayAfu1YySRxG";

  	$twitterClient = new GuzzleHttp\Client([
			'base_uri' => 'https://api.twitter.com/1.1/'
		]);

		$response = $twitterClient->request('GET', $query,
			['headers' => ['Authorization' => $twitterBearerToken]
		]);

		$responseObj = json_decode($response->getBody());

		if(!empty($optionalParam)) {
			
			return socialWall::filetrFunction($responseObj, $optionalParam);
		}
		else {

			return $responseObj;
		}
  }

  public static function savePosts($array, $wallId) {

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

  public static function filetrFunction($responseObj, $filterParamsArray) {
		
		$responseObject = [];
		foreach($responseObj as $key => $post) {

			foreach($filterParamsArray as $filterParam) {

				$found = strpos($post->text, $filterParam);

				if($found) {

					array_push($responseObject, $post);
				}
			}
		}
		
		return $responseObject;
	}


// 			$reTweet = 'RT';

// 			foreach ($this->responseArray as $key => $value) {

// 				$test = strpos($value->text, $reTweet);

// 				if($test !== false) {

// 					 unset($this->responseArray[$key]);
// 				}
// 			}
}