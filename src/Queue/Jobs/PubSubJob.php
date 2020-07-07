<?php
namespace ByDrei\Queue\Jobs;

use Google\Cloud\PubSub\Message;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;

class PubSubJob extends Job implements JobContract
{
    private const ACK_DEADLINE_IN_SEC = 10;

    /**
     * @var PubSubClient
     */
    protected $pubSubClient;

    /**
     * @var Message
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param  Container $container
     * @param  PubSubClient $pubSubClient
     * @param  Message $job
     * @param  string $connectionName
     * @param  string $queue
     * @return void
     */
    public function __construct(
        Container $container,
        PubSubClient $pubSubClient,
        Message $job,
        string $connectionName,
        string $queue
    ) {
        $this->pubSubClient = $pubSubClient;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
        $this->connectionName = $connectionName;
    }

    /**
     * @param int $delay
     * @return void
     */
    public function release($delay = 0): void
    {
        parent::release($delay);

        $subscription = $this->pubSubClient->topic($this->queue)
            ->subscription($this->queue . '.subscription');
        $subscription->modifyAckDeadline($this->job, self::ACK_DEADLINE_IN_SEC);
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        parent::delete();

        $subscription = $this->pubSubClient->topic($this->queue)
            ->subscription($this->queue . '.subscription');
        $subscription->acknowledge($this->job);
    }

    /**
     * @return int
     */
    public function attempts(): int
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->job->id();
    }

    /**
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->job->data();
    }

    /**
     * @return PubSubClient
     */
    public function getPubSub(): PubSubClient
    {
        return $this->pubSubClient;
    }

    /**
     * @return Message
     */
    public function getPuSubJob(): Message
    {
        return $this->job;
    }
}
