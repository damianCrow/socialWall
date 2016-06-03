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
          <optgroup value="Facebook Instagram Twitter Vine" label="All Channels">
            <option value="Facebook"> Facebook </option>
            <option value="Instagram"> Instagram </option>
            <option value="Twitter"> Twitter </option>
            <option value="Vine"> Vine </option>
          </optgroup>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 col-lg-offset-1 {{ $errors -> has('targetaccounts') ? 'has-error' : ''}}">
        <label for="targetaccounts"> Target Accounts (optional) </label>
        <input class="form-control" id="targetaccounts" type="text" name="targetaccounts" value="">
      </div>

      <div class="input-group vertical-spacer col-lg-3 {{ $errors -> has('searchcriteria') ? 'has-error' : ''}}">
        <label for="searchcriteria"> Search Hashtags </label>
        <input class="form-control" id="searchcriteria" type="text" name="searchcriteria" value="">
      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 vertical-spacer {{ $errors -> has('themeselect') ? 'has-error' : ''}}">

        <label class="block" for="themeselect"> Select Theme </label>

        <select id="themeselect" name="themeselect" class="form-control" value="{{ Request::old('themeselect')}}">

          <option value="theme 1"> theme 1 </option>
        </select>

      </div>

      <div class="input-group col-lg-offset-1 col-lg-3 vertical-spacer {{ $errors -> has('resultsorder') ? 'has-error' : ''}}">

        <label class="block" for="resultsorder"> Select Results Ordering </label>

        <select id="resultsorder" name="resultsorder" class="form-control" value="">  
          <option class="option" value=" {{ $socialWall['results_order'] }} "> {{ $socialWall['results_order'] }} </option>
          <option class="option" value="Recent"> Recent </option>
          <option class="option" value="Popular"> Popular </option>
          <option class="option" value="Mixed"> Mixed </option>
        </select>

      </div>

      <div class="input-group vertical-spacer col-lg-3 {{ $errors -> has('keywordfilter') ? 'has-error' : ''}}">
        <label for="keywordfilter"> Results Filter Keywords (optional) </label>
        <input class="form-control" id="keywordfilter" type="text" name="keywordfilter" value="{{ Request::old('keywordfilter')}}">
      </div>

      <div class="input-group vertical-spacer col-lg-12">
        {{ Form::submit('Update socialWall', array('class' => 'btn btn-primary')) }}
      </div>

     {{ Form::close() }}

  </div>

  <script>

    $(document).ready(function() {

      for (var i = 0; i < $('#resultsorder')[0].length; i++) {
        
        if($('#resultsorder')[0][i].text == '{{ $socialWall['results_order'] }}') {

          if (i != 0) {

            $('#resultsorder')[0][i].remove();
          }
        }
      }

      $('#mediachannels').multiselect({
        enableClickableOptGroups: true,
      });
        
      @if($media_channels != null)
        @foreach ($media_channels as $channel)
        
          $('#mediachannels').multiselect('select', ['{{$channel}}']);
        @endforeach
      @endif

      $('#targetaccounts').tagsInput({
        'defaultText': 'Account',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });

      @if($target_accounts != null)
        @foreach ($target_accounts as $account)

          $('#targetaccounts').addTag('{{ $account }}');
        @endforeach
      @endif

      $('#searchcriteria').tagsInput({
        'defaultText': 'Add #tag',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });

      @if($hashtags != null)
        @foreach ($hashtags as $hashtag)

          $('#searchcriteria').addTag('{{ $hashtag }}');
        @endforeach
      @endif

      $('#keywordfilter').tagsInput({
        'defaultText': 'Keyword',
        'placeholderColor' : '#333333',
        'height': 'auto',
        'width': '100%'
      });

      @if($filter_keywords != null)
        @foreach ($filter_keywords as $keyword)

          $('#keywordfilter').addTag('{{ $keyword }}');
        @endforeach
      @endif
      
    });

  </script>

@endsection