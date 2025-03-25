<?php

namespace TessPayments\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpClient
{
    private $client;

    public function __construct(string $baseUrl)
    {
        $config = Config::getInstance();
        
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $config->get('timeout'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function post(string $endpoint, array $data): array
    {
        try {
            $response = $this->client->post($endpoint, ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleException($e);
        }
    }

    private function handleException(RequestException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $message = $response ? $response->getBody()->getContents() : $e->getMessage();
        
        throw new \RuntimeException("API Error [$statusCode]: $message", $statusCode);
    }
}