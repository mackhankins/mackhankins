<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => '/', 'uses' => 'Pub\IndexController@index']);
Route::get('/blog', ['as' => 'blog', 'uses' => 'Pub\BlogController@index']);
Route::get('/blog/{slug}', 'Pub\BlogController@single');
Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/* Admin */
Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => 'is.admin'], function () {
    
        Route::group(['prefix' => 'admin'], function () {
        
            Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\IndexController@index']);
            Route::get('post/create', ['uses' => 'Admin\IndexController@create']);
            Route::get('post/edit/{id}', ['uses' => 'Admin\IndexController@edit']);
            Route::post('post/store', ['uses' => 'Admin\IndexController@store']);
            Route::post('post/update/{id}', ['uses' => 'Admin\IndexController@update']);
            Route::get('post/delete/{id}', ['uses' => 'Admin\IndexController@delete']);
            Route::post('post/upload', ['uses' => 'Admin\IndexController@upload']);

        });
    });
});

/* Sitemap */
Route::get('sitemap', function () {

    // create new sitemap object
    $sitemap = App::make("sitemap");
    // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
    // by default cache is disabled
    $sitemap->setCache('laravel.sitemap', 3600);
    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached()) {
    // add item to the sitemap (url, date, priority, freq)
        $sitemap->add(URL::to('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(URL::to('/blog'), '2012-08-25T20:10:00+02:00', '1.0', 'weekly');
        // get all posts from db
        $posts = DB::table('posts')->where('type', '=', 'post')->where('status', '=', 'published')->orderBy('created_at', 'desc')->get();
        // add every post to the sitemap
        foreach ($posts as $post) {
            $sitemap->add(action('Pub\BlogController@single', $post->slug), $post->updated_at, '0.5', 'weekly');
        }
    }

    // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
    return $sitemap->render('xml');
});
