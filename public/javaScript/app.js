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
    onChange: function(option, checked) {
      
      if(!$('#' + $(option).val()).length) {
        
        var Accounts = '<div id="' + $(option).val() + '" class="input-group vertical-spacer col-lg-3 col-sm-6">'
          + '<label for="' + $(option).val() + 'accounts"> Add ' + $(option).val() + ' Target Accounts </label>'
          + '<input class="form-control" id="' + $(option).val() + 'accounts" type="text" name="' + $(option).val() + 'accounts">'
        + '</div>'
        
        $(Accounts).insertBefore($('.submit-button'));
        
        createTagsInput($('#' + $(option).val() + 'accounts'));
      }
      if($('#' + $(option).val()).length) {

        if(option[0].selected === false) {

          $('#' + $(option).val()).remove();
          $('#' + $(option).val() + 'accounts').tagsInput('destroy');
        }
      }
    }
  });

  createTagsInput('#targetaccounts');
  createTagsInput('#searchcriteria');
  createTagsInput('#keywordfilter'); 

  function createTagsInput(ele) {

    $(ele).tagsInput({
      'defaultText': 'Add Tag',
      'placeholderColor' : '#333333',
      'height': 'auto',
      'width': '100%'
    });
  }

  $(".delete").on("submit", function() {

     return confirm("Are you sure you want to delete this socialWall?");
  });
});

function selectChannel(ele, value) {

  $(ele).multiselect('select', [value], true);
}

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

function createView() {

	var newView = window.open($('#socialWallRunButton').val(), '', 'height='+screen.height +', width='+screen.availWidth+ ', scrollbars=no', true);
}