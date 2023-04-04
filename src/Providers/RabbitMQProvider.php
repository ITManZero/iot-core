<?php

namespace Ite\IotCore\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Ite\IotCore\Guards\JWTGuard;
use PhpAmqpLib\Connection\AMQPStreamConnection;


class RabbitMQProvider extends ServiceProvider
{

    /**
     * Register any services.
     */
    public function register()
    {
        $this->app->singleton(AMQPStreamConnection::class, function () {
            return new AMQPStreamConnection(
                env('RABBITMQ_HOST'),
                env('RABBITMQ_PORT'),
                env('RABBITMQ_USER'),
                env('RABBITMQ_PASSWORD'),
                env('RABBITMQ_VHOST')
            );
        });
    }

    public function boot()
    {

    }
}
