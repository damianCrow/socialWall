@extends('layouts.master')

@section('content')

<h1>Hello, welcome to socialWall {{ Auth::user()['username'] }}</h1>

@endsection