<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use socialwall\Http\Requests;
use socialwall\theme;
use Validator;
use Session;
use Input;
use View;
use DB;

class themeController extends Controller {
   
  public function __construct() {

    $this->middleware('auth');
  }

  public function index() {}

  public function create() {

  	return view('themeCreate');
  }

  public function store(Request $request) {

  	$this -> validate($request, [
			'name' => 'required|unique:themes'
		]);

  	if(isset($request['backgroundimage'])) {

  		$backgroundImage = $request['backgroundimage']->openFile()->fread($request['backgroundimage']->getSize());
  	}
  	else {

  		$backgroundImage = '';
  	}

  	if($request['private']) {

		  $private = true;
		}
		else {

		  $private = false;  
		}

		$name = $request['name'];
		$view = $request['view'];
		$borderColor = $request['bordercolor'];
		$backgroundColor = $request['backgroundcolor'];
		$fontColor = $request['fontcolor'];

		$theme = new theme();
		$theme -> user_id = Auth::user()['id'];
		$theme -> name = $name;
		$theme -> view = $view;
		$theme -> border_color = $borderColor;
		$theme -> background_color = $backgroundColor;
		$theme -> font_color = $fontColor;
		$theme -> is_private = $private;
		$theme -> background_image = $backgroundImage;
		
		$theme -> save();

		Session::flash('message', 'You have successfully created a new theme.');

		return redirect() -> route('dashboard');
  }
  
  public function edit($id) {}

  public function update($id) {}

  public function destroy($id) {}
}
