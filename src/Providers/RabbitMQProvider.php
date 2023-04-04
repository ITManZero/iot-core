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
        /** @var AMQPStreamConnection $connection */
        $connection = $this->app->make(AMQPStreamConnection::class);
        $channel = $connection->channel();
        $exchange_name = "user-activity";
        $queue_name = "blocked-users";
        $binding_key = "blocked";
        $channel->exchange_declare($exchange_name, 'direct', false, false, false);
        $channel->queue_declare($queue_name);
        $channel->queue_bind($queue_name, $exchange_name, $binding_key);
    }
}
