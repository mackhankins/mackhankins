<?php namespace MH\Providers;

use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
    public function register()
    {
        $app = $this->app;
        $app->bind('MH\Repositories\FileRepositoryInterface', 'MH\Repositories\Local\FileRepository');
    }

}
