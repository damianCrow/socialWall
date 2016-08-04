@extends('layouts.master')

@section('content')

@endsection

@section('scripts')

<script id="facebook-script" src="{{ URL::asset('javaScript/fbSdkJs.js') }}"></script>

<script id="run-view-script" type="text/javascript" src="{{ URL::asset('javaScript/runView.js') }}"></script>

<script id="slider-script" src="{{ URL::asset('javaScript/slider.js') }}"></script>

@endsection