<?php

namespace YouCan\Entities;

use Illuminate\Support\Collection;

class LocationCollection extends Collection
{
    public static function createFromArray(array $attributes): self
    {
        $data = array_map(function ($item) {
            $location = $item['geometry']['location'];

            $address = $item['formatted_address'];
            $lat = $location['lat'];
            $lng = $location['lng'];
            $placeID = $item['place_id'];

            return new Location($address, $lat, $lng, $placeID);
        }, $attributes);

        return new self($data);
    }
}
