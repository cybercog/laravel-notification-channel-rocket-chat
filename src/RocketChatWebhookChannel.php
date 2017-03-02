<?php

namespace NotificationChannels\RocketChat;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\RocketChat\Events\MessageWasSent;
use NotificationChannels\RocketChat\Events\SendingMessage;
use NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification;

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
     *
     * @throws \NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
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
     * @param string $to
     * @param \NotificationChannels\RocketChat\RocketChatMessage $message
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \NotificationChannels\RocketChat\Exceptions\CouldNotSendNotification
     */
    protected function sendMessage($to, RocketChatMessage $message)
    {
        return $this->rocketChat->sendMessage($to, $message->toArray());
    }
}
