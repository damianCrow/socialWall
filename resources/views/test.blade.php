@extends('layouts.master')

@section('content')

	@foreach($responseObj->statuses as $tweet) 

		<div class="panel panel-primary">
		  <div class="panel-heading">{{ $tweet -> user -> name}}</div>
		  <div class="panel-body">{{ $tweet -> text }}</div>

		  @if(isset($tweet -> entities -> media))
		  	<img src="{{ $tweet -> entities -> media[0] -> media_url }}">
		  @endif
		</div>

	@endforeach

@endsection