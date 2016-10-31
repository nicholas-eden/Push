<?php

namespace Push\Contracts;


/**
 * Custom events sent by clients, to listen a class must implement this interface
 * then be added to the builder
 *
 * Interface ClientEventInterface
 * @package Push
 * @see https://pusher.com/docs/webhooks#client_events
 */
interface ClientEventInterface extends ChannelEventInterface
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