<?php
//
//namespace Ite\IotCore\Jobs;
//
//use Exception;
//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Foundation\Bus\Dispatchable;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Queue\Jobs\Job;
//use Illuminate\Queue\SerializesModels;
//use Illuminate\Support\Facades\Log;
//use Ite\IotCore\Context\UserActivityContext;
//use Ite\IotCore\Models\UserActivity;
//use JsonMapper;
//use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Message\AMQPMessage;
//
//class UserActivityManagerJob implements ShouldQueue
//{
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
//
//    public $context;
//    public $connection;
//    protected $channel;
//    public $queue;
//
//    /**
//     * Create a new job instance.
//     *
//     * @return void
//     * @throws Exception
//     */
//    public function __construct(UserActivityContext $context)
//    {
//        $this->connection = new AMQPStreamConnection(
//            env('RABBITMQ_HOST'),
//            env('RABBITMQ_PORT'),
//            env('RABBITMQ_USER'),
//            env('RABBITMQ_PASSWORD')
//        );
//
//        $this->channel = $this->connection->channel();
//        $this->queue = env('RABBITMQ_QUEUE_NAME');
//        $this->context = $context;
//    }
//
//    /**
//     * Execute the job.
//     *
//     * @return void
//     */
//    public function handle(): void
//    {
//        $this->channel->basic_consume('blocked-users',
//            '',
//            false,
//            true,
//            false,
//            false,
//            function (AMQPMessage $message) {
//                $json = json_decode($message->body, true);
//                $mapper = new JsonMapper();
//                $userActivity = $mapper->map($json, new UserActivity());
//                $this->context->add($userActivity);
//            });
//
//        while (count($this->channel->callbacks)) {
//            $this->channel->wait();
//        }
//    }
//
//    /**
//     * Handle a job failure.
//     *
//     * @return void
//     * @throws Exception
//     */
//    public function failed(): void
//    {
//        $this->context->clear();
//        $this->connection->close();
//    }
//
//    /**
//     * Release any resources used by the job.
//     *
//     * @return void
//     * @throws Exception
//     */
//    public function __destruct()
//    {
//        $this->context->clear();
//        $this->channel->close();
//        $this->connection->close();
//    }
//}