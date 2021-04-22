<?php

namespace Krve\Inmobile;

use DateTimeInterface;
use DOMDocument;

class Message
{
    protected Text $text;
    protected string $sender = '';
    protected array $recipients = [];
    protected ?DateTimeInterface $sendTime = null;
    protected ?int $expireInSeconds = null;

    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    public static function create(Text|string $text): Message
    {
        $text = $text instanceof Text ? $text : Text::create($text);

        return new self($text);
    }

    public function text(Text|string $text): Message
    {
        $this->text = $text instanceof Text ? $text : Text::create($text);

        return $this;
    }

    public function from(string $sender): Message
    {
        $this->sender = $sender;

        return $this;
    }

    public function to($recipients): Message
    {
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        $this->recipients = array_map(function ($recipient) {
            return $recipient instanceof Recipient ? $recipient : new Recipient($recipient);
        }, $recipients);

        return $this;
    }

    public function sendAt(DateTimeInterface $sendTime): Message
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    public function expireIn(?int $seconds): Message
    {
        $this->expireInSeconds = $seconds;

        return $this;
    }

    public function toXmlElement(DOMDocument $dom)
    {
        $element = $dom->createElement('message');

        $element->appendChild($dom->createElement('sendername', $this->sender));
        $element->appendChild($this->text->toXmlElement($dom));

        $recipients = $dom->createElement('recipients');

        foreach($this->recipients as $recipient) {
            $recipients->appendChild($recipient->toXmlElement($dom));
        }

        if ($this->sendTime) {
            $sendTime = $dom->createElement('sendtime', $this->sendTime->format('Y-m-d H:i:s'));

            $element->appendChild($sendTime);
        }

        if ($this->expireInSeconds) {
            $sendTime = $dom->createElement('expireinseconds', $this->expireInSeconds);

            $element->appendChild($sendTime);
        }

        $element->appendChild($recipients);

        return $element;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @return \Krve\Inmobile\Recipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSendTime(): DateTimeInterface
    {
        return $this->sendTime;
    }

    public function getExpireInSeconds(): ?int
    {
        return $this->expireInSeconds;
    }
}
