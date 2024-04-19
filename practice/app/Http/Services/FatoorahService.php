<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class FatoorahService
{
    private $base_url;
    private $headers;
    private $request_client;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = config('myfatoorah.base_url');
        $this->headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('myfatoorah.api_key')
        ];
    }

    private function buildRequest($url, $method, $data = [])
    {
        $response = $this->request_client->request($method, $this->base_url . $url, [
            'headers' => $this->headers,
            'json' => $data
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody(), true);
    }

    public function sendPayment($data)
    {
        $response = $this->buildRequest('v2/SendPayment', 'POST', $data);
        return $response;
    }

    public function getPaymentStatus($data)
    {
        $response = $this->buildRequest('v2/getPaymentStatus', 'POST', $data);
        return $response;
    }
}
