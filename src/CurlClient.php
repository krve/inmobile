<?php

namespace Krve\Inmobile;

class CurlClient
{
    public function get(string $url): string|bool
    {
        return $this->request('GET', $url);
    }

    public function post(string $url, ?array $data = null): string|bool
    {
        return $this->request('POST', $url, $data);
    }

    protected function request(string $method, string $url, ?array $data = null): string|bool
    {
        $curl = curl_init($url);

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
