<?php

namespace Push\Support;


use Push\Models\PusherLogWrapper;
use Illuminate\Support\ServiceProvider;

/**
 * Class PushServiceProvider
 * @package Push
 */
class PushServiceProvider extends ServiceProvider
{

    protected $defer = true;


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(
            'Pusher',
            function ($app, $params) {
                $pusher = new \Pusher(
                    \Config::get('pusher.app_key'),
                    \Config::get('pusher.app_secret'),
                    \Config::get('pusher.app_id'),
                    ['encrypted' => \App::environment() == 'production']
                );

                $logger = new PusherLogWrapper();
                $pusher->set_logger($logger);

                return $pusher;
            }
        );

        $this->app->bind(
            'Push\Contracts\PushHandlerInterface',
            'Push\Models\PusherWrapper'
        );
    }

    public function provides()
    {
        return ['Pusher', 'Push\Contracts\PushHandlerInterface'];
    }

    /**
     * @return \Push\Contracts\PushHandlerInterface
     */
    public static function getPushHandler()
    {
        return \App::make('Push\Contracts\PushHandlerInterface');
    }

}
