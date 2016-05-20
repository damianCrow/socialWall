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
      
      {{ Form::open(array('url' => 'signin')) }}

        <div class="form-group {{ $errors -> has('username') ? 'has-error' : ''}}">
          <label for="username"> Enter Username </label>
          <input class="form-control" type="text" name="username" value="{{ Request::old('username')}}">
        </div>

        <div class="form-group {{ $errors -> has('password') ? 'has-error' : ''}}">
          <label for="password"> Enter Password </label>
          <input class="form-control" type="password" name="password" value="{{ Request::old('password')}}">
        </div>

      {{ Form::hidden('_method', 'POST') }}
          {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
      {{ Form::close() }}
      
    </div>
  </div>
@endsection