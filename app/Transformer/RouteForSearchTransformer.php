<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Presenter\RouteLocationForSearchPresenter;

class RouteForSearchTransformer extends BaseTransformer
{

    /**
     * @param Object () $route
     * @return array
     * @throws \Exception
     */
    public function transform(Object $route)
    {
        return [
            'id' => (int) $route->id,
            'image_path' => empty($route->image_path) ? "" : "storage/$route->image_path",
            'name' => (string) $route->name,
        ];
    }
}
