@extends('layouts.master')

@section('header')

<h3 class="cta-wrapper col-lg-12"> 

	{!! $data->render() !!} 

	<button id="socialWallRunButton" value="run/{{$socialWallId}}" class="btn btn-lg btn-success header-btn-right col-lg-2" onclick="getSocialWallRunData('{{ URL::to('run/socialWall/' . $socialWallId) }}')">Run socialWall</button>

</h3>

@endsection

@section('content')
	
	@foreach($data as $tweet)

		<div class="grid-item panel panel-info col-lg-3">
		  <div class="post-header panel-heading">

		  <p class="post-header-username">{{ $tweet -> post_username }}</p>

		  <div class="channel-logo-wrapper pull-right">
		  	<img class="channel-logo" src="@if(substr($tweet -> post_id, 0, 2 ) === 'TW') {{ asset('assets/twitterLogo_blue.png') }} @elseif(substr( $tweet -> post_id, 0, 2 ) === 'FB') {{ asset('assets/facebookLogo.png') }} @elseif(substr( $tweet -> post_id, 0, 2 ) === 'VI') {{ asset('assets/vineLogo.png') }} @endif">

		  	<a class="approval" href="/approve/{{$tweet -> id}}" value="{{$tweet -> id}}">
		  		<span class="icon-tick icon icon-checkmark"></span>
		  	</a>

		  	<a class="approval" href="/disapprove/{{$tweet -> id}}" value="{{$tweet -> id}}">
			  	<span value="0" class="icon icon-close icon-cross"></span>
		  	</a>

		  </div>

		  </div>

		  <div class="@if($tweet -> approved === "1") approved @elseif($tweet -> approved === "0") disapproved @endif panel-body">{{ $tweet -> post_text }}</div>

		  @if($tweet -> post_media != '')

		  	@if($tweet -> media_type === 'video')

		  	 	@if(substr($tweet -> post_id, 0, 2) === 'FB')

		  	 		<div id="{{ $tweet -> post_id }}" class="post-video"></div>

		  	 		<script type="text/javascript">
						  	 		
						  $('#{{ $tweet -> post_id }}').append(createFBVideo('{{ $tweet -> post_media }}'));
						  
							function createFBVideo(src) {

								(function(d, s, id) {
							    var js, fjs = d.getElementsByTagName(s)[0];
							    if (d.getElementById(id)) return;
							    js = d.createElement(s); js.id = id;
							    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
							    fjs.parentNode.insertBefore(js, fjs);
							  }(document, 'script', 'facebook-jssdk'));

							  var FBVideo = '<div class="fb-video post-video" data-href="' + src +'"data-show-text="false"></div>';

							 	return FBVideo;
						 	}

						</script>
		  	 		
		  	 	@else

		  	 		<video class="post-video" controls="true">
			  	 		<source src="{{ $tweet -> post_media }}">
			  	 	</video>
		  	 	@endif

		  	@else

		  		<img class="post-image-custom" src="{{ $tweet -> post_media }}">
		  	@endif
		  @endif

		</div>

	@endforeach

@endsection

@section('scripts')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js">
</script>

<script type="text/javascript">

(function(){

  $('#content-wrapper').infinitescroll({
    loading : {
	    finishedMsg: '<div class="scroll-alert alert-info alert"><h3>Congratulations! You have seen all the posts</h3></div>',
	    msgText: '<div class="scroll-alert alert-info alert"><h3>Loading more posts...</h3></div>',
	    img: "",
	    debug: true,
	    animate: true
	  },
    navSelector : "#header-wrapper .cta-wrapper .pagination",
    nextSelector : "#header-wrapper .cta-wrapper .pagination li.active + li a",
    itemSelector : "#content-wrapper div.grid-item"
  },
  function(newElements){

   	$('#content-wrapper').isotope('appended', newElements);
  });
})();

</script>

@endsection