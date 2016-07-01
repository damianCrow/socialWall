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

    <h2 class="vertical-spacer"> Edit Theme </h2>

    {{ Form::model($theme, array('route' => array('theme.update', $theme->id), 'method' => 'PUT', 'files' => true)) }}
  
      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('name') ? 'has-error' : ''}}">
        <label for="name"> Enter Theme Name </label>
        <input id="name" class="form-control" type="text" name="name" value="@if(isset($request)) {{ $request['name'] }} @else {{ $theme['name'] }} @endif">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 col-sm-6 vertical-spacer {{ $errors -> has('view') ? 'has-error' : ''}}">

        <label class="block" for="view"> Select View </label>

        <select id="view" name="view" class="form-control">  
          <option @if(isset($request) && $request['view'] || $theme['view'] === 'Default View') selected @endif> Default View </option>
          <option @if(isset($request) && $request['view'] || $theme['view'] === 'Tile View') selected @endif> Tile View </option>
          <option @if(isset($request) && $request['view'] || $theme['view'] === 'Gallery View') selected @endif> Gallery View </option>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('backgroundimage') ? 'has-error' : ''}}">
        <label for="backgroundimage"> Upload Background Image (2000kb max)</label>
        <input id="backgroundimage" class="form-control" type="file" accept="image/*" name="backgroundimage">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('bordercolor') ? 'has-error' : ''}}">
        <label for="bordercolor"> Select Border Color </label>
        <input id="bordercolor" class="form-control" type="color" name="bordercolor" value="@if(isset($request)){{$request['bordercolor']}}@else{{$theme['border_color']}}@endif">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('backgroundcolor') ? 'has-error' : ''}}">
        <label for="backgroundcolor"> Select Background Color </label>
        <input id="backgroundcolor" class="form-control" type="color" name="backgroundcolor" value="@if(isset($request)){{ $request['backgroundcolor'] }}@else{{ $theme['background_color'] }}@endif">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('fontcolor') ? 'has-error' : ''}}">
        <label for="fontcolor"> Select Font Color </label>
        <input id="fontcolor" class="form-control" type="color" name="fontcolor" value="@if(isset($request)){{ $request['fontcolor'] }}@else{{ $theme['font_color'] }}@endif">
      </div>


      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('transitionspeed') ? 'has-error' : ''}}">
        <label for="transitionspeed"> Post Transition Speed (in seconds) </label>
        <input id="transitionspeed" class="form-control" type="text" name="transitionspeed" value="@if(isset($request)) {{ $request['transitionspeed'] }} @else {{ $theme['transition_speed'] }} @endif">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('placeholderimage') ? 'has-error' : ''}}">
        <label for="placeholderimage"> Upload Post Placeholder Image (1000kb max)</label>
        <input id="placeholderimage" class="form-control" type="file" accept="image/*" name="placeholderimage">
      </div>

      <div class="input-group col-lg-3 col-sm-6 col-lg-offset-1">
        <label for="private"> Make Theme Private </label>
        <input id="private" type="checkbox" name="private" @if(isset($request) && isset($request['private'])) checked @elseif($theme['is_private'] === '1') checked @endif> 
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6">
        @if($theme['background_image'] !== "")
          <label for="backgroundimagesample"> Background Image Sample </label>
          <img style="margin: 0;" id="backgroundimagesample" class="image-sample" src="{{ $theme['background_image'] }}">
        @endif
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1">
        @if($theme['placeholder_image'] !== "")
          <label for="backgroundimagesample"> Placeholder Image Sample </label>
          <img style="margin: 0;" id="backgroundimagesample" class="image-sample" src="{{ $theme['placeholder_image'] }}">
        @endif
      </div>

      <div class="input-group vertical-spacer col-lg-12 col-sm-12">
        {{ Form::submit('Update Theme', array('class' => 'btn btn-primary')) }}
        <input type="hidden" name="_token" value="{{ Session::token()}}">
      </div>

    {!! Form::close() !!}
    
  </div>
@endsection