<?php

namespace Push\Models;

/**
 * Pusher allows a logger to be set, but just passing it monolog won't work
 * It calls a log method, passing it a string as the first parameter, without any interface
 * Class PusherLogWrapper
 * @package Push
 */
class PusherLogWrapper
{

    /**
     * @param string $msg
     */
    public function log($msg)
    {
        $msg = (string)$msg;
        \Log::info('[Pusher] ' . $msg);
    }

}