<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;
use Ite\IotCore\Context\UserActivityContext;
use Ite\IotCore\Models\UserActivity;

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

        $context = UserActivityContext::getInstance();
        $context->add(new UserActivity());
        $context->add(new UserActivity());
        $this->app->singleton(UserActivityContext::class, $context);
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {

    }
}
