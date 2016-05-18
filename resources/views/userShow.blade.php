@extends('layouts.master')

@section('content')

@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

	<h1>Showing {{ $user->name }}</h1>

  <div class="jumbotron text-center">
      <h2>{{ $user -> name }}</h2>
      <p>
          <strong>Email:</strong> {{ $user -> username }}<br>
          <strong>Level:</strong> {{ $user -> email }}
      </p>
  </div>
	

@endsection