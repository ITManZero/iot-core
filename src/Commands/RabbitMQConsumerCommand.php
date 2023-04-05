<?php

namespace Ite\IotCore\Commands;

use Exception;
use Illuminate\Console\Command;
use Ite\IotCore\Context\ModuleContext;
use Ite\IotCore\Managers\UserActivityManager;
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

    protected ModuleContext $moduleContext;

    /**
     * @throws Exception
     */
    public function __construct(UserActivityManager  $manager,
                                AMQPStreamConnection $connection,
                                ModuleContext        $moduleContext)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->manager = $manager;
        $this->moduleContext = $moduleContext;
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->moduleContext->isAdminModule())
            throw new Exception('admin module acts as producer not consumer');

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
                $mapper->bStrictNullTypes = false;
                $userActivity = $mapper->map($json, new UserActivity());
                $this->manager->add($userActivity);
            });

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}
