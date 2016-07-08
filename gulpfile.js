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
    'jquery': './node_modules/jquery/dist/',
    'bootstrap': './node_modules/bootstrap-sass/assets/',
    'fontawesome': './node_modules/font-awesome/',
    'simplelineicons': './node_modules/simple-line-icons/',
    'highlightjs': './node_modules/highlightjs/',
    'breakpoint': './node_modules/breakpoint-sass/',
    'clipboard': './node_modules/clipboard/dist/',
    'redactor': './resources/assets/redactor/',
}

elixir(function (mix) {
    mix.sass('app.scss', 'public/css/', {includePaths: [
        paths.bootstrap + 'stylesheets',
        paths.fontawesome + 'scss',
        paths.simplelineicons + 'scss',
        paths.breakpoint + 'stylesheets',
    ]})
        .scripts([
            paths.redactor + 'redactor.js',
            paths.redactor + 'imagemanager.js',
            paths.redactor + 'table.js',
        ], 'public/js/redactor.js', './')
        .scripts([
            paths.jquery + "jquery.js",
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.clipboard + "clipboard.js",
            './resources/assets/prism/prism.js',
        ], 'public/js/app.js', './')
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap')
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome')
        .copy(paths.simplelineicons + 'fonts/**', 'public/fonts/simple-line-icons')
        .copy(paths.redactor + 'redactor.css', 'public/css/redactor.css')
        .copy(paths.redactor + 'redactor-font.eot', 'public/fonts/redactor')
        .version([
            'css/app.css',
            'js/app.js',
            'js/redactor.js'
        ])
});