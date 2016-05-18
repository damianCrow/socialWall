@extends('layouts.master')

@section('title')
  My App
@endsection

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
  
  <div class="row">
    <div class="col-md-6 col-md-offset-3">

      <h2> Log In </h2>
      
      <form action="{{ route('signin') }}" method="post">

        <div class="form-group {{ $errors -> has('username') ? 'has-error' : ''}}">
          <label for="username"> Enter Username </label>
          <input class="form-control" type="text" name="username" value="{{ Request::old('username')}}">
        </div>

        <div class="form-group {{ $errors -> has('password') ? 'has-error' : ''}}">
          <label for="password"> Enter Password </label>
          <input class="form-control" type="password" name="password" value="{{ Request::old('password')}}">
        </div>

        <button type="submit" class="btn btn-primary"> Submit </button>
        <input type="hidden" name="_token" value="{{ Session::token()}}">
      </form>
    </div>
  </div>
@endsection