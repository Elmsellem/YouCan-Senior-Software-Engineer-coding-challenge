<?php

namespace YouCan\Tests\Services\GoogleMaps;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use mysql_xdevapi\Exception;
use ReflectionClass;
use YouCan\Guzzle\BackOffMiddleware;
use YouCan\Services\GoogleMaps\ApiService;
use YouCan\Services\GoogleMaps\IApiService;
use YouCan\Services\GoogleMaps\IFindLocationService;
use YouCan\Tests\Services\TestCase;

class ApiServiceTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function test_api_service_retries_three_times_before_failing_to_connect_to_host()
    {
        $container = [];
        $maxRetries = 3;
        $retryMultiplier = 0;

        $backOffMiddleware = new BackOffMiddleware($maxRetries, $retryMultiplier);

        $mockHandler = new MockHandler($this->getResponses());
        $stack = \GuzzleHttp\HandlerStack::create($mockHandler);
        $stack->push($backOffMiddleware());
        $stack->push(Middleware::history($container));

        $args = [
            ['handler' => $stack]
        ];
        $apiService = $this->app->getLaravel()->make(IApiService::class, $args);

        try {
            $apiService->get('/');
        } catch (GuzzleException $e) {
            $this->assertCount($maxRetries, $container);
        }
    }

    private function getResponses(): array
    {
        return [
            new \GuzzleHttp\Psr7\Response(500, [], 'user created response'),
            new \GuzzleHttp\Psr7\Response(500, [], 'user created response'),
            new \GuzzleHttp\Psr7\Response(500, [], 'user created response'),
            new \GuzzleHttp\Psr7\Response(500, [], 'user created response'),
            new \GuzzleHttp\Psr7\Response(500, [], 'user created response'),
        ];
    }
}
