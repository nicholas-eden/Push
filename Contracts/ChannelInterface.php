<?php

namespace Push\Contracts;


/**
 * An event channel which can be subscribed to by clients and handles events
 * Client and channel events are handled by the handleEvent method
 *
 * Interface ChannelInterface
 * @package Push
 */
interface ChannelInterface
{

    /**
     * @param PushHandlerInterface $handler
     */
    public function __construct(PushHandlerInterface $handler);

    /**
     * @return bool
     */
    public function requiresAuthentication();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param EventInterface $event
     * @return bool
     */
    public function send(EventInterface $event);

    /**
     * @param \User $user
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function authenticate(\User $user);

    /**
     * @param ChannelEventInterface $event
     * @return bool
     */
    public function handleEvent(ChannelEventInterface $event);

}
