<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\Location;
use App\Models\Route;
use App\Presenter\LocationRouteIdOnlyPresenter;
use App\Presenter\RouteLocationForSearchPresenter;

class LocationForSearchTransformer extends BaseTransformer
{

    /**
     * @param Location () $location
     * @return array
     * @throws \Exception
     */
    public function transform(Location $location)
    {

        $routeLocationPresenter = new LocationRouteIdOnlyPresenter();
        $routes_presented = $routeLocationPresenter->present($location->routes);

        return [
            'routes' => (array) $routes_presented['data'],
            'location_id' => (int) $location->id,
            'title' => (string) $location->title
        ];
    }
}
