<?php

namespace Krve\Inmobile\Exceptions;

use Exception;
use Throwable;

class InmobileErrorException extends Exception
{
    protected string|int $inmobileErrorCode;

    public function __construct($message, $inmobileErrorCode)
    {
        parent::__construct($message, 0);

        $this->inmobileErrorCode = $inmobileErrorCode;
    }

    public function getInmobileErrorCode(): string|int
    {
        return $this->inmobileErrorCode;
    }

}
