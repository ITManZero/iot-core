<?php

namespace Ite\IotCore\Commands;

use Exception;
use Illuminate\Console\Command;
use Ite\IotCore\Context\UserActivityContext;
use Ite\IotCore\Models\UserActivity;
use JsonMapper;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq-consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public UserActivityContext $context;
    public AMQPStreamConnection $connection;
    protected AbstractChannel|AMQPChannel $channel;
    public mixed $queue;


    /**
     * @throws Exception
     */
    public function __construct(UserActivityContext $context)
    {
        parent::__construct();
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $this->channel = $this->connection->channel();
        $this->queue = env('RABBITMQ_QUEUE_NAME');
        $this->context = $context;
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $this->channel->basic_consume('blocked-users',
            '',
            false,
            true,
            false,
            false,
            function (AMQPMessage $message) {
                $json = json_decode($message->body);
                $mapper = new JsonMapper();
                /** @var UserActivity $userActivity */
                echo $message->body;
                $userActivity = $mapper->map($json, new UserActivity());
                echo $userActivity;
                $this->context->add($userActivity);
            });

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->context->clear();
        $this->channel->close();
        $this->connection->close();
    }
}
