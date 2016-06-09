<?php

Route::group(['middleware' => ['web']], function () {

	Route::get('/', function() {

    Auth::logout();
    DB::table('twitter_posts')->delete();

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

  Route::resource('socialWall', 'socialWallController');

  Route::get('/test', [
    'uses' => 'mediaController@twitterMedia',
    'as' => 'test'
  ]);

  Route::get('/disapprove', 'socialWallController@disApprovePost');
  
  Route::get('/approve', 'socialWallController@approvePost');
});

?>