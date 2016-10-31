<?php

namespace Push;

/**
 * Class Routes
 * @package Push
 */
class Routes extends RouteGroupAbstract
{

    public static function create(array $options)
    {


        self::group($options, function () {

            self::post('auth',
                '\Push\Controllers\PushController@channelAuthentication');
            self::post('event/channel',
                '\Push\Controllers\PushController@channelEvent');
            self::post('event/client', '\Push\Controllers\PushController@clientEvent');

        });

    }

}

