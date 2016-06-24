<?php namespace MH\Providers;

use Menu;
use Illuminate\Support\ServiceProvider;

/**
 * Class MenuServiceProvider
 * @package MH\Providers
 */
class MenuServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Menu::make('public', function ($menu) {
        
            $menu->add('Home', ['route' => '/']);
            $menu->add('Blog', ['route' => 'blog']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
