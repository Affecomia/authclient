<?php

namespace YoAuth\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use YoAuth\Guards\AuthGuard;

class YoAuthClientProvider extends ServiceProvider
{
    /**
     * Bootstrap services
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/yoauth.php', 'yoauth'
        );

        Auth::extend('yoauth', function($app, $name, array $config) {
            return new AuthGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
    }

    /**
     * Register services
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
