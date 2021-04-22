<?php

namespace Tests;

use Krve\Inmobile\Models\InmobileMessage;
use PHPUnit\Framework\TestCase;

class InmobileMessageTest extends TestCase
{
    /** @test */
    public function it_can_set_the_message()
    {
        $message = (new InmobileMessage())
            ->setMessage('Hello World');

        $this->assertEquals('Hello World', $message->getMessage());
    }

    /** @test */
    public function it_can_set_the_sender()
    {
        $message = (new InmobileMessage())
            ->from('FOOBAR');

        $this->assertEquals('FOOBAR', $message->getSender());
    }

    /** @test */
    public function it_can_set_the_recipient()
    {
        $message = (new InmobileMessage())
            ->to('00000000');

        $recipient = $message->getRecipients()[0];
        $this->assertEquals('00000000', $recipient->getMsisdn());
    }

    /** @test */
    public function it_can_set_the_send_time()
    {
        $message = (new InmobileMessage())
            ->setSendTime('2012-01-01 12:00:00');

        $this->assertEquals('2012-01-01 12:00:00', $message->getSendTime());
    }

    /** @test */
    public function it_can_set_if_the_message_should_flash()
    {
        $message = (new InmobileMessage())
            ->flash();

        $this->assertTrue($message->shouldFlash());
    }

    /** @test */
    public function it_can_set_the_expire_time_in_seconds()
    {
        $message = (new InmobileMessage())
            ->setExpireInSeconds(10);

        $this->assertEquals(10, $message->getExpireInSeconds());
    }

    /** @test */
    public function it_can_set_the_encoding()
    {
        $message = (new InmobileMessage())
            ->setEncoding(InmobileMessage::ENCODING_UTF8);

        $this->assertEquals(InmobileMessage::ENCODING_UTF8, $message->getEncoding());
    }

    /** @test */
    public function it_can_set_the_message_id_for_each_recipient()
    {
        $message = (new InmobileMessage())
            ->to('00000000', 'my-id');

        $recipient = $message->getRecipients()[0];
        $this->assertEquals('00000000', $recipient->getMsisdn());
        $this->assertEquals('my-id', $recipient->getId());
    }
}
