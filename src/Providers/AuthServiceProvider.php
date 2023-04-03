<?php

namespace Ite\IotCore\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Guards\CustomJWTGuard;
use Illuminate\Support\Facades\Auth;
use Ite\IotCore\Guards\JWTGuard;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function register()
    {

    }

    public function boot()
    {
        $this->registerPolicies();
        $this->app['auth']->extend(
            'jwt',
            function ($app, $name, array $config) {
                $guard = new JWTGuard(
                    $app['tymon.jwt'],
                    $app['auth']->createUserProvider($config['provider']),
                    $app['request']
                );
                $app->refresh('request', $guard, 'setRequest');
                return $guard;
            }
        );
        $this->app['auth']->setDefaultDriver('jwt');
    }
}
