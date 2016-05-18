@extends('layouts.master')

@section('content')

  @if(count($errors) > 0) 
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <ul>
          @foreach($errors -> all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif 

  <div class="col-md-6 col-md-offset-3">

    <h2> Sign Up </h2>
    
    <form action="{{ URL::to('user/') }}" method="post">

      <div class="form-group {{ $errors -> has('username') ? 'has-error' : ''}}">
        <label for="username"> Enter Username </label>
        <input id="username" class="form-control" type="text" name="username" value="{{ Request::old('username')}}">
      </div>

      <div class="form-group {{ $errors -> has('email') ? 'has-error' : ''}}">
        <label for="email"> Enter Email </label>
        <input id="email" class="form-control" type="text" name="email" value="{{ Request::old('email')}}">
      </div>

      <div class="form-group {{ $errors -> has('password') ? 'has-error' : ''}}">
        <label for="password"> Enter Password </label>
        <input id="password" class="form-control" type="password" name="password" value="{{ Request::old('password')}}">
      </div>

      <div class="form-group">
        <label for="admin"> Make Admin User </label>
        <input class="checkbox-inline" id="admin" type="checkbox" name="admin"> 
      </div>

      <button type="submit" class="btn btn-primary"> Submit </button>
      <input type="hidden" name="_token" value="{{ Session::token()}}">

    </form>
  </div>
@endsection