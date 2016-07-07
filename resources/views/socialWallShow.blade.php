@extends('layouts.master')

@section('header')

<h3 class="cta-wrapper col-lg-12"> 

	{!! $data->render() !!} 

	<button id="socialWallRunButton" value="run/{{$socialWallId}}" class="btn btn-lg btn-success header-btn-right col-lg-2" onclick="getSocialWallRunData('{{ URL::to('run/socialWall/' . $socialWallId) }}')">Run socialWall</button>

</h3>

@endsection

@section('content')
	
	@foreach($data as $tweet)

		<div class="grid-item panel panel-info col-lg-3">
		  <div class="post-header panel-heading">{{ $tweet -> post_username }}

		  <div class="channel-logo-wrapper pull-right">
		  	<img class="channel-logo" src="@if(substr($tweet -> post_id, 0, 2 ) === 'TW') {{ asset('assets/twitterLogo_blue.png') }} @elseif(substr( $tweet -> post_id, 0, 2 ) === 'FB') {{ asset('assets/facebookLogo.png') }} @endif">

		  	<a class="approval" href="/approve" value="{{$tweet -> id}}">
		  		<span class="icon-tick icon icon-checkmark"></span>
		  	</a>

		  	<a class="approval" href="/disapprove" value="{{$tweet -> id}}">
			  	<span value="0" class="icon icon-close icon-cross"></span>
		  	</a>

		  </div>

		  </div>

		  <div class="@if($tweet -> approved === "1") approved @elseif($tweet -> approved === "0") disapproved @endif panel-body">{{ $tweet -> post_text }}</div>

		  @if($tweet -> post_media != '')

		  	<img class="post-image-custom" src="{{ $tweet -> post_media }}">
		  @endif

		</div>

	@endforeach

@endsection
