<?php
namespace Inani\Messager;

use Illuminate\Support\ServiceProvider;

class MessagerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings
     *
     */
    public function register()
    {
        $this->app->bind('messager', function($app) {
            return new Messager;
        });
    }

    /**
     * Boot What is needed
     *
     */
    public function boot()
    {
        require __DIR__ . '/Http/routes.php';
    }
}