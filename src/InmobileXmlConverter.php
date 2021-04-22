<?php

namespace Krve\Inmobile;

use Krve\Inmobile\Models\InmobileMessage;

class InmobileXmlConverter
{
    /**
     * Convert the InmobileMessage into xml to be sent to the Inmobile API.
     */
    public function convertToXml(InmobileMessage $message, string $apiKey): string
    {
        $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><request/>');
        $xml->addChild('authentication')->addAttribute('apikey', $apiKey);
        $data = $xml->addChild('data');
        $element = $data->addChild('message');

        $element->addChild('sendername')->addCData($message->getSender());
        $textElement = $element->addChild('text')->addCData($message->getMessage());

        if ($message->getEncoding()) {
            $textElement->addAttribute('encoding', $message->getEncoding());
        }
        if ($message->shouldFlash() === true) {
            $textElement->addAttribute('flash', 'true');
        }

        if ($message->getSendTime()) {
            $element->addChild('sendtime', $message->getSendTime());
        }
        if ($message->getExpireInSeconds()) {
            $element->addChild('expiretimeinseconds', $message->getExpireInSeconds());
        }

        foreach ($message->getRecipients() as $recipient) {
            $recipientElement = $element->addChild('recipients')
                ->addChild('msisdn', $recipient->getMsisdn());

            if ($recipient->getId()) {
                $recipientElement->addAttribute('id', $recipient->getId());
            }
        }

        return $xml->asXML();
    }

    /**
     * Parse the reply from sending text messages into arrays of each sent message.
     */
    public function parseReply($reply): array
    {
        $xml = simplexml_load_string($reply);

        $reply = json_decode(json_encode((array) $xml), true);

        // Check if the response is multiple or a single recipient
        if (in_array(0, array_keys($reply['recipient']))) {
            return array_map(function ($item) {
                return [
                    'msisdn' => $item['@attributes']['msisdn'],
                    'id' => $item['@attributes']['id'],
                ];
            }, $reply['recipient']);
        }

        return [
            [
                'msisdn' => $reply['recipient']['@attributes']['msisdn'],
                'id' => $reply['recipient']['@attributes']['id'],
            ]
        ];
    }
}
