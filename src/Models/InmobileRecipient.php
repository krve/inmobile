<?php

namespace Krve\Inmobile\Models;

class InmobileRecipient
{
    private string $msisdn;
    private ?string $id;

    public function __construct(string $msisdn, ?string $id = null)
    {
        $this->msisdn = $msisdn;
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMsisdn(): string
    {
        return $this->msisdn;
    }
}
