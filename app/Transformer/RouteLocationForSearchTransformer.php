<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\LocationRoute;

class RouteLocationForSearchTransformer extends BaseTransformer
{

    /**
     * @param LocationRoute () $route
     * @return array
     * @throws \Exception
     */
    public function transform(LocationRoute $location)
    {
        return [
            'id' => (int) $location->id,
            'title' => (string) $location->location->title
        ];
    }
}
