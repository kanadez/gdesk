<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\LocationRoute;

class LocationRouteTransformer extends BaseTransformer
{

    /**
     * @param LocationRouteTransformer () $locationRoute
     * @return array
     * @throws \Exception
     */
    public function transform(LocationRoute $locationRoute)
    {
        return [
            'name' => (string) $locationRoute->route->name
        ];
    }
}
