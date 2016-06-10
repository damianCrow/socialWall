@extends('layouts.master')

@section('content')

	<h3>{!! $data->render() !!}</h3>

	@foreach($data as $tweet)

		<div class="panel panel-info col-lg-3 col-md-4">
		  <div class="post-header panel-heading">{{ $tweet -> post_username }}

		  <div class="channel-logo-wrapper pull-right">
		  	<img class="channel-logo" src="{{ asset('assets/twitterLogo_white.png') }}">

		  	<a class="approval" href="/approve" value="{{$tweet -> id}}">
		  		<span class="icon-tick icon icon-checkmark"></span>
		  	</a>

		  	<a class="approval" href="/disapprove" value="{{$tweet -> id}}">
			  	<span value="0" class="icon icon-close icon-cross"></span>
		  	</a>

		  </div>

		  </div>

		  <div class="@if($tweet -> approved === "1") approved @elseif($tweet -> approved === "0") disapproved @endif panel-body">{{ $tweet -> post_text }}</div>

		  @if($tweet -> post_media != '')

		  	<img class="col-lg-12 col-md-12" src="{{ $tweet -> post_media }}">
		  @endif

		</div>

	@endforeach

	<script type="text/javascript">
	
		$('.approval').click(function(evt) {

	    evt.preventDefault();

	    if ($(this).attr('href') === '/approve') {

	    	$(this).parents().eq(2).find('.panel-body').removeClass('disapproved').toggleClass('approved');
	    }
	    if ($(this).attr('href') === '/disapprove') {

	    	$(this).parents().eq(2).find('.panel-body').removeClass('approved').toggleClass('disapproved');
	    }

	    $.ajax({
	        method: 'GET',
	        url: $(this).attr('href'),
	        data: {id:$(this).attr('value')}
	    });
		})

</script>

@endsection
