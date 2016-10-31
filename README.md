# Push Notification Package

## Handler
This is the external service which distributes events from the server to clients and sends events to us.  The current handler is Pusher, but this could easily swapped out by implementing the PushHandlerInterface and updating PushServiceProvider to use a different class for the interface.

## Events
There are four types of events:

### Channel Event
Any event which occurs on the channel itself, these are triggered by the handler (Pusher).  The current events supported are ChannelVacated and ChannelOccupied.  ChannelVacated is sent when there are no subscribers left in the channel, ChannelOccupied happens when a channel was empty then gains a subscriber.

### Presence Event
These are events which occur relating to a subscriber, these will include a user id.  The current events are MemberAdded and MemberRemoved.

### Client Event
These are events broadcast by a client and thus should be treated as unsafe data, sanitize and validate any data used from these events.  Currently supporting StartConfirming and StopConfirming, which indicate when a user changes status to confirming or not confirming orders in Storefront.

### Server Events
These are events which we broadcast to a channel.  The frontend should listen for these events where appropriate.

Event names should be static, do not put an id or any other variable into them.  Any variables should be passed in the event's data.  The client and server should be able to bind to a specific event name.

When creating new events which can be received, add an entry into either ChannelEventBuilder or ClientEventBuilder.  Server events do not need entered since we do not expect to receive them.

Currently no validation is done on event data, none of the existing events are expecting data, just that the event occurred.  See the MerchantConfirmingChannel for basic event handling.


## Channels
There are three types of channels which Pusher supports: public, private, and presence.  Public channels can be subscribed to without authentication.  Both private and presence require authentication, but events on private channels do not include a user id, thus are not currently used.  Channels handle their own authentication, although the authentication endpoint will verify the user is logged in, thus the channel will be provided with a Human object.

Channels classes handle sending and processing received events.  Any logic for handling external events should be placed in the handleEvent method.

When creating a new channel, add an entry into the ChannelBuilder class which will allow the handler to instantiate an instance of the class when an event is received.  Also add a get method in the PushServiceProvider.  A binding isn't necessary as Laravel will detect that it requires an instance of PushHandlerInterface and will find that PusherWrapper is bound to it.

See PusherWrapper::getEventsFromRequest and PushController::clientEvent to see how an event goes from the request to the channel.

Channels should be used for filtering information, use prefixes to split events to different groups.  Ex: merchant_54321 and merchant_12345 both use the same channel class, but the client would need to subscribe to both to receive events about both.  Use the ChannelBuilder to determine the appropriate class then set the relevant identifier.

## Usage
Sending an event is easy.  First get an instance of the event you want to send, and set any data to tag onto the event:

```php
$event = PushServiceProvider::getOrderPlacedEvent();
$data = ['orderId' => 1234];
$event->setData($data);
```

Then get an instance of the channel you want to sent the event over, setting any applicable identifier, such as merchant id.  Finally, tell the channel to send the event:

```php
$channel = PushServiceProvider::getMerchantOrderChannel();
$channel->setMerchantId($order->getMerchantId());
$channel->send($event);
```