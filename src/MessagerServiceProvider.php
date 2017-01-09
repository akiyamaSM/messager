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
        $this->app->bind('Message', function () {
            return new Message;
        });
    }

    /**
     * Boot What is needed
     *
     */
    public function boot()
    {
        // php artisan vendor:publish
        $this->publishes([
            __DIR__. '/migrations/2017_01_02_201510_create_messages_table.php'
            => base_path('database/migrations/2017_01_02_201510_create_messages_table.php')
        ]);
    }
}
