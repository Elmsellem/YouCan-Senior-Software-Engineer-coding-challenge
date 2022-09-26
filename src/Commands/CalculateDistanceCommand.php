<?php

namespace YouCan\Commands;

use Illuminate\Console\Command;
use YouCan\Entities\Location;
use YouCan\Services\DistanceCalculator\ICalculateDistanceService;
use YouCan\Services\GoogleMaps\IFindLocationService;

class CalculateDistanceCommand extends Command
{
    protected $name = "calculate-distance";

    protected $description = "Command to calculate the distance between two places.";

    private ICalculateDistanceService $calculateDistanceService;
    private IFindLocationService $findLocationService;

    public function __construct(
        ICalculateDistanceService $calculateDistanceService,
        IFindLocationService      $findLocationService
    )
    {
        parent::__construct();

        $this->calculateDistanceService = $calculateDistanceService;
        $this->findLocationService = $findLocationService;
    }

    public function handle()
    {
        $firstLocation = $this->getLocation();
        $secondLocation = $this->getLocation();

        $distance = $this->calculateDistanceService->calculateDistanceInKM($firstLocation, $secondLocation);

        $this->info(
            sprintf(
                "The distance between `%s` and `%s` is `%s` Km",
                $firstLocation->getAddress(),
                $secondLocation->getAddress(),
                $distance
            )
        );
    }

    public function getLocation(): Location
    {
        $terms = (string)$this->ask("Search for a location: ");
        $locations = $this->findLocationService->searchLocation($terms);

        $choices = $locations->map(fn(Location $l) => $l->getPlaceID() . ' ' . $l->getAddress())->toArray();

        $selectedChoice = $this->askWithCompletion("Here's what I found: ", $choices);
        $selectedPlaceId = explode(' ', $selectedChoice)[0];

        return $locations->filter(fn(Location $l) => $l->getPlaceID() == $selectedPlaceId)->first();
    }

    public function askWithCompletion($question, $choices, $default = null): mixed
    {
        $selectedChoice = parent::askWithCompletion($question, $choices, $default);
        if (is_null($selectedChoice)) {
            $selectedChoice = $this->askWithCompletion($question, $choices, $default);
        }
        return $selectedChoice;
    }
}