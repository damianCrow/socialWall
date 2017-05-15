
function sliderView(data) {

	$('body').html('');
	$('body').css({
		'overflow': 'hidden'
	});

	data.forEach(function(post, index, arr) { 

		$('body').append(post);
		$('.slide').css({'left': '-50%', 'top': '5%'});
	});

	// $('.post-text, .post-author').hide();
	
	TweenMax.staggerTo('.slide', 15, {x: '110%', onComplete: slideHorizontally}, 4);

	var postsArray = document.querySelectorAll('.slide');

	for(var s = 0; s < postsArray.length - 1; s++) {

		if($(postsArray[s]).hasClass('in-focus')) {

			$(postsArray[s]).removeClass('in-focus');
		}
	}

	function slideHorizontally() {

		if(!$('.in-focus').length) {
			
			showDetails(this.target, 10000);
		}
		else {

			var randomnumber = Math.ceil(Math.random() * 70) + 5;
			var randomnumber2 = Math.ceil(Math.random() * 30) + 15;

			if(this.target.style.transformX === '110%') {

				this.target.style.transformX = '-50%';
				this.target.style.top = randomnumber + '%';			
			}

			if(this.target.style.transformX === '-50%') {

				return tween(this.target, randomnumber2, slideHorizontally);
			}
		}
	}

	function showDetails (elem, duration) {

		var originalWidth = elem.style.width;
		var originalHeight = elem.style.height;

		TweenMax.killTweensOf(elem);
// FOCUSED TWEEN NOT SHOWN!! \\
		elem.style.opacity = 0;

		focusTween(elem, 10000, function() {
// COMENTED CODE BELOW STOPS TWEEN ZOMING IN AND OUT. \\
			// elem.style.opacity = 0.8;
			// regularSize(elem, originalWidth, originalHeight, tween(elem, 15, slideHorizontally));
		});
	}

	function tween(elem, duration, callBack) {

		TweenMax.to(elem, duration, {
			left: '110%', 
			onComplete: callBack
		});
	}

	function focusTween(elem, duration, callBack) {

		$(elem).addClass('in-focus');

		setTimeout(function() {

			// $(elem).find($('.post-text, .post-author')).show();

			// TweenMax.from(elem, 15, {
			// 	left: '110%'
			// 	// top: 0
			// });

			TweenMax.to(elem, 1.5, {
				// left: '10%', 
				// top: '10%', 
				// height: '80%', 
				// width: '80%', 
				// zIndex: 100, 
				onComplete: function() {

					// if($(elem).find($('.vine-video')).length && $(elem).hasClass('in-focus')) {

					// 	$(elem).find($('.vine-video'))[0].play();
					// 	$(elem).find($('.vine-video'))[0].onended = function() {

					// 		return callBack();
					// 	}
					// }
					// else if($(elem).find($('.fb-video')).length && $(elem).hasClass('in-focus')) {

					// 	playFbVideo(0, duration, $(elem).find($('.fb-video'))[0].id, callBack);
					// }
					// else {

						setTimeout(function() {

							return callBack();
						}, duration);
					// }
				}
			});
		}, 3000);
	}

	function regularSize(elem, originalWidth, originalHeight, callBack) {

		TweenMax.to(elem, 1.5, {
			// top: 0, 
			height: originalHeight, 
			width: originalWidth, 
			zIndex: 1, 
			onComplete: callBack
		});

		$(elem).removeClass('in-focus');
		$(elem).find($('.post-text, .post-author')).hide();
	}
}
