<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use socialwall\Http\Requests;
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

  public function store(Request $request) {

  	$this -> validate($request, [
			'name' => 'required|unique:themes',
			'transitionspeed' => 'required|numeric|max:60|min:3',
			'backgroundimage' => 'max:5000'
		]);

  	if(isset($request['backgroundimage'])) {

  		$fileName = uniqid() .'-'. $request->file('backgroundimage')->getClientOriginalName();

  		$request -> file('backgroundimage')->move(public_path('assets'), $fileName);

  		$backgroundImageUrl = asset('assets/' . $fileName);
  	}
  	else {

  		$backgroundImageUrl = '';
  	}

  	if($request['private']) {

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
		
		$theme -> save();

		Session::flash('message', 'You have successfully created a new theme.');

		return redirect() -> route('dashboard');
  }
  
  public function edit($id) {

  	$theme = theme::find($id);
 	
  	return View::make('themeEdit', [
    	'theme' => $theme
    ]);
  }

  public function update($id) {

  	$rules = [
			'name' => 'required|unique:themes',
			'transitionspeed' => 'required|numeric|max:60|min:3',
			'backgroundimage' => 'max:5000'
		];
  }

  public function destroy($id) {

  	$theme = theme::find($id);

  	if($theme -> background_image !== "") {

  		File::Delete(public_path('assets/' . substr(strrchr($theme -> background_image, "/"), 1)));
  	}

    $theme -> delete();

    Session::flash('message', 'You have successfully deleted this Theme and its assets!');

    return redirect() -> route('dashboard');
  }
}
