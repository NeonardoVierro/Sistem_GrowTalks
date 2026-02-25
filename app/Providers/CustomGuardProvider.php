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
        // Customize session guard untuk menggunakan session names berbeda
        Auth::resolved(function ($auth) {
            $auth->extend('session', function ($app, $name, $config) {
                $provider = Auth::createUserProvider($config['provider']);
                
                $guard = new SessionGuard(
                    $name,
                    $provider,
                    $app['session.store'],
                    $app['request']
                );

                // Set session name berdasarkan guard
                $sessionNames = [
                    'internal' => 'growtalks_internal_session',
                    'web' => 'growtalks_web_session',
                ];

                if (isset($sessionNames[$name])) {
                    // Modify the session handler untuk gunakan session name yang berbeda
                    $sessionStore = $app['session.store'];
                    // Store original name
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
