<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Guards\CustomSessionGuard;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register custom session guard
        Auth::extend('custom-session', function ($app, $name, array $config) {
            $provider = $app['auth']->createUserProvider($config['provider']);
            $guard = new CustomSessionGuard($name, $provider, $app['session.store']);
            
            $guard->setCookieJar($app['cookie']);
            $guard->setDispatcher($app['events']);
            $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            
            return $guard;
        });
    }
}
