<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class MyfatoorahServices
{
    private $headers;
    private $request_client;
    private $base_url;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = env('MYFATOORAH_URL');
        $this->headers = [
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer ' . env('MYFATOORAH_KEY')
        ];
    }


    private function buildRequest($uri, $method, $data = [])
    {
        $request = new Request($method, $this->base_url . $uri, $this->headers);
        if (!$data) return false;

        $response = $this->request_client->send($request, ['json' => $data]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $response = json_decode($response->getBody(), true);
        return $response;
    }

    public function sendPayment($data)
    {
        return $this->buildRequest('v2/SendPayment', 'POST', $data);
    }
    public function  getPaymentInfo($data)
    {
        return $this->buildRequest('v2/GetPaymentStatus', 'POST', $data);
    }
}
