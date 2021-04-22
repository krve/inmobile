<?php

namespace Krve\Inmobile;

use Krve\Inmobile\Exceptions\InmobileErrorException;
use Krve\Inmobile\Models\InmobileMessage;

class InmobileMessageSender
{
    protected string $apiKey;
    protected ?string $defaultSenderName;
    protected CurlClient $curlClient;
    protected InmobileXmlConverter $xmlConverter;

    public function __construct(string $apiKey, ?string $defaultSenderName = null, CurlClient $curlClient = null)
    {
        $this->apiKey = $apiKey;
        $this->defaultSenderName = $defaultSenderName;
        $this->curlClient = $curlClient ?: new CurlClient();
        $this->xmlConverter = new InmobileXmlConverter();
    }

    public function send(InmobileMessage $message): array
    {
        if (!$message->getSender()) {
            $message->from($this->defaultSenderName);
        }

        $xml = $this->xmlConverter->convertToXml($message, $this->apiKey);

        $response = $this->curlClient->post(
            'https://mm.inmobile.dk/Api/V2/SendMessages',
            ['xml' => $xml]
        );

        libxml_use_internal_errors(true);

        if (!simplexml_load_string($response)) {
            throw new InmobileErrorException('ERROR: response code was ' . $response, (int) $response);
        }

        return $this->xmlConverter->parseReply($response);
    }
}
