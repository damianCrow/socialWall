var elixir = require('laravel-elixir');

require( 'elixir-jshint' );
 

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
        .version(["public/css/app.css"]);
});

elixir( function( mix ) {
    mix.jshint( ['public/javaScript/*.js', '!js/vendor/*.js'] );
});

