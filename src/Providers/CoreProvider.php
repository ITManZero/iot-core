<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;
use Ite\IotCore\Context\UserActivityManager;

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
        $this->app->singleton(UserActivityManager::class);
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {
        $this->app->make(UserActivityManager::class)->init();
    }
}
