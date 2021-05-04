<?php

namespace Tests;

use Krve\Inmobile\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
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

    public function test_can_convert_content_to_array()
    {
        $response = new Response($this->validResponse(), status: 200);

        $this->assertIsArray($response->toArray());
        $this->assertEquals([
            [
                'msisdn' => '4500000000',
                'id' => 'id-1',
            ],
            [
                'msisdn' => '4500000000',
                'id' => '13cab0f4-0e4f-44cf-8f84-a9eb435f36a4',
            ]
        ], $response->toArray());
    }

    /**
     * @dataProvider statusProvider
     * @test
     */
    public function test_validates_if_response_was_successful($status, $expected)
    {
        $response = new Response($this->validResponse(), status: $status);

        $this->assertEquals($expected, $response->isOk());
    }

    public function statusProvider(): array
    {
        return [
            [200, true],
            [300, false],
            [400, false],
            [500, false],
            [201, true],
        ];
    }
}
