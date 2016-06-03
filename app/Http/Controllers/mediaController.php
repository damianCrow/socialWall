<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use socialwall\getMedia;
use socialwall\Http\Requests;
use GuzzleHttp;
use View;

class mediaController extends Controller  {

	public function twitterMedia() {

		$query = '?q=%23game%20of%20thrones';

		
		$twitterBearerToken = "Bearer AAAAAAAAAAAAAAAAAAAAAANHvQAAAAAAabl2KF9Ig7CAgBZ6v2ahka2Kf5s%3DqTa8l2czzBRTUysN2wwpcAiSs1TV2XcGnGOABfayAfu1YySRxG";

		$client = new GuzzleHttp\Client([
			'base_uri' => 'https://api.twitter.com/1.1/search/'
		]);

		$response = $client->request('GET', 'tweets.json'.$query,
			['headers' => ['Authorization' => $twitterBearerToken]
		]);

		$responseObj = (json_decode($response->getBody()));

		if (isset($responseObj -> search_metadata -> next_results)) {

			$this -> twitterMedia($responseObj -> search_metadata -> next_results);
echo $responseObj -> search_metadata -> next_results;
			return View::make('test')
	     	->with('responseObj', $responseObj);
		}
		else {
echo $responseObj -> search_metadata -> next_results;
			return View::make('test')
	     	->with('responseObj', $responseObj);
		}
	}
}
