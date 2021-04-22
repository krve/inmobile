<?php

namespace Krve\Inmobile\Models;

class InmobileMessage
{
    public const ENCODING_GSM7 = 'gsm7';
    public const ENCODING_UTF8 = 'utf-8';

    private string $message;
    private string $sender = '';
    private array $recipients = [];
    private string $sendTime = '';
    private ?int $expireInSeconds = null;
    private bool $flash = false;
    private string $encoding = '';

    public function setMessage(string $message): InmobileMessage
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function from(string $sender): InmobileMessage
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function to(string $recipient, ?string $id = null): InmobileMessage
    {
        $this->recipients[] = new InmobileRecipient($recipient, $id);

        return $this;
    }

    /**
     * @return InmobileRecipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSendTime(): string
    {
        return $this->sendTime;
    }

    public function setSendTime(string $sendTime): InmobileMessage
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    public function setExpireInSeconds(?int $expireInSeconds): InmobileMessage
    {
        $this->expireInSeconds = $expireInSeconds;

        return $this;
    }

    public function flash(bool $flash = true): InmobileMessage
    {
        $this->flash = $flash;

        return $this;
    }

    public function shouldFlash(): bool
    {
        return $this->flash;
    }

    public function getExpireInSeconds(): ?int
    {
        return $this->expireInSeconds;
    }

    public function setEncoding(string $encoding): InmobileMessage
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function getEncoding(): string
    {
        return $this->encoding;
    }
}
