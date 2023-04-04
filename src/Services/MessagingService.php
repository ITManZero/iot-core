<?php

namespace Ite\IotCore\Services;

use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessagingService
{
    public AMQPStreamConnection $connection;
    public AbstractChannel|AMQPChannel $channel;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $connection->channel();
    }

    public function publish(string $exchange, string $key, $data): void
    {
        $message = new AMQPMessage(json_encode($data));

        $this->channel->basic_publish($message, $exchange, $key);
    }

}