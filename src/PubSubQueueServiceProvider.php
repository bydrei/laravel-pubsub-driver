<?php
namespace ByDrei;

use Bydrei\Queue\Connectors\PubSubConnector;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class PubSubQueueServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $config = $this->app['config']->get('queue', []);
        $this->app['config']->set('queue', $this->mergeConfig(require __DIR__.'/../config/queue.php', $config));
    }

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
            __DIR__.'/../config/queue.php' => config_path('queue.php'),
        ], 'config');
    }

    /**
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    private function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (! is_array($value) || ! Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }
}
