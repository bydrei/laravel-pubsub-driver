<?php
namespace ByDrei\Queue\Connectors;

use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use ByDrei\Queue\PubSubQueue;
use Google\Cloud\PubSub\PubSubClient;

class PubSubConnector implements ConnectorInterface
{
    /**
     * @param array $config
     * @return PubSubQueue|Queue
     */
    public function connect(array $config)
    {
        return new PubSubQueue(
            new PubSubClient($config)
        );
    }
}
