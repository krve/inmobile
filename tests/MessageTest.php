<?php

namespace Tests;

use Krve\Inmobile\Message;
use Krve\Inmobile\Recipient;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function it_can_create_a_message()
    {
        $message = Message::create('Hello World');

        $this->assertEquals('Hello World', $message->getText()->getContent());
    }

    /** @test */
    public function it_can_set_the_sender()
    {
        $message = Message::create('Hello World')->from('Foobar');

        $this->assertEquals('Foobar', $message->getSender());
    }

    /** @test */
    public function it_can_set_the_send_time()
    {
        $date = date_create();

        $message = Message::create('Hello World')->sendAt($date);

        $this->assertEquals($date, $message->getSendTime());
    }

    /** @test */
    public function it_can_set_the_expire_time()
    {
        $message = Message::create('Hello World')->expireIn(100);

        $this->assertEquals(100, $message->getExpireInSeconds());
    }

    /** @test */
    public function it_can_add_a_recipient()
    {
        $message = Message::create('Hello World')->to('100');

        $this->assertEquals('100', $message->getRecipients()[0]->getMsisdn());
    }

    /** @test */
    public function it_can_add_multiple_recipients()
    {
        $message = Message::create('Hello World')->to(['100', '101']);

        $this->assertEquals('100', $message->getRecipients()[0]->getMsisdn());
        $this->assertEquals('101', $message->getRecipients()[1]->getMsisdn());
    }

    /** @test */
    public function it_can_add_a_recipient_using_the_class()
    {
        $message = Message::create('Hello World')->to(
            Recipient::create('100')->setMessageId('id-1')
        );

        $this->assertEquals('100', $message->getRecipients()[0]->getMsisdn());
        $this->assertEquals('id-1', $message->getRecipients()[0]->getMessageId());
    }

    /** @test */
    public function it_can_add_multiple_recipients_using_the_class()
    {
        $message = Message::create('Hello World')->to([
            Recipient::create('100')->setMessageId('id-1'),
            Recipient::create('101')->setMessageId('id-2')
        ]);

        $this->assertEquals('100', $message->getRecipients()[0]->getMsisdn());
        $this->assertEquals('id-1', $message->getRecipients()[0]->getMessageId());
        $this->assertEquals('101', $message->getRecipients()[1]->getMsisdn());
        $this->assertEquals('id-2', $message->getRecipients()[1]->getMessageId());
    }
}
