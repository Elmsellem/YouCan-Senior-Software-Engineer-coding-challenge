<?php

namespace YouCan\Tests\Services\DistanceCalculator;

use Illuminate\Contracts\Container\BindingResolutionException;
use YouCan\Entities\Location;
use YouCan\Services\DistanceCalculator\ICalculateDistanceService;
use YouCan\Tests\Services\TestCase;

class CalculateDistanceServiceTest extends TestCase
{
    protected ICalculateDistanceService $calculateDistanceService;

    /** @throws BindingResolutionException */
    public function setUp(): void
    {
        parent::setUp();
        $this->calculateDistanceService = $this->app->getLaravel()->make(ICalculateDistanceService::class);
    }

    public function test_calculate_distance_successfully()
    {
        $startLocation = new Location('Unnamed road', 34.696699, -1.9189603, '123');
        $endLocation = new Location('Unnamed road', 34.7107514, -1.9203737, '124');

        $distanceKMToBe = 1.568;

        $distanceKM = $this->calculateDistanceService->calculateDistanceInKM($startLocation, $endLocation);

        $this->assertEquals($distanceKM, $distanceKMToBe);
    }
}
