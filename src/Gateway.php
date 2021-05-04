<?php

namespace Krve\Inmobile;

use DomDocument;
use Krve\Inmobile\Exceptions\GatewayErrorException;

class Gateway
{
    protected string $apiKey;
    protected CurlClient $client;

    public function __construct(string $apiKey, CurlClient $client = null)
    {
        $this->apiKey = $apiKey;
        $this->client = $client ?: new CurlClient('https://mm.inmobile.dk');
    }

    public function send(Message $message, string $statusCallbackUrl = null)
    {
        $response = $this->client->post('/Api/V2/SendMessages', [
            'xml' => $this->toXml($message, $statusCallbackUrl),
        ]);

        libxml_use_internal_errors(true);

        if (!simplexml_load_string($response)) {
            throw new GatewayErrorException('ERROR: response code was ' . $response, (int) $response);
        }

        return new Response($response, 200);
    }

    protected function toXml(Message $message, ?string $statusCallbackUrl = null): string|bool
    {
        $dom = new DomDocument('1.0', 'UTF-8');

        $request = $dom->createElement('request');
        $data = $dom->createElement('data');

        $messageElement = $message->toXmlElement($dom);
        $data->appendChild($messageElement);

        $authentication = $dom->createElement('authentication');
        $authentication->setAttribute('apikey', $this->apiKey);

        if ($statusCallbackUrl) {
            $callbackUrl = $dom->createElement('statuscallbackurl', $statusCallbackUrl);

            $data->appendChild($callbackUrl);
        }

        $request->appendChild($authentication);
        $request->appendChild($data);
        $dom->appendChild($request);

        return $dom->saveXML($dom->documentElement);
    }
}
