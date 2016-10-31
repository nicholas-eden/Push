<?php

namespace Push\Contracts;

/**
 * Events which happen directly on the channel, such as someone subscribes or un-subscribes
 *
 * Interface ChannelEventInterface
 * @package Push
 */
interface ChannelEventInterface
{

    /**
     * @param ChannelInterface $channel
     * @return $this
     */
    public function setChannel(ChannelInterface $channel);

    /**
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData();

}