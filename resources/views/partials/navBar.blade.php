<header>
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="@if(!Auth::user()) {{ URL::to('/') }} @else {{ URL::to('dashboard') }} @endif">socialWall</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      @if (Route::current()->getUri() !== '/')

        <ul class="nav navbar-nav navbar-right">
          <li @if (Route::current()->getUri() === "dashboard") class="active" @endif>
            <a href="{{ route('dashboard') }}"> Home </a>
          </li>
          <li @if (strpos($_SERVER['REQUEST_URI'], "socialWall") !== false) class="active" @endif>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> socialWall
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="{{ URL::to('socialWall/create') }}"> Create New socialWall </a></li>
              <li><a tabindex="-1" href="{{ URL::to('socialWall') }}"> Run, Edit Or Delete socialWall </a></li>
            </ul>
          </li>
          <li @if (strpos($_SERVER['REQUEST_URI'], "theme") !== false) class="active" @endif>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Theme
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="{{ URL::to('theme/create') }}"> Create New Theme </a></li>
              <li><a tabindex="-1" href="{{ URL::to('theme') }}"> Preview, Edit Or Delete Theme </a></li>
            </ul>
          </li>
          <li @if (strpos($_SERVER['REQUEST_URI'], "user") !== false) class="active" @endif>

            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Account
              <span class="caret"></span>
            </a>

            <ul class="dropdown-menu">
            
              <li><a tabindex="-1" href="{{ URL::to('user/' . Auth::user()->id . '/edit') }}"> Edit My Account </a></li>

              @if (Auth::user()->isAdmin())
                <li><a tabindex="-1" href="{{ URL::to('user/create') }}"> Add A New User </a></li>
                <li><a tabindex="-1" href="{{ URL::to('user') }}"> Edit Or Delete User </a></li>
              @endif
            </ul> 
          </li>
          <li>
            <a href="{{ url('/') }}" class="btn-default btn-default" role="button"> Sign Out </a>
          </li>
        </ul>

      @endif
      
    </div>
  </div>
</nav>
</header>