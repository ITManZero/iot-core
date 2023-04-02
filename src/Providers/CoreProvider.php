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

    private array $commands = [
        RabbitMQConsumerCommand::class
    ];

    /**
     * Register services.
     */
    public function register()
    {
        foreach ($this->providers as $provider)
            $this->app->register($provider);

        $this->commands($this->commands);

//        $this->app->singleton(UserActivityContext::class);
        $this->app->bind(UserActivityContext::class, function () {
            return new UserActivityContext();
        });
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {

    }
}
