<?php

namespace Ite\IotCore\Commands;

use Exception;
use Illuminate\Console\Command;
use Ite\IotCore\Context\UserActivityManager;
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

    /**
     * The Manager of Users Activities.
     *
     * @var UserActivityManager
     */
    public UserActivityManager $manager;

    /**
     * The Broker Connection.
     *
     * @var AMQPStreamConnection
     */
    public AMQPStreamConnection $connection;
    protected AbstractChannel|AMQPChannel $channel;
    public mixed $queue;


    /**
     * @throws Exception
     */
    public function __construct(UserActivityManager $context)
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
        $this->manager = $context;
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
                if (!is_null($json['expireAt']))
                    $json['expireAt'] = date_parse($json['expireAt']);
                $mapper = new JsonMapper();
                /** @var UserActivity $userActivity */
                $userActivity = $mapper->map($json, new UserActivity());
                $this->manager->add($userActivity);
            });

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->manager->clear();
        $this->channel->close();
        $this->connection->close();
    }
}
