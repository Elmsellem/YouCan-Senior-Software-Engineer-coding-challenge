<?php

namespace YouCan\Tests\Services\GoogleMaps;

use Illuminate\Contracts\Container\BindingResolutionException;
use YouCan\Entities\LocationCollection;
use YouCan\Services\GoogleMaps\IFindLocationService;
use YouCan\Tests\Services\TestCase;

class FindLocationServiceTest extends TestCase
{
    protected IFindLocationService $findLocationService;

    /** @throws BindingResolutionException */
    public function setUp(): void
    {
        parent::setUp();
        $this->findLocationService = $this->app->getLaravel()->make(IFindLocationService::class);
    }

    public function test_search_location_return_location_collection()
    {
        $locations = $this->findLocationService->searchLocation('oujda');
        $this->assertInstanceOf(LocationCollection::class, $locations);
    }
}
