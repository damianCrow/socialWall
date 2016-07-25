<?php

Route::group(['middleware' => ['web']], function() {

	Route::get('/', function() {

    Auth::logout();
    DB::table('posts') -> delete();

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

  Route::get('/run/socialWall/{socialwallId}', 'socialWallController@socialWallRun');

  Route::get('/socialWall/run/{socialwallId}', function() {

    return view('runView');
  });

});

?>