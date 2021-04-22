<?php

namespace Krve\Inmobile;

use DOMDocument;
use DOMElement;
use Krve\Inmobile\Exceptions\InvalidTextEncodingException;

class Text
{
    public const ENCODING_GSM7 = 'gsm7';
    public const ENCODING_UTF8 = 'utf-8';

    protected string $content;
    protected bool $flash = false;
    protected string $encoding = self::ENCODING_GSM7;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function create(string $content): Text
    {
        return new self($content);
    }

    public function flash(): Text
    {
        $this->flash = true;

        return $this;
    }

    public function encoding(string $encoding): Text
    {
        if(!in_array($encoding, [self::ENCODING_GSM7, self::ENCODING_UTF8])) {
            throw new InvalidTextEncodingException;
        }

        $this->encoding = $encoding;

        return $this;
    }

    public function toXmlElement(DOMDocument $dom): DOMElement
    {
        $element = $dom->createElement('text');

        $element->appendChild($dom->createCDATASection($this->content));
        $element->setAttribute('encoding', $this->encoding);
        $element->setAttribute('flash', $this->flash ? 'true' : 'false');

        return $element;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isFlashed(): bool
    {
        return $this->flash;
    }

    public function getEncoding(): string
    {
        return $this->encoding;
    }
}
