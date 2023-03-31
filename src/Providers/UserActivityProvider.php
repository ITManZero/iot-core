<?php

namespace Ite\IotCore\Providers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Ite\IotCore\Models\UserActivity;
use JsonMapper;
use JsonMapper_Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class UserActivityProvider extends ServiceProvider
{
    private $connection;
    private $channel;

    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('blockedUsers', function () {
            return [];
        });
    }


    /**
     * Bootstrap services.
     * @throws Exception
     */
    public function boot(): void
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'localhost'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USERNAME', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );
        $this->channel = $this->connection->channel();

        $this->channel->basic_consume('blocked-users',
            '',
            false,
            true,
            false,
            false,
            'consumeMessage');

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        register_shutdown_function(function () {
            $this->app->singleton('blockedUsers', function () {
                return [];
            });
            $this->channel->close();
            $this->connection->close();
        });
    }


    /**
     * @throws BindingResolutionException
     * @throws JsonMapper_Exception
     */
    public function consumeMessage(AMQPMessage $message)
    {
        $json = json_decode($message->body, true);
        $mapper = new JsonMapper();
        $mapper->map($json, new UserActivity());
        $blockedUsers = $this->app->make('blockedUsers');
        $blockedUsers[] = $json;
    }
}
