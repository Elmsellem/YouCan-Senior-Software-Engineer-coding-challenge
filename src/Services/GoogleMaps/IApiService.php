<?php

namespace YouCan\Services\GoogleMaps;

interface IApiService
{
    /**
     * Call an API and return array from JSON response.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params): array;
}
