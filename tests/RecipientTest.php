<?php

namespace Tests;

use Krve\Inmobile\Recipient;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    /** @test */
    public function it_can_set_the_msisdn()
    {
        $recipient = Recipient::create('foobar');

        $this->assertEquals('foobar', $recipient->getMsisdn());
    }

    /** @test */
    public function it_can_set_the_message_id()
    {
        $recipient = Recipient::create('foobar')->setMessageId('id-1');

        $this->assertEquals('id-1', $recipient->getMessageId());
    }
}
