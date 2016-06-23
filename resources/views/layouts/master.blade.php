<!DOCTYPE html>
<html>
  <head>
      <title> socialWall </title>

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
      <link rel="stylesheet" href="{{ elixir("css/app.css") }}">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js">
      </script>
      <script type="text/javascript" src="https://npmcdn.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
      <script type="text/javascript" src="{{ URL::asset('javaScript/app.js') }}"></script>
  </head>
  <body>

  	@include('partials.navBar')
  	
  	<div id="wrapper" class="container-fluid">

      <div class="row-fluid col-md-10 col-md-offset-1">

        @if (Session::has('message'))
          <div class="alert alert-success fade in">
            <h4 class="alert-message"> 

              {{ Session::get('message') }}
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

            </h4>
          </div>
        @endif

        @yield('header')
      </div>

      <div class="grid row-fluid col-md-10 col-md-offset-1">

        @yield('content')

      </div>
    </div>

    @yield('scripts')
  </body>
</html>
