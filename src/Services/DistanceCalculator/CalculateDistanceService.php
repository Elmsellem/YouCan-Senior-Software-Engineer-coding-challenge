<?php

namespace YouCan\Services\DistanceCalculator;

use YouCan\Entities\Location;

class CalculateDistanceService implements ICalculateDistanceService
{
    public function calculateDistanceInKM(Location $firstLocation, Location $secondLocation): float
    {
        $lat1 = $firstLocation->getLat();
        $lon1 = $firstLocation->getLng();
        $lat2 = $secondLocation->getLat();
        $lon2 = $secondLocation->getLng();

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return round($miles * 1.609344, 3);
    }
}
