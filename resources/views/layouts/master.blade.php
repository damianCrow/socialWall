<!DOCTYPE html>
<html>
  <head>
      <title> socialWall </title>

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

      <link rel="stylesheet" href="{{ elixir("css/app.css") }}">
  </head>
  <body>

  	@include('partials.navBar')
  	
  	<div id="wrapper" class="container">

      @if (Session::has('message'))
        <div class="alert alert-success fade in">
          <h4 class="alert-message"> 

            {{ Session::get('message') }}
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

          </h4>
        </div>
      @endif

      @yield('content')

    </div>
  </body>
</html>
