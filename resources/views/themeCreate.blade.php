@extends('layouts.master')

@section('header')

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

@endsection

@section('content')

  <div class="col-md-12 col-md-offset-1 form-inline">

    <h2 class="vertical-spacer"> Create New Theme </h2>
  
    {{ Form::open(array('url' => 'theme/', 'method' => 'POST', 'files' => true)) }}

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('name') ? 'has-error' : ''}}">
        <label for="name"> Enter Theme Name </label>
        <input id="name" class="form-control" type="text" name="name" value="{{ Request::old('name')}}">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 col-sm-6 vertical-spacer {{ $errors -> has('view') ? 'has-error' : ''}}">

        <label class="block" for="view"> Select View </label>

        <select id="view" name="view" class="form-control" value="{{ Request::old('view')}}">  
          <option > Default View </option>
          <option > Tile View </option>
          <option > Gallery View </option>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('backgroundimage') ? 'has-error' : ''}}">
        <label for="backgroundimage"> Upload Background Image </label>
        <input id="backgroundimage" class="form-control" type="file" accept="image/*" name="backgroundimage" value="{{ Request::old('backgroundimage')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('bordercolor') ? 'has-error' : ''}}">
        <label for="bordercolor"> Select Border Color </label>
        <input id="bordercolor" class="form-control" type="color" name="bordercolor" value="{{ Request::old('bordercolor')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('backgroundcolor') ? 'has-error' : ''}}">
        <label for="backgroundcolor"> Select Background Color </label>
        <input id="backgroundcolor" class="form-control" type="color" name="backgroundcolor" value="{{ Request::old('backgroundcolor')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('fontcolor') ? 'has-error' : ''}}">
        <label for="fontcolor"> Select Font Color </label>
        <input id="fontcolor" class="form-control" type="color" name="fontcolor" value="{{ Request::old('fontcolor')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-12 col-sm-12">
        <label for="private"> Make Theme Private </label>
        <input id="private" type="checkbox" name="private"> 
      </div>

      <div class="input-group vertical-spacer col-lg-12 col-sm-12">
        <button type="submit" class="btn btn-primary vertical-spacer"> Save Theme </button>
        <input type="hidden" name="_token" value="{{ Session::token()}}">
      </div>

    {!! Form::close() !!}
    
  </div>
@endsection