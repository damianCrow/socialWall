<?php

Route::group(['middleware' => ['web']], function () {

	Route::get('/', 
    function () {

    Auth::logout();
    return view('welcome');
	});

  Route::get('/adduser', [
    'uses' => 'userController@addUser',
    'as' => 'adduser',
    'middleware' => 'admin'
  ]);

  Route::get('/deleteuser', [
    'uses' => 'userController@getAllUsers',
    'as' => 'deleteuser',
    'middleware' => 'admin'
  ]);

   Route::delete('/deleteuser', [
    'uses' => 'userController@deleteUser',
    'as' => 'deleteuser',
    'middleware' => 'admin'
  ]);

  Route::post('/signup', [
  	'uses' => 'userController@signUp',
  	'as' => 'signup',
    'middleware' => 'admin'
  ]);

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