<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\Location;
use App\Models\Route;

class LocationForSearchTransformer extends BaseTransformer
{

    /**
     * @param Location () $route
     * @return array
     * @throws \Exception
     */
    public function transform(Location $location)
    {
        return [
            'route_id' => (int) empty($location->route) ? null : $location->route->route->id,
            'location_id' => (int) $location->id,
            'title' => (string) $location->title
        ];
    }
}
