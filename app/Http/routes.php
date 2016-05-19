<?php

Route::group(['middleware' => ['web']], function () {

	Route::get('/', 
    function () {

    Auth::logout();
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
});

?>