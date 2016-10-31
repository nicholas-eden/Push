<?php

namespace Push\Contracts;


/**
 * Channel events which have a user id attached
 *
 * Interface PresenceEventInterface
 * @package Push
 * @see     https://pusher.com/docs/webhooks#member_added
 */
interface PresenceEventInterface extends ChannelEventInterface
{


    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId);

    /**
     * @return int
     */
    public function getUserId();


}