var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
    'jquery': './vendor/bower_components/jquery/',
    'bootstrap': './vendor/bower_components/bootstrap-sass-official/assets/',
    'fontawesome': './vendor/bower_components/fontawesome/',
    'simplelineicons': './vendor/bower_components/simple-line-icons/',
    'highlightjs': './vendor/bower_components/highlightjs/'
}

elixir(function(mix) {
    mix.sass('app.scss', 'public/css/', {includePaths: [paths.bootstrap + 'stylesheets', paths.fontawesome + 'scss', paths.simplelineicons + 'scss']})
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap')
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome')
        .copy(paths.simplelineicons + 'fonts/**', 'public/fonts/simple-line-icons')
        .copy(paths.highlightjs + 'styles/github.css', 'public/css/github.css')
        .scripts([
            paths.jquery + "dist/jquery.js",
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.highlightjs + 'highlight.pack.js',
            './resources/javascripts/**/*.js',
        ], 'public/js/app.js', './')
        .version([
            'css/app.css',
            'js/app.js'
        ])
});
