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
	var data = window.opener.data;
	var count = 0;

	tileView();
	// galleryView(0);
	
	function galleryView(i) {

	  $('body').html(content[i]);
	  $('.channel-logo').attr('src', '../../assets/twitterLogo_white.png');
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
				galleryView(i);
			}
			else {

				galleryView(i + 1);
			}
		}, 10000);

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

		$('body, .grid').css('background-color', '#262626');

		$('body').html('<div class="grid">');

		for(var i = 0; i < data.length; i++) {

			var tile = document.createElement('div');
			var container = document.createElement('div');
			tile.classList.add('tile');
			container.classList.add('tile-wrapper');

			var postDetails = '<div class="post-details"><h2 class="post-author">' + data[i].post_username +'</h2><p class="post-text">' + data[i].post_text + '</p></div>';

			$(tile).append(postDetails);
			$(container).append(tile);

			if(data[i].post_media !== "") {

				$(tile).css({
					'background-image': 'url(' + data[i].post_media + ')',
					'height': window.innerHeight / 4
				});
			}
			else {
				$(tile).css({
					'background-image': 'url(../../assets/twitterLogo_blue.png)',
					'height': window.innerHeight / 4
				});
			}

			$(container).css({
				'height': window.innerHeight / 4
			});

			$('.grid').append(container);

			count++;

			if(count === data.length - 1) {

				timer(10000, bigTileTransition);
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

		timer(10000, smallTileTransition, [randomNumber, position]);
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