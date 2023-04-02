<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Support\Facades\Cache;
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
        
        $context = Cache::get(UserActivityContext::class);
        if (is_null($context))
            Cache::put(UserActivityContext::class, $context = UserActivityContext::getInstance());

        $this->app->singleton(UserActivityContext::class, function () use ($context) {
            return $context;
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
