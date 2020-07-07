<?php
namespace ByDrei\Providers;

use App\Queue\Connectors\PubSubConnector;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class PubSubQueueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        /** @var QueueManager $manager */
        $manager = $this->app['queue'];
        $manager->addConnector('pubsub', function () {
            return new PubSubConnector;
        });

        $this->publishes([
            __DIR__.'/config/queue.php' => config_path('queue.php'),
        ]);
    }
}