<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function() {

	Route::get('/', function(Request $request) {

    Auth::logout();
    DB::table('posts') -> delete();

    foreach($request->session()->all() as $key => $storedQuery) {

      if(strpos($key, 'wall_id') !== false) {

        $request->session()->forget($key);
      }
    }

    return view('welcome');
	});

  Route::post('/signin', [
    'uses' => 'userController@signIn',
    'as' => 'signin'
  ]);

  Route::get('/dashboard', [
  	'uses' => 'userController@dashboard',
  	'as' => 'dashboard',
  	'middleware' => 'auth'
  ]);

  Route::resource('user', 'userController');

  Route::resource('theme', 'themeController');

  Route::resource('socialWall', 'socialWallController');

  Route::get('/disapprove/{postId}', 'socialWallController@disApprovePost');  

  Route::get('/approve/{postId}', 'socialWallController@approvePost');

  Route::get('/refresh/socialWall/{socialwallId}', 'socialWallController@socialWallRefresh');

  Route::get('/run/socialWall/{socialwallId}', 'socialWallController@socialWallRun');

  Route::get('/update/socialWall/{socialwallId}', 'socialWallController@socialWallUpdate');

  Route::get('/socialWall/run/{socialwallId}', function() {

    return view('runView');
  });

});

?>