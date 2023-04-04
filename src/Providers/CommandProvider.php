<?php

namespace Ite\IotCore\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Ite\IotCore\Commands\RabbitMQConsumerCommand;


class CommandProvider extends ServiceProvider
{

    private array $commands = [
        RabbitMQConsumerCommand::class
    ];

    /**
     * Register any commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    public function boot()
    {
    }
}
