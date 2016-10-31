<?php

namespace Push\Models;


use Push\Contracts\ChannelInterface;

/**
 * Used to build a channel for an event received from the handler
 *
 *
 * Class ChannelBuilder
 * @package Push
 */
class ChannelBuilder
{
    /**
     * @param string $channelName
     * @return ChannelInterface
     * @throws NotFoundException
     */
    public static function buildChannelFromName($channelName)
    {
        //todo

        throw new NotFoundException('Channel not found by name: ' . $channelName);
    }
}