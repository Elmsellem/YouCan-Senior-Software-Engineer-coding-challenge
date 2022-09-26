<?php

namespace YouCan\Services\GoogleMaps;

use YouCan\Entities\LocationCollection;

class FindLocationService implements IFindLocationService
{
    protected IApiService $apiService;

    public function __construct(IApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function searchLocation(string $terms): LocationCollection
    {
        $params = [
            'fields' => 'formatted_address,geometry,place_id',
            'inputtype' => 'textquery',
            'input' => $terms,
        ];
        $data = $this->apiService->get('place/findplacefromtext/json', $params);
        return LocationCollection::createFromArray($data['candidates']);
    }
}