var gulp = require('gulp');
var _ = require('underscore');
var Elixir = require('laravel-elixir');

var $ = Elixir.Plugins;
var config = Elixir.config;
var Task = Elixir.Task;

/*
 |----------------------------------------------------------------
 | Prism custom builds
 |----------------------------------------------------------------
 |
 | Prism helps you highlisht your code syntax with nice beautiful
 | themes.
 |
 | Once in your gulpfile.js and after elixir is imported, it can
 | be used as follows:
 | ```js
 | require('./elixir-prism');
 |
 | elixir(function(mix) {
 |		mix.prism({
 |			folder: 'bower_components/prism',
 |			theme: 'okaidia',
 |			components: ['*'],
 |			// components: ['css', 'css-extras', 'c', clike', 'markdown', 'php']
 |			// and more... https://github.com/PrismJS/prism/tree/gh-pages/components
 |			plugins: []
 |		})
 | });
 | ```
 | Node dependencies:
 |{
 |	"devDependencies": {
 |		"gulp": "^3.8.8"
 | 	},
 |	"dependencies": {
 |		"laravel-elixir": "^3.3.2",
 |	 	"underscore": "^1.8.3",
 |		"underscore-deep-extend": "0.0.5"
 |	}
 |}
 |
 */
Elixir.extend('prism', function(options) {
    options = _.extend(config.prism, {
        theme: 'okaidia',
        cssOutputFolder: 'vendor/prism/css',
        jsOutputFolder: 'vendor/prism/js'
    }, options);

    // components array
    var components = ['core']
        .concat(options.components)
        .map(function(component) {
            return options.folder + 'components/prism-' + component + '.js';
        });

    // plugins array
    var plugins = []
        .concat(options.plugins)
        .map(function(plugin) {
            return options.folder + 'plugins/' + plugin;
        });

    // All scripts
    var scripts = components.concat(plugins);

    // Styles
    var styles = options.folder +'themes/prism-' + options.theme + '.css';

    new Task('prism', function() {
        return (
            gulp
                .src(scripts
                    .concat([
                        '!/**/components/**/*.min.js',
                        '!/**/plugins/**/*.min.js'
                    ])
                )
                .pipe($.if(config.sourcemaps, $.sourcemaps.init()))
                .pipe($.concat('prism.js'))
                .on('error', function(e) {
                    new Elixir.Notification().error(e, 'Prism Scripts Compilation Failed!');
                    this.emit('end');
                })
                .pipe($.if(config.production, $.uglify()))
                .pipe($.if(config.sourcemaps, $.sourcemaps.write('.')))
                .pipe(gulp.dest([config.assetsPath, options.jsOutputFolder].join('/')))
                .pipe(new Elixir.Notification('Prism scripts compiled!'))

            &&

            gulp
                .src(styles)
                .pipe($.if(config.sourcemaps, $.sourcemaps.init()))
                .pipe($.concat('prism.css'))
                .on('error', function(e) {
                    new Elixir.Notification().error(e, 'Prism Styles Compilation Failed!');
                    this.emit('end');
                })
                .pipe($.if(config.production, $.uglify()))
                .pipe($.if(config.sourcemaps, $.sourcemaps.write('.')))
                .pipe(gulp.dest([config.assetsPath, options.cssOutputFolder].join('/')))
                .pipe(new Elixir.Notification('Prism styles compiled!'))
        );
    });
});