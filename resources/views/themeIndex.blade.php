@extends('layouts.master')

@section('content')

	<h1> Themes Index </h1>

	</br>

  @foreach ($themes as $theme)

    <ul class="list-group">
    	<li class="list-group-item">Name: <strong> {{ $theme->name }} </strong></li>
    	<li class="list-group-item">Theme Id: <strong> {{ $theme->id }} </strong></li>
      <li class="list-group-item">User Id: <strong> {{ $theme->user_id }} </strong></li>
      <li class="list-group-item">Date Added: <strong> {{ $theme->created_at }} </strong></li>
      <li class="list-group-item">View: <strong> {{ $theme->view }} </strong></li>
      <li class="list-group-item">Post Transition Speed: <strong> {{ $theme->transition_speed }} Seconds </strong></li>
      <li class="list-group-item">Font Color: <span class="color-sample" style="background-color: {{ $theme->font_color }};"></span></li>
      <li class="list-group-item">Border Color: <span class="color-sample" style="background-color: {{ $theme->border_color }};"></span></li>
      <li class="list-group-item">Background Color: <span class="color-sample" style="background-color: {{ $theme->background_color }};"></span></li>
      <li class="list-group-item">Background Image: @if($theme->background_image !== '') <img class="image-sample" src="{{ $theme->background_image }}"> @else <strong> None </strong> @endif </li>

      <li class="list-group-item">Post Placeholder Image: @if($theme->placeholder_image !== '') <img class="image-sample" src="{{ $theme->placeholder_image }}"> @else <strong> None </strong> @endif </li>


      @if($theme->is_private == '0') 
        <li class="list-group-item">Private Theme: <strong> No </strong></li>
      @else
        <li class="list-group-item">Private Theme: <strong> Yes </strong></li>
      @endif

      @if(Auth::user()->isAdmin() || Auth::user()->id === $theme->user_id)

        <li class="list-group-item list-group-item-danger">

          <a class="btn btn-sm btn-success" href="{{ URL::to('theme/' . $theme->id) }}">Preview Theme</a>
          <a class="btn btn-sm btn-info" href="{{ URL::to('theme/' . $theme->id . '/edit') }}">Edit Theme</a>

          {{ Form::open(array('url' => 'theme/' . $theme->id, 'class' => 'delete side-by-side')) }}
              {{ Form::hidden('_method', 'DELETE') }}
              {{ Form::submit('Delete Theme', array('class' => 'btn btn-sm btn-danger')) }}
          {{ Form::close() }}
        	
  			</li>
      @else 

        <li class="list-group-item list-group-item-warning">

          <a class="btn btn-sm btn-success" href="{{ URL::to('theme/' . $theme->id) }}">Preview Theme</a>
          <a class="btn btn-default btn-sm disabled" href="{{ URL::to('/') }}">Edit Theme</a>

          {{ Form::open(array('url' => '/', 'class' => 'delete side-by-side')) }}
              {{ Form::hidden('_method', 'GET') }}
              {{ Form::submit('Delete Theme', array('class' => 'btn btn-sm disabled btn-default')) }}
          {{ Form::close() }}
          
        </li>

      @endif
    </ul>

    </br>

  @endforeach

@endsection

