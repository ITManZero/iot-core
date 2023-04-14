<?php

namespace Ite\IotCore\Services;

use Ite\IotCore\Providers\RabbitMQProvider;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessagingService
{
    public AMQPStreamConnection $connection;
    public AbstractChannel|AMQPChannel $channel;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $rabbitmqProvider = new RabbitMQProvider();
        $this->connection = $rabbitmqProvider->connection;
        $this->channel = $rabbitmqProvider->channel;
    }

    public function publish(string $exchange, string $key, $data): void
    {
        $message = new AMQPMessage(json_encode($data));

        $this->channel->basic_publish($message, $exchange, $key);
    }

}