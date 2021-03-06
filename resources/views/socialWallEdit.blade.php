@extends('layouts.master')

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

  <div class="col-md-10 col-md-offset-1 form-inline">

    <h2 class="vertical-spacer"> Edit socialWall </h2>
    
    {{ Form::model($socialWall, array('route' => array('socialWall.update', $socialWall->id), 'method' => 'PUT')) }}

      <div class="input-group vertical-spacer col-lg-3 {{ $errors -> has('name') ? 'has-error' : ''}}">
        <label for="name"> Enter socialWall Name </label>
        <input id="name" class="form-control" type="text" name="name" value="{{ $socialWall['name']}}">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 vertical-spacer {{ $errors -> has('mediachannels') ? 'has-error' : ''}}">

        <label class="block" for="mediachannels[]"> Select Media Channels </label>

        <select id="mediachannels" name="mediachannels[]" class="hide form-control" value="" multiple>
          
          <option value="Facebook"> Facebook </option>
          <option value="Instagram"> Instagram </option>
          <option value="Twitter"> Twitter </option>
          <option value="Vine"> Vine </option>
          
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-lg-offset-1 {{ $errors -> has('searchcriteria') ? 'has-error' : ''}}">
        <label for="searchcriteria"> Search Hashtags </label>
        <input class="form-control" id="searchcriteria" type="text" name="searchcriteria" value="">
      </div>

      <div class="input-group col-lg-3 vertical-spacer {{ $errors -> has('themeselect') ? 'has-error' : ''}}">

        <label class="block" for="themeselect"> Select Theme </label>

        <select id="themeselect" name="themeselect" class="form-control" value="{{ Request::old('themeselect')}}">

          <option value="Default Theme" @if($socialWall['theme'] === 'Default Theme') selected @endif> Default Theme </option>
          @foreach ($themes as $theme)
            <option value="{{ $theme -> name }}" @if($socialWall['theme'] === $theme['name']) selected @endif> {{ $theme -> name }} </option>
          @endforeach
        </select>

      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 vertical-spacer {{ $errors -> has('resultsorder') ? 'has-error' : ''}}">

        <label class="block" for="resultsorder"> Select Results Ordering </label>

        <select id="resultsorder" name="resultsorder" class="form-control">  
          <option  @if($socialWall['results_order'] === 'Recent') selected @endif> Recent </option>
          <option  @if($socialWall['results_order'] === 'Popular') selected @endif> Popular </option>
          <option  @if($socialWall['results_order'] === 'Mixed') selected @endif> Mixed </option>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-lg-offset-1 {{ $errors -> has('keywordfilter') ? 'has-error' : ''}}">
        <label for="keywordfilter"> Results Filter Keywords (optional) </label>
        <input class="form-control" id="keywordfilter" type="text" name="keywordfilter" value="{{ Request::old('keywordfilter')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-3 {{ $errors -> has('updateinterval') ? 'has-error' : ''}}">
        <label for="updateinterval"> Enter Update Interval (in minutes) </label>
        <input id="updateinterval" class="form-control" type="text" name="updateinterval" value="{{ $socialWall['update_interval']}}">
      </div>

      <div class=" submit-button input-group vertical-spacer col-lg-12">
        {{ Form::submit('Update socialWall', array('class' => 'btn btn-primary')) }}
      </div>

     {{ Form::close() }}

  </div>

@endsection

@section('scripts')
  
  <script>

    $(window).load(function() {

      @if($media_channels != null)
        @foreach($media_channels as $channel)
        
          selectChannel('#mediachannels','{{$channel}}');  
     
        @endforeach
      @endif

      @if(!empty($target_accounts))
        @foreach($target_accounts as $key => $account)

          @foreach($account as $tag)

            $('#{{$key}}').addTag('{{ $tag }}'); 
          @endforeach

        @endforeach
      @endif

      @if($hashtags != null)
        @foreach ($hashtags as $hashtag)

          $('#searchcriteria').addTag('{{ $hashtag }}');
        @endforeach
      @endif

      @if($filter_keywords != null)
        @foreach ($filter_keywords as $keyword)

          $('#keywordfilter').addTag('{{ $keyword }}');
        @endforeach
      @endif

    });
  </script>
@endsection