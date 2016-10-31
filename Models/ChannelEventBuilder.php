<?php

namespace Push\Models;


use Push\Contracts\ChannelEventInterface;
use Push\Models\Events\ChannelOccupiedEvent;
use Push\Models\Events\ChannelVacatedEvent;
use Push\Models\Events\MemberAddedEvent;
use Push\Models\Events\MemberRemovedEvent;

/**
 * Used to build channel events received from the handler
 *
 * Class ChannelEventBuilder
 * @package Push
 * @see https://pusher.com/docs/webhooks#channel-existence
 */
class ChannelEventBuilder
{

    /**
     * @param string $eventName
     * @return ChannelEventInterface
     * @throws NotFoundException
     */
    public static function buildEventFromName($eventName)
    {

        if ($eventName === ChannelOccupiedEvent::NAME) {
            return new ChannelOccupiedEvent();
        }

        if ($eventName === ChannelVacatedEvent::NAME) {
            return new ChannelVacatedEvent();
        }

        if ($eventName === MemberAddedEvent::NAME) {
            return new MemberAddedEvent();
        }

        if ($eventName === MemberRemovedEvent::NAME) {
            return new MemberRemovedEvent();
        }

        throw new NotFoundException('Event not found from event name: ' . $eventName);
    }

}