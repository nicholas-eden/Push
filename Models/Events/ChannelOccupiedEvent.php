<?php

namespace Push\Models\Events;


use Push\Contracts\ChannelEventInterface;
use Push\Contracts\ChannelInterface;

/**
 * Received when a channel goes from 0 to 1 subscribers
 *
 * Class ChannelOccupiedEvent
 * @package Push
 * @see https://pusher.com/docs/webhooks#channel_occupied
 */
class ChannelOccupiedEvent implements ChannelEventInterface
{

    const NAME = 'channel_occupied';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @param ChannelInterface $channel
     * @return $this
     */
    public function setChannel(ChannelInterface $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}