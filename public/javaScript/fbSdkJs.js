var fbVideoPlayersArray = [];
	
  window.fbAsyncInit = function() {
    FB.init({
      appId      : 'your-app-id',
      xfbml      : true,
      version    : 'v2.7'
    });

    FB.Event.subscribe('xfbml.ready', function(msg) {

		  if(msg.type === 'video') {
		     
		    fbVideoPlayersArray.push([msg.id, msg.instance]);
		  }
		});
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
