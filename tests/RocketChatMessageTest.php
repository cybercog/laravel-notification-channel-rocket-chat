<?php

namespace NotificationChannels\RocketChat\Test;

use NotificationChannels\RocketChat\RocketChatMessage;

class RocketChatMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new RocketChatMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_accept_a_content_when_creating_a_message()
    {
        $message = RocketChatMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $message = (new RocketChatMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_room()
    {
        $message = (new RocketChatMessage())->to('room');

        $this->assertEquals('room', $message->room);
    }

    /** @test */
    public function it_can_set_the_from()
    {
        $message = (new RocketChatMessage())->from('token');

        $this->assertEquals('token', $message->from);
    }
}
