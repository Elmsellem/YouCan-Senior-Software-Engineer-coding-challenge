<?php

namespace YouCan\Services\GoogleMaps;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use YouCan\Guzzle\BackOffMiddleware;

class ApiService implements IApiService
{
    protected Client $client;
    protected string $apiKey = 'AIzaSyCYdYiYNtWhlVxmyvo';
    private array $clientOptions;

    public function __construct(array $clientOptions = [])
    {
        $this->clientOptions = $clientOptions;
        $this->initClient();
    }

    private function initClient()
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push((new BackOffMiddleware)());

        $config = [
            'base_uri' => 'https://maps.googleapis.com/maps/api/',
            'handler' => $handlerStack,
            'timeout' => 30
        ];

        $config = array_replace($config, $this->clientOptions);

        $this->client = new Client($config);
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $params = []): array
    {
        $params['key'] = $this->apiKey;

        $request = new Request('get', $endpoint);
        $response = $this->client->send($request, ['query' => $params]);

        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['status'] === 'OK') return $data;

        $msg = $data['status'] . ': ' . $data['error_message'] ?? '';
        throw new BadResponseException($msg, $request, $response);
    }
}