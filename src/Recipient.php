<?php

namespace Krve\Inmobile;

use DOMDocument;
use DOMElement;

class Recipient
{
    protected string $msisdn;
    protected ?string $messageId = null;

    public function __construct(string $msisdn)
    {
        $this->msisdn = $msisdn;
    }

    public static function create(string $msisdn): Recipient
    {
        return new self($msisdn);
    }
    
    public function setMessageId(string $messageId): Recipient
    {
        $this->messageId = $messageId;
        
        return $this;
    }

    public function toXmlElement(DOMDocument $dom) : DOMElement
    {
        $element = $dom->createElement('msisdn', $this->msisdn);

        if ($this->messageId) {
            $element->setAttribute('id', $this->messageId);
        }

        return $element;
    }

    public function getMsisdn(): string
    {
        return $this->msisdn;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }
}
