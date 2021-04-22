<?php

namespace Krve\Inmobile;

class CurlClient
{
    protected ?string $baseUri = null;

    public function __construct(string $baseUri = null)
    {
        $this->baseUri = $baseUri;
    }

    public function post(string $url, ?array $data = null): string|bool
    {
        return $this->request('POST', $url, $data);
    }

    protected function request(string $method, string $url, ?array $data = null): string|bool
    {
        $curl = curl_init(
            sprintf('%s/%s', rtrim($this->baseUri, '/'), ltrim($url, '/'))
        );

        curl_setopt($curl, CURLOPT_HEADER, 0);
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
        }
        if ($method === 'POST' && $data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
