$(document).ready(function() {

	// $('.grid').isotope({
	// 	  itemSelector: '.tile',
	// 	  packery: {
	// 	  	horizontal: true,
	// 	  	stamp: '.stamp',
	// 	  	percentPosition: true
	// 	  }
	// 	});
	

	var content = window.opener.content;
	var data = window.opener.data.data;
	var theme = window.opener.data.theme;
	var count = 0;
	var delay;

	if(theme.length > 0) {

		switch(theme[0].view) {

			case 'Tile View': tileView();
			
			break;

			case 'Gallery View': data.forEach(createGalleryViewPosts);

			break;
		}

		if(theme[0].background_image !== "") {

			$('body, .tile-wrapper').css('background-image', 'url(' + theme[0].background_image + ')');
		}

		$('body, .tile, .post-details').css({
			'background-color': theme[0].background_color
		});

		$('.tile').css({
			'border-color': theme[0].border_color
		});

		$('.post-author, .post-text, #previous-post, #next-post').css({
			'color': theme[0].font_color
		});

		delay = theme[0].transition_speed * 1000;
	}
	else {

		tileView();

		delay = 10000;

		$('body').css({
			'background-color': '#262626'
		});
	}

	function createGalleryViewPosts(post, index, arr) {

		var element = '<div class="post-container"><img class="channel-logo"><span id="previous-post" class="icon-previous2"></span><span id="next-post" class="icon-next2"></span><div class="post-wrapper">' +

		'<h2 class="post-author">' + post.post_username +'</h2>';

		if(post.post_media !== "") {

			element += '<div class="post-image-wrapper"> <img class="post-image" src="' + post.post_media + '"></div>';
		}
		else {

			if(theme.length > 0) {
				
				if(theme[0].background_image !== "") {

					element += '<div class="post-image-wrapper"> <img class="post-image" src="' + theme[0].background_image + '"></div>';
				}
			}
		}

		element += '<p class="post-text">' + post.post_text + '</p>' +
		
		'</div></div>' + '';

		content.push(element);

		if(index === arr.length - 1) {

			galleryView(0);
		}
	}

	function galleryView(i) {

	  $('body').html(content[i]);
	  $('.channel-logo').attr('src', '../../assets/twitterLogo_white.png');
		$('.post-wrapper').fadeTo(1300, 1);
		$('.post-container').css({
			'height': window.innerHeight
		});
		$('.post-wrapper').css({
			'transform': 'translateY(-50%) translateX(-50%)',
		});
		$('.post-image').css({
			'max-height': window.innerHeight / 10 * 6,
			'max-width': '100%',
		});

		if(theme.length > 0) {

			$('.post-container').css({
				'background-color': theme[0].background_color
			});

			$('.post-image, .post-wrapper').css({
				'border-color': theme[0].border_color
			});

			$('.post-wrapper, .post-image').css({
				'border-color': theme[0].border_color
			});

			$('.post-author, .post-text, #previous-post, #next-post').css({
				'color': theme[0].font_color
			});

			if(theme[0].background_image !== "") {

				$('.post-container').css('background-image', 'url(' + theme[0].background_image + ')');
			}
		}

		var fadeOut = setTimeout(function() {
			$('.post-wrapper').fadeTo(1000, 0);
		}, delay - 1000);

		var showNextPost = 	setTimeout(function() {

			if(i === content.length - 1) {

				i = 0;
				galleryView(i);
			}
			else {

				galleryView(i + 1);
			}
		}, delay);

		$('#previous-post').click(function() {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			if(i === 0) {

				i = content.length - 1;
				galleryView(i);
			}
			else {

				galleryView(i - 1);
			}
		});

		$('#next-post').click(function() {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			if(i < content.length - 1) {

				galleryView(i + 1);
			}
			else {

				i = 0;
				galleryView(i);
			}
		});
	}

	function tileView() {

		$('body').html('<div class="grid">');

		for(var i = 0; i < data.length; i++) {

			var tile = document.createElement('div');
			var container = document.createElement('div');
			tile.classList.add('tile');
			container.classList.add('tile-wrapper');

			var channelLogo = '<img class="channel-logo" src="http://localhost:8000/assets/twitterLogo_blue.png">';

			var postDetails = '<div class="post-details"><h2 class="post-author">' + data[i].post_username +'</h2><p class="post-text">' + data[i].post_text + '</p></div>';

			$(tile).append(postDetails, channelLogo);
			$(container).append(tile);

			$(tile).css({
				'height': window.innerHeight / 4
			});

			if(data[i].post_media !== "") {

				$(tile).css({
					'background-image': 'url(' + data[i].post_media + ')'
				});
			}
			else {

				if(theme.length > 0) {

					if(theme[0].background_image !== "") {

						$(tile).css({
							'background-image': 'url(' + theme[0].background_image + ')'
						});
					}
				}
				else {

					$(tile).css({
						'background-image': 'url(../../assets/twitterLogo_blue.png)'
					});
				}
			}

			$(container).css({
				'height': window.innerHeight / 4
			});

			$('.grid').append(container);

			count++;

			if(count === data.length - 1) {

				timer(delay, bigTileTransition);
			}
		}
	}

	var tileArray = document.querySelectorAll('.tile');

	function timer(wait, func, argument) {

		return setTimeout(function() {

	  	if(argument) {

	  		if(Array.isArray(argument)) {

					return func.apply(this, argument);
				}
				// if (typeof argument === 'number') {

				// 	return func(argument); 
				// }
	  	}
	  	else {

	  		return func.apply(null); 
	  	}
	   	
	 	}, wait);
	}

	function bigTileTransition() {
		
		var randomNumber = Math.floor((Math.random() * tileArray.length - 1) + 1);

		var position = $(tileArray[randomNumber]).position();
	
		tileArray[randomNumber].classList.add('big-tile');
		tileArray[randomNumber].classList.remove('tile');
		$(tileArray[randomNumber]).css({
			'height': window.innerHeight,
			'z-index': 2
		});

		timer(delay, smallTileTransition, [randomNumber, position]);
	}

	function smallTileTransition(index, position) {

		if (index === undefined) {

			index = 0;
		}
		
		tileArray[index].classList.remove('big-tile');
		$(tileArray[index]).css({
			'left': position.left,
			'top': position.top
		});
		tileArray[index].classList.add('tile');
		$(tileArray[index]).css({'height': window.innerHeight / 4});

		timer(1200, bigTileTransition);

		setTimeout(function() {
			$(tileArray[index]).css({
				'z-index': 1,
				'left': '',
				'top': ''
			});
		}, 1000);
	}
});