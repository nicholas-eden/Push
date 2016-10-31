<?php

namespace Push\Models\Events;


use Push\Contracts\ChannelInterface;
use Push\Contracts\PresenceEventInterface;

/**
 * Received when someone unsubscribes from a channel, includes human id
 *
 * Class MemberRemovedEvent
 * @package Push
 * @see https://pusher.com/docs/webhooks#member_removed
 */
class MemberRemovedEvent implements PresenceEventInterface
{

    const NAME = 'member_removed';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @var int
     */
    protected $userId;


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


    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }


}