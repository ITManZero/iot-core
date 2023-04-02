<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;
use Ite\IotCore\Context\UserActivityContext;
use Ite\IotCore\Context\UserActivityManager;

class CoreProvider extends ServiceProvider
{
    private array $providers = [
    ];

    private array $singletons = [
        UserActivityManager::class,
        UserActivityContext::class,
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

        foreach ($this->singletons as $singleton)
            $this->app->singleton($singleton);

        $this->commands($this->commands);
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
