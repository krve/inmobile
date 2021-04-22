<?php

namespace Tests;

use Krve\Inmobile\CurlClient;
use Krve\Inmobile\Exceptions\InmobileErrorException;
use Krve\Inmobile\InmobileMessageSender;
use Krve\Inmobile\Models\InmobileMessage;
use PHPUnit\Framework\TestCase;

class InmobileMessageSenderTest extends TestCase
{
    private \Mockery\LegacyMockInterface|CurlClient|\Mockery\MockInterface $mock;

    private InmobileMessageSender $sender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = \Mockery::mock(CurlClient::class);
        $this->sender = new InmobileMessageSender('api-token', 'SenderName', $this->mock);
    }

    /** @test */
    public function it_can_send_a_message()
    {
        $message = (new InmobileMessage())
            ->from('Foobar')
            ->setMessage('Hello World')
            ->to(1);

        $this->mock->shouldReceive('post')
            ->with(
                \Mockery::any(),
                \Mockery::on(function ($payload) {
                    return isset($payload['xml']) && str_contains($payload['xml'], '<request><authentication apikey="api-token"/><data><message><sendername><![CDATA[Foobar]]></sendername><text><![CDATA[Hello World]]></text><recipients><msisdn>1</msisdn></recipients></message></data></request>');
                })
            )
            ->once()
            ->andReturn('<reply><recipient msisdn="1" id="message-1" /></reply>');

        $response = $this->sender->send($message);

        $this->assertEquals([['msisdn' => 1, 'id' => 'message-1']], $response);
    }

    /** @test */
    public function it_uses_the_default_sender_name_if_none_is_specified_on_the_message()
    {
        $message = (new InmobileMessage())
            ->setMessage('Hello World')
            ->to(1);

        $this->mock->shouldReceive('post')
            ->with(
                \Mockery::any(),
                \Mockery::on(function ($payload) {
                    return isset($payload['xml']) && str_contains($payload['xml'], 'SenderName');
                })
            )
            ->once()
            ->andReturn('<reply><recipient msisdn="1" id="message-1" /></reply>');

        $response = $this->sender->send($message);

        $this->assertEquals([['msisdn' => 1, 'id' => 'message-1']], $response);
    }

    /** @test */
    public function it_can_send_a_message_with_extra_parameters()
    {
        $message = (new InmobileMessage())
            ->from('Foobar')
            ->setMessage('Hello World')
            ->setSendTime('2012-01-01 12:00:00')
            ->setEncoding(InmobileMessage::ENCODING_UTF8)
            ->setExpireInSeconds(10)
            ->flash()
            ->to(1);

        $this->mock->shouldReceive('post')
            ->with(
                \Mockery::any(),
                \Mockery::on(function ($payload) {
                    return isset($payload['xml']) && str_contains($payload['xml'], '<request><authentication apikey="api-token"/><data><message><sendername><![CDATA[Foobar]]></sendername><text encoding="utf-8" flash="true"><![CDATA[Hello World]]></text><sendtime>2012-01-01 12:00:00</sendtime><expiretimeinseconds>10</expiretimeinseconds><recipients><msisdn>1</msisdn></recipients></message></data></request>');
                })
            )
            ->once()
            ->andReturn('<reply><recipient msisdn="1" id="message-1" /></reply>');

        $response = $this->sender->send($message);

        $this->assertEquals([['msisdn' => 1, 'id' => 'message-1']], $response);
    }

    /** @test */
    public function it_throws_an_error_when_recieving_an_error_code()
    {
        $message = (new InmobileMessage())
            ->from('Foobar')
            ->setMessage('Hello World')
            ->to(1);

        $this->mock->shouldReceive('post')
            ->with(
                \Mockery::any(),
                \Mockery::on(function ($payload) {
                    return isset($payload['xml']) && str_contains($payload['xml'], '<request><authentication apikey="api-token"/><data><message><sendername><![CDATA[Foobar]]></sendername><text><![CDATA[Hello World]]></text><recipients><msisdn>1</msisdn></recipients></message></data></request>');
                })
            )
            ->once()
            ->andReturn('-1001');

        try {
            $this->sender->send($message);
        } catch (InmobileErrorException $exception) {
            $this->assertEquals(-1001, $exception->getInmobileErrorCode());

            return;
        }

        $this->fail('No exception was thrown.');
    }
}
