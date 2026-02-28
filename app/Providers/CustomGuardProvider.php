<?php

namespace App\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CustomGuardProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::resolved(function ($auth) {
            $auth->extend('session', function ($app, $name, $config) {
                $provider = Auth::createUserProvider($config['provider']);
                
                $guard = new SessionGuard(
                    $name,
                    $provider,
                    $app['session.store'],
                    $app['request']
                );

                $sessionNames = [
                    'internal' => 'growtalks_internal_session',
                    'web' => 'growtalks_web_session',
                ];

                if (isset($sessionNames[$name])) {
                    $sessionStore = $app['session.store'];
                    if (!isset($app['session.guard_names'])) {
                        $app['session.guard_names'] = [];
                    }
                    $app['session.guard_names'][$name] = $sessionNames[$name];
                }

                return $guard;
            });
        });
    }
}
