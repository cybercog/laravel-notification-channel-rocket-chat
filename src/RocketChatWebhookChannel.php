<?php

namespace NotificationChannels\RocketChat;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class RocketChatWebhookChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \NotificationChannels\RocketChat\RocketChat
     */
    protected $rocketChat;

    /**
     * Create a new RocketChat channel instance.
     *
     * @param  \NotificationChannels\RocketChat\RocketChat $rocketChat
     * @return void
     */
    public function __construct(RocketChat $rocketChat)
    {
        $this->rocketChat = $rocketChat;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     *
     * @throws \NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification) : void
    {
        /** @var \NotificationChannels\RocketChat\RocketChatMessage $message */
        $message = $notification->toRocketChat($notifiable);

        $to = $message->room ?: $notifiable->routeNotificationFor('RocketChat');
        if (!$to = $to ?: $this->rocketChat->room()) {
            throw CouldNotSendNotification::missingTo();
        }

        if (!$from = $message->from ?: $this->rocketChat->token()) {
            throw CouldNotSendNotification::missingFrom();
        }

        try {
            $this->sendMessage($to, $message);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::rocketChatRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithRocketChat($exception);
        }
    }

    /**
     * @param  string  $to
     * @param  \NotificationChannels\RocketChat\RocketChatMessage  $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendMessage($to, RocketChatMessage $message) : ResponseInterface
    {
        return $this->rocketChat->sendMessage($to, $message->toArray());
    }
}
