<?php

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Console\Application;
use YouCan\Services\DistanceCalculator\CalculateDistanceService;
use YouCan\Services\DistanceCalculator\ICalculateDistanceService;
use YouCan\Services\GoogleMaps\FindLocationService;
use YouCan\Services\GoogleMaps\IApiService;
use YouCan\Services\GoogleMaps\ApiService;
use YouCan\Services\GoogleMaps\IFindLocationService;

$container = new Container;

$container->singleton(IApiService::class, static function ($app, array $params = []) {
    return new ApiService(...$params);
});

$container->singleton(ICalculateDistanceService::class, static function ($app) {
    return new CalculateDistanceService;
});

$container->singleton(IFindLocationService::class, static function ($app) use ($container) {
    return new FindLocationService($app->get(IApiService::class));
});

$events = new Dispatcher($container);

$artisan = new Application($container, $events, 'Version 1');
$artisan->setName('My Console App Name');

return $artisan;
