@extends('layouts.master')

@section('content')

	@foreach($data->statuses as $tweet) 

		<div class="panel panel-primary col-lg-3 col-md-4">
		  <div class="post-header panel-heading">{{ $tweet -> user -> name}}

		  <div class="channel-logo-wrapper pull-right">
		  	<img class="channel-logo" src="{{ asset('assets/twitterLogo_white.png') }}">
		  	<span class="icon-tick icon icon-checkmark"></span>
		  	<span class="icon icon-close icon-cross"></span>
		  </div>

		  </div>
		  <div class="panel-body">{{ $tweet -> text }}</div>

		  @if(isset($tweet -> entities -> media))
		  	<img class="col-lg-12 col-md-12" src="{{ $tweet -> entities -> media[0] -> media_url }}">
		  @endif
		</div>

	@endforeach

@endsection