<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\Location;
use App\Presenter\LocationImagePresenter;
use App\Presenter\LocationTagPresenter;
use Illuminate\Support\Carbon;

class LocationTransformer extends BaseTransformer
{

    /**
     * @param Location () $location
     * @return array
     * @throws \Exception
     */
    public function transform(Location $location)
    {
        $imagePresenter = new LocationImagePresenter();
        $images_presented = $imagePresenter->present($location->images);

        $tagPresenter = new LocationTagPresenter();
        $tags_presented = $tagPresenter->present($location->tags);

        return [
            'id' => $location->id,
            'title' => $location->title,
            'images' => $images_presented['data'],
            'category' => $location->category->category->name,
            'tags' => $tags_presented['data'],
            'description' => $location->description,
            'description' => $location->description,
        ];
    }
}
