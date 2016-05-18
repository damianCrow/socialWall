@extends('layouts.master')

@section('content')

	<h1>Edit Your Account</h1>

	</br>

	<!-- <form action="{{ URL::to('user/' . $user->id) }}" method="put"> -->

	{{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT')) }}

	 <div class="form-group {{ $errors -> has('username') ? 'has-error' : ''}}">
      <label for="username"> Enter Username </label>
      <input id="username" class="form-control" type="text" name="username" value="{{ $user['username'] }}">
    </div>

    <div class="form-group {{ $errors -> has('email') ? 'has-error' : ''}}">
      <label for="email"> Enter Email </label>
      <input id="email" class="form-control" type="text" name="email" value="{{ $user['email'] }}">
    </div>

    <div class="form-group {{ $errors -> has('password') ? 'has-error' : ''}}">
      <label for="password"> Enter Password </label>
      <input id="password" class="form-control" type="password" name="password" value="{{ $user['password']}}">
    </div>

    <div class="form-group">
      <label for="admin"> Make Admin User </label>
      <input @if (!Auth::user()->isAdmin()) disabled readonly @endif class="checkbox-inline" id="admin" type="checkbox" name="admin" @if($user['admin']== 1) checked @endif> 
    </div>

    <!-- <button type="submit" class="btn btn-primary"> Submit </button>
    <input type="hidden" name="_token" value="{{ Session::token()}}"> -->

		{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}

@endsection