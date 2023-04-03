<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;
use Ite\IotCore\Context\ModuleContext;
use Ite\IotCore\Context\UserActivityContext;
use Ite\IotCore\Managers\ModuleManager;
use Ite\IotCore\Managers\UserActivityManager;

class CoreProvider extends ServiceProvider
{
    private array $providers = [
        AuthServiceProvider::class
    ];

    public array $managers = [
        UserActivityManager::class,
        ModuleManager::class,
    ];

    public array $contexts = [
        UserActivityContext::class,
        ModuleContext::class,
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

        foreach ($this->managers as $manager)
            $this->app->singleton($manager);

        foreach ($this->contexts as $context)
            $this->app->singleton($context);

        $this->commands($this->commands);
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {
        foreach ($this->managers as $manager)
            $this->app->make($manager)->init();
    }
}
