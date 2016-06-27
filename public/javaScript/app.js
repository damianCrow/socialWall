// SOCIAL WALL APPROVAL CODE \\

$(document).ready(function() {

	$(window).on('load', function(){

		$('.grid').isotope({
		  itemSelector: '.grid-item',
		  packery: {
		  	stamp: '.stamp',
		  	percentPosition: true
		  }
		});
	});

	$(window).on('hashchange', function(){

    $('.grid').isotope();
	});

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
	});

// SOCIAL WALL MULTISELECT AND TAGSINPUT CODE \\

  $('#mediachannels').multiselect({
    enableClickableOptGroups: true,
  });

  $('#targetaccounts').tagsInput({
    'defaultText': 'Account',
    'placeholderColor' : '#333333',
    'height': 'auto',
    'width': '100%'
  });

  $('#searchcriteria').tagsInput({
    'defaultText': 'Add #tag',
    'placeholderColor' : '#333333',
    'height': 'auto',
    'width': '100%'
  });

  $('#keywordfilter').tagsInput({
    'defaultText': 'Keyword',
    'placeholderColor' : '#333333',
    'height': 'auto',
    'width': '100%'
  });

  $(".delete").on("submit", function() {

     return confirm("Are you sure you want to delete this socialWall?");
  });
});

// SOCIALWALL RUN CODE \\

var content = [];
var data = [];

function getSocialWallRunData(url) {

 	$.ajax({
    method: 'GET',
    url: url,
    success: function(response) {

    	if(typeof(JSON.parse(response)) !== 'object') {

    		var message = '<div class="alert alert-warning fade in"><h4 class="alert-message">' + response + '<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></h4></div>';

          $(message).insertBefore('.cta-wrapper');
    	}
    	else {

    		data = JSON.parse(response);
        createView();
    	}
    },
    error: function(response) {

    	console.log(response);
    }
  });
}

// function createPost(post, index, arr) {

// 	var element = '<div class="post-container"><img class="channel-logo"><span id="previous-post" class="icon-previous2"></span><span id="next-post" class="icon-next2"></span><div class="post-wrapper">' +

// 	'<h2 class="post-author">' + post.post_username +'</h2>';

// 	if(post.post_media !== "") {

// 		element += '<div class="post-image-wrapper"> <img class="post-image" src="' + post.post_media + '"></div>';
// 	}

// 	element += '<p class="post-text">' + post.post_text + '</p>' +
	
// 	'</div></div>' + '';

// 	content.push(element);

// 	if(index === arr.length - 1) {

// 		
// 	}
// }

function createView() {

	var newView = window.open($('#socialWallRunButton').val(), '', 'height='+screen.height +', width='+screen.availWidth+ ', scrollbars=no', true);
}