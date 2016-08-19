@extends('layouts.master')

@section('header')

<div id="sorts" class="cta-wrapper col-lg-12"> 

	{!! $data->render() !!} 

	<button id="socialWallRunButton" value="run/{{$socialWallId}}" class="btn btn-lg btn-success header-btn-right col-lg-2" onclick="getSocialWallRunData('{{ URL::to('run/socialWall/' . $socialWallId) }}')">Run socialWall</button>

	<h5 class="text-info">Sort By:</h5>
	<button class="sort btn btn-info" data-sort-by="dateAscending">Date Ascending</button>
	<button class="sort btn btn-info" data-sort-by="dateDecending">Date Decending</button>
	<button class="filter btn btn-info" data-filter=".approved">Approved Posts</button>
	<button class="filter btn btn-info" data-filter=".disapproved">Disapproved Posts</button>
	<button class="filter btn btn-info" data-filter=".grid-item">All Posts</button>

</div>

@endsection

@section('content')
	
	@foreach($data as $tweet)

		<div class="grid-item panel panel-info col-lg-3 @if($tweet -> approved === "1") approved @elseif($tweet -> approved === "0") disapproved @endif">
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

		  <div class="panel-body">{{ $tweet -> post_text }}</div>

		  @if($tweet -> post_media != '')

		  	@if($tweet -> media_type === 'video')

		  	 	@if(substr($tweet -> post_id, 0, 2) === 'FB')

		  	 		<div id="{{ $tweet -> post_id }}" class="post-video"></div>

		  	 		<script type="text/javascript">
						  	 		
						  $('#{{ $tweet -> post_id }}').append(createFBVideo('{{ $tweet -> post_media }}'));

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

		  <p class="post-date"> {{ $tweet -> post_date }} </p>
		</div>

	@endforeach

@endsection

@section('scripts')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js">
</script>

<script type="text/javascript">

(function updatePosts(){

  setInterval(function() {

    $.ajax({
      method: 'GET',
      url: '/update/socialWall/' + {{$socialWallId}},
      success: function(response) {

        if(response !== 'no new posts') {

         	var data = JSON.parse(response);
         	
          $('<div class="alert alert-success fade in"><h4 class="alert-message">There are new posts for this socialWall available! Click <button id="showPostButton" class="btn btn-info" data-dismiss="alert">Here</button> to see the new posts!<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></h4></div>').insertBefore('.cta-wrapper');

          $('#showPostButton').click(function() {

          	data.forEach(function(post, index, arr) {

          		var isApproved, logoSrc, media, date;

	          	date = '<p class="post-date">'+ post.post_date +'</p>';

	          	if(post.approved === "1") {

	          		isApproved = 'approved';
	          	} 
	          	else if(post.approved === "0") {

	          		isApproved = 'disapproved';
	          	}

	          	if(post.post_id.charAt(0) === 'T') { 

	          		logoSrc = '{{ asset("assets/twitterLogo_blue.png") }}';
	          	} 
	          	else if(post.post_id.charAt(0) === 'F') {

	          		logoSrc = '{{ asset("assets/facebookLogo.png") }}';
	          	} 
	          	else if(post.post_idcharAt(0) === 'V') { 

	          		logoSrc ='{{ asset("assets/vineLogo.png") }}';
	          	}

	          	if(post.post_media != '') {

			  				if(post.media_type === 'video') {

			  	 				if(post.post_id.charAt(0) === 'F') {

			  	 					media = '<div id="'+post.post_id +'" class="post-video"></div>';

			  	 					$('#'+post.post_id).append(createFBVideo(post.post_media));
						  
			  	 				}
			  	 				else {

			  	 					media = '<video class="post-video" controls="true"><source src="'+post.post_media+'"></video>';
			  	 				}
			  	 			}
			  	 			else {

			  	 				media = '<img class="post-image-custom" src="'+post.post_media+'">';
			  	 			}
			  	 		}
			  	 		else {

			  	 			media = '';
			  	 		}

	          	var postHtmlString ='<div class="grid-item panel panel-info col-lg-3"'+ isApproved +'"><div class="post-header panel-heading"><p class="post-header-username">'+ post.post_username +'</p><div class="channel-logo-wrapper pull-right"><img class="channel-logo" src="' + logoSrc + '"><a class="approval" href="/approve/'+ post.id +'" value="'+ post.id +'"><span class="icon-tick icon icon-checkmark"></span></a><a class="approval" href="/disapprove/'+ post.id +'" value="'+ post.id +'"><span value="0" class="icon icon-close icon-cross"></span></a></div></div><div class="panel-body">'+ post.post_text +'</div>'+ media +'<p class="post-date">'+ post.post_date +'</p></div>';

	          	$('#content-wrapper').prepend(postHtmlString).isotope('reloadItems').isotope({ sortBy: 'dateDecending' });
          	});
          });
        }
      },
      error: function(response) {

        console.log(response);
      }
    });

  }, {{$updateInterval}} * 60000);

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
  function(newElements) {

   	$('#content-wrapper').isotope('appended', newElements);
  });
})();

</script>

@endsection