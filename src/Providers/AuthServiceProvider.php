<?php

namespace Ite\IotCore\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Ite\IotCore\Context\ModuleContext;
use Ite\IotCore\Guards\AdminJWTGuard;
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

    public function boot(ModuleContext $moduleContext)
    {
        $this->registerPolicies();

        if ($moduleContext->isAdminModule())
            $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
                $guard = new AdminJWTGuard(
                    $app['tymon.jwt'],
                    $app['auth']->createUserProvider($config['provider']),
                    $app['request']);
                $app->refresh('request', $guard, 'setRequest');
                return $guard;
            }
            );
        else
            $this->app['auth']->extend('jwt', function ($app) {
                $guard = new JWTGuard($app['tymon.jwt'], $app['request']);
                $app->refresh('request', $guard, 'setRequest');
                return $guard;
            }
            );
        $this->app['auth']->setDefaultDriver('api');
    }
}
