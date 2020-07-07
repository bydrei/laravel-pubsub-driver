<?php
namespace ByDrei;

use ByDrei\PubSubJob;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue as QueueInterface;
use Illuminate\Queue\Queue;
use Google\Cloud\PubSub\PubSubClient;

class PubSubQueue extends Queue implements QueueInterface
{
    /**
     * @var PubSubClient
     */
    protected $pubSubClient;

    public function __construct(
        PubSubClient $pubSubClient
    ) {
        $this->pubSubClient = $pubSubClient;
    }

    /**
     * @param null $queue
     * @return int
     */
    public function size($queue = null): int
    {
        return 0;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string|object $job
     * @param  string $data
     * @param  string $queue
     * @return void
     */
    public function push($job, $data = '', $queue = null): void
    {
        if ($queue === null) {
            return;
        }

        $this->pushToPubSub(
            $queue,
            $this->createPayload($job, $queue, $data)
        );
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTimeInterface|\DateInterval|int $delay
     * @param  string|object                        $job
     * @param  mixed                                $data
     * @param  string                               $queue
     * @return void
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        // FUTURE: no need to implement currently
        return;
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string $queue
     * @return Job|null
     */
    public function pop($queue = null) :? Job
    {
        if ($queue === null) {
            return null;
        }

        $topic = $this->getClient()->topic($queue);
        $messages = $topic->subscription($queue . ".subscription")
            ->pull(["maxMessages" => 1, "returnImmediately" => false]);


        if (empty($messages) === true) {
            return null;
        }

        return new PubSubJob(
            $this->container,
            $this->pubSubClient,
            $messages[0],
            $this->connectionName,
            $queue
        );
    }

    /**
     * Published message to pub sub queue.
     *
     * @param string $queue
     * @param string $payload
     * @return void
     */
    protected function pushToPubSub(string $queue, string $payload): void
    {
        $topic = $this->getClient()
            ->topic($queue);

        if ($topic->exists() === false) {
            return;
        }

        $topic->publish(['data' => $payload]);
    }

    /**
     * @return PubSubClient
     */
    protected function getClient() : PubSubClient
    {
        return $this->pubSubClient;
    }

    /**
     * @param string $payload
     * @param null $queue
     * @param array $options
     * @return void
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        // FUTURE: no need to implement currently
        return;
    }
}
