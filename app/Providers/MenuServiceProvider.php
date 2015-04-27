<?php namespace MH\Providers;

use Menu;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Menu::make('public', function($menu) {
            $menu->add('Home', array('route' => '/'));
            $menu->add('Blog', array('route' => 'blog'));
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
