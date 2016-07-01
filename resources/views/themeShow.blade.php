@extends('layouts.master')

@section('content')

	@if($theme -> view === 'Tile View')

		<div class="tile-wrapper-preview tile-wrapper" style="width: 100%; height: 100%; background-color: {{ $theme -> background_color }}; @if($theme -> background_image !== "") background-image: url('{{ $theme -> background_image }}'); @endif">

			@for($i = 0; $i < 10; $i++)

			<div class="tile tile-preview" style="position: relative; border-color: {{ $theme -> border_color }}; background-color: {{ $theme -> background_color }}; background-image: url(@if($theme -> placeholder_image !== "") '{{ $theme -> placeholder_image }}' @else '{{ asset('assets/5774f4753496d-gtb-detroit.png') }}' @endif);">
				<img class="channel-logo" src="http://localhost:8000/assets/twitterLogo_blue.png">
			</div>

			@endfor

			<div class="big-tile-preview big-tile" style="background-image: url(@if($theme -> placeholder_image !== "") '{{ $theme -> placeholder_image }}' @else '{{ asset('assets/5774f4753496d-gtb-detroit.png') }}' @endif); 
					border: 1px solid {{ $theme -> border_color }};
			    background-repeat: no-repeat;
			    background-size: 100% auto;
			    background-color: {{ $theme -> background_color }};
			    background-position: 50% 50%;
			    position: absolute;
			    left: 20%;
			   	width: 60%;
			   	">
			    <div class="post-details" style="opacity: 0.75; background-color: {{ $theme -> background_color }};">
				    <h2 class="post-author" style="color: {{ $theme -> font_color }}">BuyFordGT</h2>
				    <p class="post-text" style="color: {{ $theme -> font_color }}">L C #Ford: Mustang Shelby #GT350 16 shelby #GT 350 competition orange exterior track package low milesâ¦ #Cars #Car https://t.co/rctQdZBJoA</p>
			    </div>

			    <img class="channel-logo" src="{{ asset('assets/twitterLogo_blue.png') }}">

			</div>

	  </div>

	@elseif($theme -> view === 'Gallery View')

		<div style="background-color: {{ $theme -> background_color }};">
			<div class="post-container post-container-preview" style="@if($theme -> background_image !== "") background-image: url('{{ $theme -> background_image }}'); @endif background-color: {{ $theme -> background_color }};">
				<img class="channel-logo" src="../../assets/twitterLogo_white.png">
				<span id="previous-post" class="icon-previous2" style="color: {{ $theme -> font_color }};"></span>
				<span id="next-post" class="icon-next2" style="color: {{ $theme -> font_color }};"></span>
				<div class="post-wrapper" style="opacity: 1; transform: translateY(-50%) translateX(-50%); border-color: {{ $theme -> border_color }};">
					<h2 class="post-author" style="color: {{ $theme -> font_color }};">GTB_Tweets</h2>
					<div class="post-image-wrapper"> 
					<img class="post-image" src="@if($theme -> placeholder_image !== "") {{ $theme -> placeholder_image }} @else {{ asset('assets/5774f4753496d-gtb-detroit.png') }}  @endif" style="max-height: 492.6px; max-width: 100%; border-color: {{ $theme -> border_color }};"></div>
					<p class="post-text" style="color: {{ $theme -> font_color }};">#Spotted. @MonsterEnergy here to #unleashthebeast. Thanks for the free #energy! https://t.co/gj3rW94Gnk</p>
				</div>
			</div>
		</div>

	@endif

    <script type="text/javascript">

    	$('.tile-wrapper-preview, .big-tile-preview, .post-container-preview').css({
    		'height': window.innerHeight
    	})

    	$('.tile-preview').css({
    		'height': window.innerHeight / 4
    	})


    </script>
@endsection
