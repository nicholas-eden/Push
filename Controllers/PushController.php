<?php

namespace Push\Controllers;


use Auth;
use Push\Models\ChannelBuilder;
use Push\Support\PushServiceProvider;
use Response;

/**
 * Class PushController
 * @package Push
 */
class PushController extends \BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('oauth_login:global', array('only' => array('channelAuthentication')));
        $this->beforeFilter('oauth_client', array('except' => array('channelAuthentication')));

    }

    /**
     * Authenticates a user for a channel
     * @return \Illuminate\Http\JsonResponse
     * @see https://pusher.com/docs/authenticating_users#/lang=wordpress
     */
    public function channelAuthentication()
    {
        $handler = PushServiceProvider::getPushHandler();
        $request = $this->getRequest();
        \Log::info('[PushController] Authenticating request',[
            'request'   => (string)$request
        ]);

        $rawChannelName = $handler->getChannelNameFromRequest($request,true);
        $channelName = $handler->getChannelNameFromRequest($request);
        $socketId = $handler->getSocketIdFromRequest($request);

        $channel = ChannelBuilder::buildChannelFromName($channelName);
        $channel->authenticate(Auth::user());

        //todo: any way to abstract this?
        /** @var \Pusher $pusher */
        $pusher = $handler->getInstance();
        $formattedResponse = $pusher->presence_auth($rawChannelName, $socketId, Auth::user()->getAuthIdentifier());
        //decoding so we can use the json response which drops in proper headers
        $arrayResponse = json_decode($formattedResponse);

        \Log::info('[Pusher] Gave client access to channel', [
            'channelName' => $rawChannelName,
            'socketId'    => $socketId,
            'response'    => $formattedResponse
        ]);

        return Response::json(
            $arrayResponse
        );
    }

    /**
     * Hit when there is a channel event
     * ex: member added, member removed, channel occupied, channel vacated
     *
     * @return \Illuminate\Http\JsonResponse
     * @see https://pusher.com/docs/webhooks#webhook-format
     */
    public function channelEvent()
    {
        $request = $this->getRequest();
        $handler = PushServiceProvider::getPushHandler();
        $handler->authenticateRequest($request);

        $events = $handler->getEventsFromRequest($request);
        foreach ($events as $eventReceived) {
            $channel = $eventReceived->getChannel();
            $channel->handleEvent($eventReceived);
        }

        return Response::json();
    }

    /**
     * Hit when there is an event broadcast by the client
     *
     * @return \Illuminate\Http\JsonResponse
     * @see https://pusher.com/docs/webhooks#webhook-format
     */
    public function clientEvent()
    {
        $request = $this->getRequest();
        $handler = PushServiceProvider::getPushHandler();
        $handler->authenticateRequest($request);

        $events = $handler->getEventsFromRequest($request);
        foreach ($events as $eventReceived) {
            $channel = $eventReceived->getChannel();
            $channel->handleEvent($eventReceived);
        }

        return Response::json();
    }

}
