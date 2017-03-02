<?php

namespace NotificationChannels\RocketChat\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when room identifier is missing.
     *
     * @return static
     */
    public static function missingTo()
    {
        return new static('Notification was not sent. Room identifier is missing.');
    }

    /**
     * Thrown when user or app access token is missing.
     *
     * @return static
     */
    public static function missingFrom()
    {
        return new static('Notification was not sent. Access token is missing.');
    }

    /**
     * Thrown when there's a bad response from the RocketChat.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function rocketChatRespondedWithAnError(ClientException $exception)
    {
        $message = $exception->getResponse()->getBody();
        $code = $exception->getResponse()->getStatusCode();

        return new static("RocketChat responded with an error `{$code} - {$message}`");
    }

    /**
     * Thrown when we're unable to communicate with RocketChat.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithRocketChat(Exception $exception)
    {
        return new static("The communication with RocketChat failed. Reason: {$exception->getMessage()}");
    }
}
