<?php

namespace Tests;

use Krve\Inmobile\Exceptions\GatewayErrorException;
use PHPUnit\Framework\TestCase;

class GatewayErrorExceptionTest extends TestCase
{
    public function test_can_create_from_status_code()
    {
        $exception = GatewayErrorException::fromCode(-11);

        $this->assertStringContainsString(
            GatewayErrorException::ERROR_CODES[-11], $exception->getMessage()
        );
        $this->assertEquals(-11, $exception->getInmobileErrorCode());
    }

    public function test_uses_a_generic_error_message_when_the_error_code_was_not_found()
    {
        $exception = GatewayErrorException::fromCode(-1000);

        $this->assertStringContainsString('-1000', $exception->getMessage());
        $this->assertEquals(-1000, $exception->getInmobileErrorCode());
    }
}
