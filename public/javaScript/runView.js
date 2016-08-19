$(document).ready(function() {

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

			case 'Default View': tileView();

			break;

			case 'Slider View': data.forEach(createSliderViewPosts);

			break;
		}

		if(theme[0].background_image !== "") {

			$('body').css('background-image', 'url(' + theme[0].background_image + ')');
		}

		$('body, .tile, .slide, .post-details, .post-text').css({
			'background-color': theme[0].background_color
		});

		$('.tile, .slide').css({
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

	// function createFBVideo(src, postId) {

	//   var FBVideo = '<div class="post-video-wrapper"><div id="' + postId + '" class="fb-video post-video" data-href="' + src +'"data-show-text="false"></div></div>';

	//  	return FBVideo;
 // 	}

	function assignLogo(post) {

		var src;

		switch(post.post_id.substring(0, 2)) {

			case 'FB': src = '../../assets/facebookLogo.png';

			break;

			case 'TW': src = '../../assets/twitterLogo_blue.png';

			break;

			case 'VI': src = '../../assets/vineLogo.png';

			break;
		}

		return src;
	}

	function createSliderViewPosts(post, index, arr) {

		if(content.length < data.length) {

			var slide = document.createElement('div');
			var container = document.createElement('div');
			slide.classList.add('slide');
			container.classList.add('slide-wrapper');

			var channelLogo = '<img class="channel-logo" src="' + assignLogo(post) + '">';

			var postDetails = '<div class="post-content"><h2 class="post-author">' + post.post_username +'</h2><p class="post-text">' + post.post_text + '</p></div>';

			if(post.post_media !== "") {

					if(post.media_type === 'image') {

						$(slide).css({
							'background-image': 'url(' + post.post_media + ')',
							'height': '18%',
							'width': '32%'
						});
					}
					else if(post.post_id.substring(0, 2) === 'FB') {

						$(slide).append(createFBVideo(post.post_media, post.post_id));
						$(slide).css({
							'height': '26%',
							'width': '28%'
						});
					}
					else {

						var video = '<div class="post-video-wrapper"><video class="post-video vine-video" controls="true"><source src="' + post.post_media + '"></source></video></div>';

						$(slide).append(video);	
						$(slide).css({
							'height': '35%',
							'width': '20%',
						});		
					}	
				}
				else {

					if(theme.length > 0) {

						if(theme[0].placeholder_image !== "") {

							$(slide).css({
								'background-image': 'url(' + theme[0].placeholder_image + ')',
								'height': '18%',
								'width': '32%'
							});
						}
					}
					else {

						// $(tile).css({
						// 	'background-image': 'url(../../assets/twitterLogo_blue.png)'
						// });
					}
				}

			$(slide).append(postDetails, channelLogo);
			$(container).append(slide);
			content.push(container);
		}

		if(index === arr.length - 1) {

			sliderView(content);
		}
	}

	function createGalleryViewPosts(post, index, arr) {

		if(content.length < data.length) {

			var element = '<div class="post-container"><img class="channel-logo" src="' + assignLogo(post) + '"><span id="previous-post" class="icon-previous2"></span><span id="next-post" class="icon-next2"></span><div class="post-wrapper">' +

			'<h2 class="post-author">' + post.post_username +'</h2>';

			if(post.post_media !== "") {

				if(post.media_type === 'image') {

					element += '<div class="post-image-wrapper"> <img class="post-image" src="' + post.post_media + '"></div>';
				}
				else if(post.post_id.substring(0, 2) === 'FB') {

					element += createFBVideo(post.post_media, post.post_id);
				}
				else {
					element += '<div class="post-video-wrapper"> <video class="post-video" controls="true"><source src="' + post.post_media + '""></video></div>';
				}
			}
			else {

				if(theme.length > 0) {
					
					if(theme[0].placeholder_image !== "") {

						element += '<div class="post-image-wrapper"> <img class="post-image" src="' + theme[0].placeholder_image + '"></div>';
					}
				}
			}

			element += '<p class="post-text">' + post.post_text + '</p>' +
			
			'</div></div>' + '';

			content.push(element);
		}

		if(index === arr.length - 1) {

			galleryView(0);
		}
	}

	function galleryView(i) {

		function NextPost() {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			if(i < content.length - 1) {

				galleryView(i + 1);
			}
			else {

				i = 0;
				galleryView(i);
			}
		}

		function previousPost() {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			if(i === 0) {

				i = content.length - 1;
				galleryView(i);
			}
			else {

				galleryView(i - 1);
			}
		}

	  $('body').html(content[i]);
		$('.post-wrapper').fadeTo(1300, 1);
		$('.post-container').css({
			'height': window.innerHeight
		});
		$('.post-wrapper').css({
			'transform': 'translateY(-50%) translateX(-50%)',
		});
		$('.post-image, .post-video').css({
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

			NextPost();

		}, delay);

		$('#previous-post').click(function() {

			previousPost();
		});

		$('#next-post').click(function() {

			NextPost();
		});

		if($('.post-wrapper').find($('.post-video')).is('video')) {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			setTimeout(function() {

				$('.post-wrapper').find($('.post-video'))[0].play();	

			}, 1500);
			
			$('.post-wrapper').find($('.post-video'))[0].onended = function(e) {

				timer(500, NextPost);
			};
		}
		else if($('.post-wrapper').find($('.fb-video')).length > 0) {

			clearTimeout(showNextPost);
			clearTimeout(fadeOut);

			setTimeout(function() {

				// FB.XFBML.parse();
				// playFbVideo(500, 10000, $('.post-wrapper').find($('.fb-video'))[0].id, function() {

					timer(delay, NextPost);
				// });
			}, 1500)
		}
		else {};
	}

	function tileView() {

		$('body').html('<div class="grid">');

		for(var i = 0; i < data.length; i++) {

			var tile = document.createElement('div');
			var container = document.createElement('div');
			tile.classList.add('tile');
			container.classList.add('tile-wrapper');

			var channelLogo = '<img class="channel-logo" src="' + assignLogo(data[i]) + '">';

			var postDetails = '<div class="post-details"><h2 class="post-author">' + data[i].post_username +'</h2><p class="post-text">' + data[i].post_text + '</p></div>';

			$(tile).append(postDetails, channelLogo);
			$(container).append(tile);

			$(tile).css({
				'height': window.innerHeight / 4
			});

			if(data[i].post_media !== "") {

				if(data[i].media_type === 'image') {

					$(tile).css({
						'background-image': 'url(' + data[i].post_media + ')'
					});
				}
				else if(data[i].post_id.substring(0, 2) === 'FB') {

					 $(tile).append(createFBVideo(data[i].post_media, data[i].post_id));
				}
				else {

					var video = '<div class="post-video-wrapper"> <video class="post-video" controls="true"><source src="' + data[i].post_media + '"></source></video></div>';

					$(tile).append(video);			
				}	
			}
			else {

				if(theme.length > 0) {

					if(theme[0].placeholder_image !== "") {

						$(tile).css({
							'background-image': 'url(' + theme[0].placeholder_image + ')'
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

				timer(3000, bigTileTransition);
			}
		}
	}

	var tileArray = document.querySelectorAll('.tile');

// ADD DYNAMIC DELAY AND ARGUMENTS TO ANY FUNCTION \\	

	function timer(wait, func, argument) {

		return setTimeout(function() {

	  	if(argument) {

	  		if(Array.isArray(argument)) {

					return func.apply(this, argument);
				}
				if(typeof argument === 'number') {

					return func(argument); 
				}
	  	}
	  	else {

	  		return func(); 
	  	}
	   	
	 	}, wait);
	}

// FIND AND RETURN THE CORRECT PLAYER FROM THE fbVideoPlayersArray \\

	// function findPlayer(array, videoId) {

	// 	for(var i = 0; i < array.length; i++) {

	// 		if(videoId === array[i][0]) {

	// 			var player = array[i][1]; 
	// 		}
	// 	}

	// 	return player;
	// }

// TRANSITION TILE TO BIGGER SIZE AND CENTER IT IN VIEW. IF THERE IS A VIDEO IN THE BIG TILE, PLAY IT \\

	function bigTileTransition() {
		
		var randomNumber = Math.floor((Math.random() * tileArray.length - 1) + 1);

		var position = $(tileArray[randomNumber]).position();
		
		tileArray[randomNumber].classList.remove('tile');
		tileArray[randomNumber].classList.add('big-tile');
		$(tileArray[randomNumber]).css({
			'height': window.innerHeight,
			'z-index': 2
		});

		if($(tileArray[randomNumber]).find($('.fb-video')).length > 0) {

			playFbVideo(1500, 10000, $(tileArray[randomNumber]).find($('.fb-video'))[0].id, function() {

				timer(1000, smallTileTransition, [randomNumber, position]);
			});
		}
		else if($(tileArray[randomNumber]).find($('.post-video')).is('video')) {

			setTimeout(function() {

				$(tileArray[randomNumber]).find($('.post-video'))[0].play();

			}, 1000);
			
			$(tileArray[randomNumber]).find($('.post-video'))[0].onended = function() {

			  return timer(1000, smallTileTransition, [randomNumber, position]);
			};
		}
		else {

			return timer(delay, smallTileTransition, [randomNumber, position]);
		}
	}

	// function playFbVideo(playDelay, playTime, videoId, callBack) {

	// 	if(fbVideoPlayersArray.length > 1) {

	// 		var player = findPlayer(fbVideoPlayersArray, videoId);
	// 	}
	// 	else {

	// 		var player = fbVideoPlayersArray[0][1];
	// 	}
	// 	console.log(player);
	// 	if(player !== undefined) {

	// 		setTimeout(function() {

	// 			player.play();

	// 		}, playDelay)

	// 		setTimeout(function() {

	// 			player.pause();

	// 			callBack();

	// 		}, playTime)
	// 	}
	// 	else {

	// 		callBack();
	// 	}
	// }

// TRANSITION TILE BACK TO ORIGINAL SIZE \\

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

		setTimeout(function() {

			$(tileArray[index]).css({
				'z-index': 1,
				'left': '',
				'top': ''
			});

			timer(200, bigTileTransition);

		}, 1000);
	}
});

function playFbVideo(playDelay, playTime, videoId, callBack) {

		if(fbVideoPlayersArray.length > 1) {

			var player = findPlayer(fbVideoPlayersArray, videoId);
		}
		else {

			var player = fbVideoPlayersArray[0][1];
		}
		console.log(player);
		if(player !== undefined) {

			setTimeout(function() {

				player.play();

			}, playDelay)

			setTimeout(function() {

				player.pause();

				callBack();

			}, playTime)
		}
		else {

			callBack();
		}
	}

	function findPlayer(array, videoId) {

		for(var i = 0; i < array.length; i++) {

			if(videoId === array[i][0]) {

				var player = array[i][1]; 
			}
		}

		return player;
	}