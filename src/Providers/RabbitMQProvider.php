<?php

namespace Ite\IotCore\Providers;

use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;


class RabbitMQProvider
{

    public AMQPStreamConnection $connection;
    public AbstractChannel|AMQPChannel $channel;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );;
        $this->channel = $this->connection->channel();
        $this->init();
    }

    private function init(): void
    {
        $config = config('rabbitmq');

        // creating exchange
        $exchanges = $config['exchanges'];
        foreach ($exchanges as $exchange) {
            $this->channel->exchange_declare(
                $exchange['name'],
                $exchange['type'],
                $exchange['passive'],
                $exchange['durable'],
                $exchange['auto_delete']);
        }

        // creating and binding queues
        $queues = $config['queues'];
        foreach ($queues as $queue) {
            $this->channel->queue_declare($queue['name']);
            $this->channel->queue_bind($queue['name'], $queue['exchange'], $queue['bind']);
        }
    }
}
