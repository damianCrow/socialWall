<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use socialwall\Http\Requests;
use socialwall\socialWall;
use socialwall\theme;
use Validator;
use File;
use Session;
use Input;
use View;
use DB;

class themeController extends Controller {
   
  public function __construct() {

    $this->middleware('auth');
  }

  public function index() {

  	$themes = theme::all();

    return View::make('themeIndex')
      ->with('themes', $themes);
  }

  public function create() {

  	return view('themeCreate');
  }

  public function show($id) {

  	$theme = theme::find($id);

  	return view('themeShow')
  		->with('theme', $theme);
  }

  public function store(Request $request) {

  	Input::merge(array_map('trim', $request->all()));

  	$this -> validate($request, [
			'name' => 'required|max:50|unique:themes',
			'transitionspeed' => 'required|numeric|max:60|min:3',
			'backgroundimage' => 'image|max:2000',
			'placeholderimage' => 'max:1000|image'
		]);

  	if(isset($request['backgroundimage'])) {

  		$fileName = uniqid() .'-'. $request->file('backgroundimage')->getClientOriginalName();

  		$request -> file('backgroundimage')->move(public_path('assets'), $fileName);

  		$backgroundImageUrl = asset('assets/' . $fileName);
  	}
  	else {

  		$backgroundImageUrl = '';
  	}
  	if(isset($request['placeholderimage'])) {

  		$fileName = uniqid() .'-'. $request->file('placeholderimage')->getClientOriginalName();

  		$request -> file('placeholderimage')->move(public_path('assets'), $fileName);

  		$placeHolderImageUrl = asset('assets/' . $fileName);
  	}
  	else {

  		$placeHolderImageUrl = '';
  	}

  	if(isset($request['private'])) {

		  $private = true;
		}
		else {

		  $private = false;  
		}

		$name = $request['name'];
		$view = $request['view'];
		$transitionSpeed = $request['transitionspeed'];
		$borderColor = $request['bordercolor'];
		$backgroundColor = $request['backgroundcolor'];
		$fontColor = $request['fontcolor'];

		$theme = new theme();
		$theme -> user_id = Auth::user()['id'];
		$theme -> name = $name;
		$theme -> view = $view;
		$theme -> transition_speed = $transitionSpeed;
		$theme -> border_color = $borderColor;
		$theme -> background_color = $backgroundColor;
		$theme -> font_color = $fontColor;
		$theme -> is_private = $private;
		$theme -> background_image = $backgroundImageUrl;
		$theme -> placeholder_image = $placeHolderImageUrl;
		
		$theme -> save();

		Session::flash('message', 'You have successfully created a new theme.');

		return redirect() -> action('themeController@index');
  }
  
  public function edit($id) {

  	$theme = theme::find($id);
 	
  	return View::make('themeEdit', [
    	'theme' => $theme
    ]);
  }

  public function update($id) {

    Input::merge(array_map('trim', Input::all()));

    $theme = theme::find($id);
    $request = Input::all();

  	$rules = [
			'name' => 'required|max:50|unique:themes,name,' .$id,
			'transitionspeed' => 'required|numeric|max:60|min:3',
			'backgroundimage' => 'max:2000|image',
			'placeholderimage' => 'max:1000|image'
		];

		$validator = Validator::make($request, $rules);

    if($validator->fails()) {

      return View::make('themeEdit')
        ->withErrors($validator)
        ->with([
        	'theme' => $theme,
        	'request' => $request
        	]);         
    } 
    else {

    	if(isset($request['private'])) {

			  $private = true;
			}
			else {

			  $private = false;  
			}

			if(isset($request['backgroundimage'])) {

				File::Delete(public_path('assets/' . substr(strrchr($theme -> background_image, "/"), 1)));

	  		$fileName = uniqid() .'-'. Input::file('backgroundimage')->getClientOriginalName();

	  		Input::file('backgroundimage')->move(public_path('assets'), $fileName);

	  		$backgroundImageUrl = asset('assets/' . $fileName);

	  		$theme -> background_image = $backgroundImageUrl;
	  	}
	  	
	  	if(isset($request['placeholderimage'])) {

	  		File::Delete(public_path('assets/' . substr(strrchr($theme -> placeholder_image, "/"), 1)));

	  		$fileName = uniqid() .'-'. Input::file('placeholderimage')->getClientOriginalName();

	  		Input::file('placeholderimage')->move(public_path('assets'), $fileName);

	  		$placeHolderImageUrl = asset('assets/' . $fileName);

	  		$theme -> placeholder_image = $placeHolderImageUrl;
	  	}

	    $theme -> name = $request['name'];
			$theme -> view = $request['view'];
			$theme -> transition_speed = $request['transitionspeed'];
			$theme -> border_color = $request['bordercolor'];
			$theme -> background_color = $request['backgroundcolor'];
			$theme -> font_color = $request['fontcolor'];
			$theme -> is_private = $private;
			
			$theme -> save();

			Session::flash('message', 'You have successfully updated this theme.');

			return redirect() -> action('themeController@index');
    }
  }

  public function destroy($id) {

  	$theme = theme::find($id);

  	$socialWallsWithThisTheme = socialWall::where('theme', '=', $theme -> name) -> get();

  	foreach($socialWallsWithThisTheme as $socialWall) {
  		
	  	$socialWall -> theme = 'Default Theme';

	  	$socialWall -> save();
  	}

  	if($theme -> background_image !== "") {

  		File::Delete(public_path('assets/' . substr(strrchr($theme -> background_image, "/"), 1)));
  	}
  	if($theme -> placeholder_image !== "") {

  		File::Delete(public_path('assets/' . substr(strrchr($theme -> placeholder_image, "/"), 1)));
  	}

    $theme -> delete();

    Session::flash('message', 'You have successfully deleted a Theme and its assets!');

    return redirect() -> action('themeController@index');
  }
}
