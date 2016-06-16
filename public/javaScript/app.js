// SOCIAL WALL APPROVAL CODE \\

$(document).ready(function() {

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
var i = 0;

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

    		var data = JSON.parse(response);
    		data.forEach(createPost);
    	}
    },
    error: function(response) {

    	console.log(response);
    }
  });
}

function createPost(post, index, arr) {

	var element = '<div class="post-container"><span id="previous-post" class="icon-previous2"></span><span id="next-post" class="icon-next2"></span><div class="post-wrapper">' +

	'<h2 class="post-author">' + post.post_username +'</h2>';

	if(post.post_media !== "") {

		element += '<div class="post-image-wrapper"> <img class="post-image" src="' + post.post_media + '"></div>';
	}

	element += '<p class="post-text">' + post.post_text + '</p>' +
	
	'</div></div>';

	content.push(element);

	if(index === arr.length - 1) {

		postTransition(i);
	}
}

function postTransition(i) {

	$('body').html(content[i]);
	$('.post-wrapper').fadeTo(1300, 1);
	$('.post-container').css({
		'height': window.innerHeight
	});
	$('.post-wrapper').css({
		'transform': 'translateY(-50%) translateX(-50%)'
	});
	$('.post-image').css({
		'max-height': window.innerHeight / 10 * 6,
		'max-width': '100%'
	});

	var fadeOut = setTimeout(function() {
		$('.post-wrapper').fadeTo(1000, 0);
	}, 9000);

	var showNextPost = 	setTimeout(function() {

		if(i === content.length - 1) {

			i = 0;
			postTransition(i);
		}
		else {

			postTransition(i + 1);
		}
	}, 10000);

	$('#previous-post').click(function() {

		clearTimeout(showNextPost);
		clearTimeout(fadeOut);

		if(i === 0) {

			i = content.length - 1;
			postTransition(i);
		}
		else {

			postTransition(i - 1);
		}
	});

	$('#next-post').click(function() {

		clearTimeout(showNextPost);
		clearTimeout(fadeOut);

		if(i < content.length - 1) {

			postTransition(i + 1);
		}
		else {

			i = 0;
			postTransition(i);
		}
	});
}