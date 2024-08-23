<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;
use Tymon\JWTAuth\JWTManager;
use Tymon\JWTAuth\Claims\Factory as ClaimsFactory;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(JWTManager::class, function ($app) {
            $factory = $app->make(ClaimsFactory::class);
            $token = new JWTAuth($app->make('tymon.jwt.config'), $factory);

            $token->setClaims([
                'name' => $app->auth->user()->name,
                'email' => $app->auth->user()->email,
            ]);

            return $token;
        });
    }
}
