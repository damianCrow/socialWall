@extends('layouts.master')

@section('content')

	<h1>User Accounts Index </h1>

	</br>

  @foreach ($users as $user)

    <ul class="list-group">
    	<li class="list-group-item">Username: <strong> {{ $user->username }} </strong></li>
    	<li class="list-group-item">User Id: <strong> {{ $user->id }} </strong></li>
      <li class="list-group-item">Date Added: <strong> {{ $user->created_at }} </strong></li>
      <li class="list-group-item">Email: <strong> {{ $user->email }} </strong></li>

      @if($user->admin == 0) 
      	<li class="list-group-item">Admin User: <strong> No </strong></li>
      @else
      	<li class="list-group-item">Admin User: <strong> Yes </strong></li>
      @endif
      <li class="list-group-item list-group-item-danger">

      <a class="btn btn-sm btn-info" href="{{ URL::to('user/' . $user->id . '/edit') }}">Edit User</a>

      {{ Form::open(array('url' => 'user/' . $user->id, 'class' => 'delete side-by-side')) }}
          {{ Form::hidden('_method', 'DELETE') }}
          {{ Form::submit('Delete This User', array('class' => 'btn btn-sm btn-danger')) }}
      {{ Form::close() }}
      	
			</li>
    </ul>

    </br>

  @endforeach

  <script>

    $(".delete").on("submit", function() {

       return confirm("Are you sure you want to delete this user?");
    });

	</script>
@endsection

