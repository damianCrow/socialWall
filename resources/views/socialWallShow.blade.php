@extends('layouts.master')

@section('content')

	<h3 class="cta-wrapper"> 

		{!! $data->render() !!} 

		<button class="btn btn-lg btn-success header-btn-right" onclick="getSocialWallRunData('{{ URL::to('run/socialWall/' . $socialWallId) }}')">Run socialWall</button>

	</h3>
	

	@foreach($data as $tweet)

		<div class="panel panel-info col-lg-3 col-md-4">
		  <div class="post-header panel-heading">{{ $tweet -> post_username }}

		  <div class="channel-logo-wrapper pull-right">
		  	<img class="channel-logo" src="{{ asset('assets/twitterLogo_white.png') }}">

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

		  	<img class="col-lg-12 col-md-12" src="{{ $tweet -> post_media }}">
		  @endif

		</div>

	@endforeach

@endsection
