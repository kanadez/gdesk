<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\LocationTag;
use App\Models\LocationTagLocation;
use Illuminate\Support\Carbon;

class LocationTagTransformer extends BaseTransformer
{

    /**
     * @param LocationTagTransformer () $tag
     * @return array
     * @throws \Exception
     */
    public function transform(LocationTagLocation $tag_location)
    {
        return [
            'name' => $tag_location->tag->tag
        ];
    }
}
