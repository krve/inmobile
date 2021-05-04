<?php

namespace Tests;

use Krve\Inmobile\CurlClient;
use Krve\Inmobile\Exceptions\GatewayErrorException;
use Krve\Inmobile\Gateway;
use Krve\Inmobile\Response;
use Krve\Inmobile\Message;
use Krve\Inmobile\Recipient;
use Mockery;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    protected function validResponse(): string
    {
        return <<<XML
        <reply>
            <recipient msisdn="4500000000" id="id-1"></recipient>
            <recipient msisdn="4500000000" id="13cab0f4-0e4f-44cf-8f84-a9eb435f36a4"></recipient>
        </reply>
        XML;
    }

    /** @test */
    public function it_can_convert_a_message_to_xml()
    {
        $client = Mockery::mock(CurlClient::class);
        $gateway = new Gateway('foobar', $client);

        $client->shouldReceive('post')
            ->with(
                Mockery::any(),
                Mockery::on(function ($payload) {
                    $xml = $payload['xml'];

                    $this->assertEquals(
                        <<<XML
                        <request><authentication apikey="foobar"/><data><message><sendername/><text encoding="gsm7" flash="false"><![CDATA[Hello World]]></text><recipients><msisdn id="id-1">4500000000</msisdn><msisdn>4500000000</msisdn></recipients></message></data></request>
                        XML,
                        $xml
                    );

                    return true;
                })
            )
            ->once()
            ->andReturn($this->validResponse());

        $gateway->send(
            Message::create('Hello World')
                ->to([
                    Recipient::create(4500000000)->setMessageId('id-1'),
                    '4500000000'
                ])
        );
    }

    /** @test */
    public function it_throws_an_expection_when_the_api_returns_an_error()
    {
        $client = Mockery::mock(CurlClient::class);
        $gateway = new Gateway('foobar', $client);

        $this->expectException(GatewayErrorException::class);

        $client->shouldReceive('post')
            ->once()
            ->andReturn('-1001');

        $gateway->send(
            Message::create('Hello World')
                ->to([
                    Recipient::create(4500000000)->setMessageId('id-1'),
                    '4500000000'
                ])
        );
    }

    /** @test */
    public function it_can_set_the_callback_url()
    {
        $client = Mockery::mock(CurlClient::class);
        $gateway = new Gateway('foobar', $client);

        $client->shouldReceive('post')
            ->with(
                Mockery::any(),
                Mockery::on(function ($payload) {
                    $xml = $payload['xml'];

                    $this->assertEquals(
                        <<<XML
                        <request><authentication apikey="foobar"/><data><message><sendername/><text encoding="gsm7" flash="false"><![CDATA[Hello World]]></text><recipients><msisdn>4500000000</msisdn></recipients></message><statuscallbackurl>https://example.com/callback</statuscallbackurl></data></request>
                        XML,
                        $xml
                    );

                    return true;
                })
            )
            ->once()
            ->andReturn($this->validResponse());

        $gateway->send(
            Message::create('Hello World')
                ->to('4500000000'),
            'https://example.com/callback'
        );
    }

    public function test_returns_instance_of_response()
    {
        $client = Mockery::mock(CurlClient::class);
        $gateway = new Gateway('foobar', $client);

        $client->shouldReceive('post')
            ->once()
            ->andReturn($this->validResponse());

        $response = $gateway->send(Message::create('Hello World')->to('4500000000'));

        $this->assertInstanceOf(Response::class, $response);
    }
}
