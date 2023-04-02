<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;
use Ite\IotCore\Context\UserActivityContext;

class CoreProvider extends ServiceProvider
{
    private array $providers = [
    ];

    /**
     * Register services.
     */
    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
        $this->app->singleton(UserActivityContext::class);
        $this->commands([
            RabbitMQConsumerCommand::class
        ]);
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {

    }
}
