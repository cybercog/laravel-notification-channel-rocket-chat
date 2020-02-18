<?php

namespace NotificationChannels\RocketChat;

class RocketChatMessage
{
    /**
     * RocketChat room id.
     *
     * @var string
     */
    public $room = '';

    /**
     * A user or app access token.
     *
     * @var string
     */
    public $from = '';

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content = '';

    /**
     * Create a new instance of RocketChatMessage.
     *
     * @param  string  $content
     * @return static
     */
    public static function create($content = '') : self
    {
        return new static($content);
    }

    /**
     * Create a new instance of RocketChatMessage.
     *
     * @param $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
    }

    /**
     * Set the sender's access token.
     *
     * @param  string  $accessToken
     * @return $this
     */
    public function from($accessToken) : self
    {
        $this->from = $accessToken;

        return $this;
    }

    /**
     * Set the RocketChat room the message should be sent to.
     *
     * @param  string $room
     * @return $this
     */
    public function to($room) : self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Set the content of the RocketChat message. Supports GitHub flavoured markdown.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content) : self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get an array representation of the RocketChatMessage.
     *
     * @return array
     */
    public function toArray() : array
    {
        $message = array_filter([
            'text' => $this->content,
            'roomId' => $this->room,
        ]);

        return $message;
    }
}
