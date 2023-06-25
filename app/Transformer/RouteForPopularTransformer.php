<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Presenter\RouteLocationForSearchPresenter;

class RouteForPopularTransformer extends BaseTransformer
{

    /**
     * @param Object () $route
     * @return array
     * @throws \Exception
     */
    public function transform(Object $route)
    {
        $routeLocationPresenter = new RouteLocationForSearchPresenter();
        $locations_presented = $routeLocationPresenter->present($route->route_locations);

        return [
            'id' => (int) $route->id,
            'image_path' => empty($route->image_path) ? "" : "storage/$route->image_path",
            'name' => (string) $route->name,
            'locations' => (array) $locations_presented['data']
        ];
    }
}
