<?php

namespace NotificationChannels\RocketChat\Test;

use Illuminate\Support\Facades\Config;
use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use NotificationChannels\RocketChat\RocketChat;
use NotificationChannels\RocketChat\RocketChatMessage;
use NotificationChannels\RocketChat\RocketChatWebhookChannel;
use NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification;
use PHPUnit\Framework\TestCase;

class RocketChatWebhookChannelTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /** @test */
    public function it_can_send_a_notification()
    {
        $client = Mockery::mock(Client::class);

        $apiBaseUrl = 'http://localhost:3000';
        $token = ':token';
        $room = ':room';

        $client->shouldReceive('post')->once()
            ->with(
                "{$apiBaseUrl}/hooks/{$token}",
                [
                    'json' => [
                        'text' => 'hello',
                        'roomId' => $room,
                    ],
                ]
            )->andReturn(new Response(200));

        $rocketChat = new RocketChat($client, $apiBaseUrl, $token, $room);
        $channel = new RocketChatWebhookChannel($rocketChat);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_does_not_send_a_message_when_room_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $rocketChat = new RocketChat(new Client(), '', '', '');
        $channel = new RocketChatWebhookChannel($rocketChat);
        $channel->send(new TestNotifiable(), new TestNotificationWithMissedRoom());
    }

    /** @test */
    public function it_does_not_send_a_message_when_from_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $rocketChat = new RocketChat(new Client(), '', '', '');
        $channel = new RocketChatWebhookChannel($rocketChat);
        $channel->send(new TestNotifiable(), new TestNotificationWithMissedFrom());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForRocketChat()
    {
        return '';
    }
}

class TestNotification extends Notification
{
    public function toRocketChat()
    {
        return RocketChatMessage::create('hello')->from(':token')->to(':room');
    }
}

class TestNotificationWithMissedRoom extends Notification
{
    public function toRocketChat()
    {
        return RocketChatMessage::create('hello')->from(':token');
    }
}

class TestNotificationWithMissedFrom extends Notification
{
    public function toRocketChat()
    {
        return RocketChatMessage::create('hello')->to(':room');
    }
}
