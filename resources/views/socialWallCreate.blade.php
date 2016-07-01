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

    <h2 class="vertical-spacer"> Create New socialWall </h2>
    
    <form action="{{ URL::to('socialWall/') }}" method="post">

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('name') ? 'has-error' : ''}}">
        <label for="name"> Enter socialWall Name </label>
        <input id="name" class="form-control" type="text" name="name" value="{{ Request::old('name')}}">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 col-sm-6 vertical-spacer {{ $errors -> has('mediachannels') ? 'has-error' : ''}}">

        <label class="block" for="mediachannels[]"> Select Media Channels </label>

        <select id="mediachannels" name="mediachannels[]" class="hide form-control" value="{{ Request::old('')}}" multiple>
          <optgroup value="Facebook Instagram Twitter Vine" label="All Channels">
            <option value="Facebook"> Facebook </option>
            <option value="Instagram"> Instagram </option>
            <option value="Twitter"> Twitter </option>
            <option value="Vine"> Vine </option>
          </optgroup>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 col-lg-offset-1 {{ $errors -> has('targetaccounts') ? 'has-error' : ''}}">
        <label for="targetaccounts"> Target Accounts </label>
        <input class="form-control" id="targetaccounts" type="text" name="targetaccounts" value="{{ Request::old('targetaccounts')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('searchcriteria') ? 'has-error' : ''}}">
        <label for="searchcriteria"> Search Hashtags </label>
        <input class="form-control" id="searchcriteria" type="text" name="searchcriteria" value="{{ Request::old('searchcriteria')}}">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 col-sm-6 vertical-spacer {{ $errors -> has('themeselect') ? 'has-error' : ''}}">

        <label class="block" for="themeselect"> Select Theme </label>

        <select id="themeselect" name="themeselect" class="form-control" value="{{ Request::old('themeselect')}}">
          @foreach ($themes as $theme)
            <option value="{{ $theme -> name }}"> {{ $theme -> name }} </option>
          @endforeach
        </select>

      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 col-sm-6 vertical-spacer {{ $errors -> has('resultsorder') ? 'has-error' : ''}}">

        <label class="block" for="resultsorder"> Select Results Ordering </label>

        <select id="resultsorder" name="resultsorder" class="form-control" value="{{ Request::old('')}}">  
          <option > Recent </option>
          <option > Popular </option>
          <option > Mixed </option>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-sm-6 {{ $errors -> has('keywordfilter') ? 'has-error' : ''}}">
        <label for="keywordfilter"> Results Filter Keywords </label>
        <input class="form-control" id="keywordfilter" type="text" name="keywordfilter" value="{{ Request::old('keywordfilter')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-12 col-sm-12">
        <button type="submit" class="btn btn-primary vertical-spacer"> Save socialWall </button>
        <input type="hidden" name="_token" value="{{ Session::token()}}">
      </div>

    </form>
  </div>

  <script>
  
    $(document).ready(function() {

      $('#mediachannels').multiselect({
        enableClickableOptGroups: true
      });

      $('#targetaccounts').tagsInput({
        'defaultText': 'Optional.',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });

      $('#searchcriteria').tagsInput({
        'defaultText': '#...',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });

      $('#keywordfilter').tagsInput({
        'defaultText': 'Optional.',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });
    });

  </script>

@endsection