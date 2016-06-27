@extends('layouts.master')

@section('content')

	<h1> socialWalls Index </h1>

	</br>

  @foreach ($socialWalls as $socialWall)

    <ul class="list-group">
    	<li class="list-group-item">Name: <strong> {{ $socialWall->name }} </strong></li>
    	<li class="list-group-item">socialWall Id: <strong> {{ $socialWall->id }} </strong></li>
      <li class="list-group-item">User Id: <strong> {{ $socialWall->user_id }} </strong></li>
      <li class="list-group-item">Date Added: <strong> {{ $socialWall->created_at }} </strong></li>
      <li class="list-group-item">Media Channels: <strong> {{ $socialWall->media_channels }} </strong></li>
      <li class="list-group-item">Hashtags: <strong> {{ $socialWall->search_hashtags }} </strong></li>
      <li class="list-group-item">Associated Theme: <strong> {{ $socialWall->theme }} </strong></li>
      <li class="list-group-item">Results Ordering: <strong> {{ $socialWall->results_order }} </strong></li>
      <li class="list-group-item">Targeted Accounts: <strong> {{ $socialWall->target_accounts }} </strong></li>
      <li class="list-group-item">Filter Keywords: <strong> {{ $socialWall->filter_keywords }} </strong></li>

      @if(Auth::user()->isAdmin() || Auth::user()->id === $socialWall->user_id)

        <li class="list-group-item list-group-item-danger">

          <a class="btn btn-sm btn-success" href="{{ URL::to('socialWall/' . $socialWall->id) }}">Run socialWall</a>
          <a class="btn btn-sm btn-info" href="{{ URL::to('socialWall/' . $socialWall->id . '/edit') }}">Edit socialWall</a>

          {{ Form::open(array('url' => 'socialWall/' . $socialWall->id, 'class' => 'delete side-by-side')) }}
              {{ Form::hidden('_method', 'DELETE') }}
              {{ Form::submit('Delete This socialWall', array('class' => 'btn btn-sm btn-danger')) }}
          {{ Form::close() }}
        	
  			</li>
      @else 

        <li class="list-group-item list-group-item-warning">

          <a class="btn btn-sm btn-success" href="{{ URL::to('socialWall/' . $socialWall->id) }}">Run socialWall</a>
          <a class="btn btn-default btn-sm disabled" href="{{ URL::to('/') }}">Edit socialWall</a>

          {{ Form::open(array('url' => '/', 'class' => 'delete side-by-side')) }}
              {{ Form::hidden('_method', 'GET') }}
              {{ Form::submit('Delete socialWall', array('class' => 'btn btn-sm disabled btn-default')) }}
          {{ Form::close() }}
          
        </li>

      @endif
    </ul>

    </br>

  @endforeach

@endsection

