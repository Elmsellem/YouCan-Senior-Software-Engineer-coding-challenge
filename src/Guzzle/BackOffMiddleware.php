<?php

namespace YouCan\Guzzle;

use Psr\Http\Message\{RequestInterface, ResponseInterface};
use GuzzleHttp\{Client,
    Exception\ConnectException,
    HandlerStack,
    Middleware,
    Psr7\Request,
    Psr7\Response,
    RetryMiddleware
};

class BackOffMiddleware
{
    private int $maxRetries = 3;
    private int $retryMultiplier = 3;

    public function __construct(int $maxRetries = 3, int $retryMultiplier = 1000)
    {
        $this->maxRetries = $maxRetries;
        $this->retryMultiplier = $retryMultiplier;
    }

    public function __invoke(): callable
    {
        return Middleware::retry($this->decider(), $this->delay());
    }

    private function decider(): callable
    {
        return (function ($retries, Request $request, Response $response = null, ConnectException $exception = null): bool {
            return $retries + 1 < $this->maxRetries && $response?->getStatusCode() >= 500;
        });
    }

    private function delay(): callable
    {
        return function (int $retries, ResponseInterface $response): int {
            return $retries * $this->retryMultiplier;
        };
    }
}