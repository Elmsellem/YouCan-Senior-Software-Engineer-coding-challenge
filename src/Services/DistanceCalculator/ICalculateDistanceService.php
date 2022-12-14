<?php

namespace YouCan\Services\DistanceCalculator;

use YouCan\Entities\Location;

interface ICalculateDistanceService
{
    /**
     * Calculate the distance in KM between two locations using the lat and lng.
     *
     * @param Location $firstLocation
     * @param Location $secondLocation
     * @return float
     */
    public function calculateDistanceInKM(Location $firstLocation, Location $secondLocation): float;
}
