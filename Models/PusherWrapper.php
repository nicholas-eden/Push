<?php

namespace Push\Models;

use Push\Contracts\ChannelEventInterface;
use Push\Contracts\PresenceEventInterface;
use Push\Contracts\PushHandlerInterface;
use Illuminate\Http\Request;
use Pusher;

/**
 * Wraps the Pusher instance for conform to the PushHandlerInterface
 *
 * Has all logic specific to Pusher
 *
 * Class PusherWrapper
 * @package Push
 */
class PusherWrapper implements PushHandlerInterface
{

    /**
     * @var Pusher
     */
    protected $pusherInstance;

    /**
     * @param Pusher $pusher
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusherInstance = $pusher;
    }

    /**
     * @return Pusher
     */
    public function getInstance()
    {
        return $this->pusherInstance;
    }

    /**
     * @param string $channel
     * @param string $event
     * @param array  $data
     * @param bool   $requiresAuthentication
     * @return bool
     */
    public function publish($channel, $event, array $data, $requiresAuthentication = false)
    {
        //todo: this is sloppy...
        if ($requiresAuthentication) {
            $channel = 'presence-' . $channel;
        }

        try {
            return $this->getInstance()->trigger([$channel], $event, $data);
        } catch (\PusherException $e) {
            \Log::error('[Pusher] Exception caught when sending event',[
                'channel'   => $channel,
                'event'     => $event,
                'data'      => $data,
                'exception' => (string)$e
            ]);

            return false;
        }
    }

    /**
     * @param Request $request
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function authenticateRequest(Request $request)
    {
        $settings = $this->getInstance()->getSettings();
        $secret = $settings['secret'];
        $givenKey = $request->header('HTTP_X_PUSHER_KEY');
        $givenSignature = $request->header('HTTP_X_PUSHER_SIGNATURE');
        $body = $request->getContent();

        $expectedSignature = hash_hmac('sha256', $body, $secret, false);

        if ($expectedSignature === $givenSignature) {
            return true;
        }

        \Log::warning('[Pusher] Failed to authenticate request', [
            'request' => (string)$request
        ]);
        throw new UnauthorizedHttpException('Failed to authenticate pusher request');
    }

    /**
     * Pusher uses prefixes to indicate a channel type or event type
     * Remove those so we don't need to put that on the channel itself
     * @param string $name
     * @return string
     */
    protected function stripModifiers($name)
    {
        $modifiers = [
            'private-',
            'presence-',
            'client-'
        ];

        foreach ($modifiers as $prefix) {
            if (strpos($name, $prefix) === 0) {
                $length = strlen($prefix);

                return substr($name, $length);
            }
        }

        return $name;
    }

    /**
     * @param Request $request
     * @param bool    $raw
     * @return string
     */
    public function getChannelNameFromRequest(Request $request, $raw = false)
    {
        $channelName = $request->get('channel_name');

        if ($raw) {
            return $channelName;
        }

        return $this->stripModifiers($channelName);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getSocketIdFromRequest(Request $request)
    {
        return $request->get('socket_id');
    }

    /**
     * @param Request $request
     * @return ChannelEventInterface[]
     * @see https://pusher.com/docs/webhooks
     */
    public function getEventsFromRequest(Request $request)
    {
        $jsonContents = $request->getContent();
        $contents = json_decode($jsonContents, true);

        \Log::debug('[Pusher] Building events from request', [
            'body' => $contents
        ]);

        $eventsToHandle = [];
        foreach ($contents['events'] as $event) {
            try {
                $eventType = $event['name'];

                if ($eventType == 'client_event') {
                    $eventName = $event['event'];
                    $eventName = $this->stripModifiers($eventName);

                    $eventInstance = ClientEventBuilder::buildEventFromName($eventName);

                    if (is_array($event['data'])) {
                        $eventInstance->setData($event['data']);
                    }

                    if (isset($event['user_id'])) {
                        $eventInstance->setUserId($event['user_id']);
                    }
                } else {
                    $eventInstance = ChannelEventBuilder::buildEventFromName($eventType);
                    $eventInstance->setData($event);

                    if ($eventInstance instanceof PresenceEventInterface) {
                        $eventInstance->setUserId($event['user_id']);
                    }
                }

                $channelName = $event['channel'];
                $channelName = $this->stripModifiers($channelName);
                $channelInstance = ChannelBuilder::buildChannelFromName($channelName);


                $eventInstance->setChannel($channelInstance);
                $eventsToHandle[] = $eventInstance;

                \Log::debug('[Pusher] Event instance created', [
                    'event' => $event
                ]);
            } catch (NotFoundException $e) {
                \Log::info('[Pusher] Event listener not found', [
                    'event' => $event
                ]);
                continue;
            }
        }

        return $eventsToHandle;
    }


}