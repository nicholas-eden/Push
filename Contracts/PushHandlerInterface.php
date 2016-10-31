<?php

namespace Push\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * A event service which handles the subscribing to channels and delivery of events.
 * This could be an internal implementation using Socket.io or an external service like Pusher
 *
 * Interface PushHandlerInterface
 * @package Push
 */
interface PushHandlerInterface
{

    /**
     * Get the wrapped instance if applicable
     * @return mixed
     */
    public function getInstance();

    /**
     * @param string $channel
     * @param string $event
     * @param array  $data
     * @param bool   $requiresAuthentication
     * @return bool
     */
    public function publish($channel, $event, array $data, $requiresAuthentication = false);

    /**
     * @param Request $request
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function authenticateRequest(Request $request);

    /**
     * @param Request $request
     * @param bool    $raw
     * @return string
     */
    public function getChannelNameFromRequest(Request $request, $raw = false);

    /**
     * @param Request $request
     * @return ChannelEventInterface[]
     */
    public function getEventsFromRequest(Request $request);

    /**
     * @param Request $request
     * @return string
     */
    public function getSocketIdFromRequest(Request $request);
}