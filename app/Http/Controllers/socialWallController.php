<?php

namespace socialwall\Http\Controllers;

use Illuminate\Http\Request;
use socialwall\Http\Requests;
use socialwall\socialWall;
use Illuminate\Support\Facades\Auth;
use Session;

class socialWallController extends Controller {

	public function __construct() {

    $this->middleware('auth');
  }

  public function index() {
     
  }

  public function create() {
      
  	return view('socialWallCreate');
  }

  public function store(Request $request) {

		$this -> validate($request, [
			'name' => 'required|unique:social_walls',
			'mediachannels' => 'required',
			'searchcriteria' => 'required',
		]);

		$name = $request['name'];
		$media_channels = json_encode($request['mediachannels']);
		$search_hashtags = $request['searchcriteria'];
		$target_accounts = $request['targetaccounts'];
		$theme = $request['themeselect'];
		$results_order = $request['resultsorder'];
		$filter_keywords = $request['keywordfilter'];
		

		$socialwall = new socialWall();
		$socialwall -> user_id = Auth::user()['id'];
		$socialwall -> name = $name;
		$socialwall -> media_channels = $media_channels;
		$socialwall -> target_accounts = $target_accounts;
		$socialwall -> search_hashtags = $search_hashtags;
		$socialwall -> theme = $theme;
		$socialwall -> results_order = $results_order;
		$socialwall -> filter_keywords = $filter_keywords;

		$socialwall -> save();

		Session::flash('message', 'You have successfully created a new socialWall.');

		return redirect() -> route('dashboard');
	}

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id) {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
      //
  }
}
