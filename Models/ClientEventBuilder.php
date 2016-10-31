<?php

namespace Push\Models;


use Push\Contracts\ClientEventInterface;

/**
 * Class ClientEventBuilder
 * @package Push
 * @see https://pusher.com/docs/webhooks#client_events
 */
class ClientEventBuilder
{

    /**
     * @param string $eventName
     * @return ClientEventInterface
     * @throws NotFoundException
     */
    public static function buildEventFromName($eventName)
    {
        //todo

        throw new NotFoundException('Event not found from event name: ' . $eventName);
    }

}