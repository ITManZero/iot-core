<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;

class CoreProvider extends ServiceProvider
{
    private array $providers = [
        UserActivityProvider::class
    ];

    /**
     * Register services.
     */
    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {

    }
}
