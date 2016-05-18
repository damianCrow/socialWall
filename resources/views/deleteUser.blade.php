@extends('layouts.master')

@section('content')

	
    @foreach ($users as $user)

	    <ul>
	    	<li>Username: {{ $user->username }}</li>
	    	<li>User Id: {{ $user->id }}</li>
	      <li>Date Added: {{ $user->created_at }}</li>
	      <li>Email: {{ $user->email }}</li>

	      @if($user->admin == 0) 
	      	<li>Admin User: No </li>
	      @else
	      	<li>Admin User: Yes </li>
	      @endif

	      <form action="{{ route('deleteuser') }}" method="delete">
		      <button onclick="return confirm('Are you sure you want to PERMANENTLY delete this user?')" type="submit" class="btn btn-danger">Delete</button>
		      <input type="hidden" name="_token" value="{{ Session::token()}}">
		    </form>

	    </ul>
	    </br>

    @endforeach
  

@endsection