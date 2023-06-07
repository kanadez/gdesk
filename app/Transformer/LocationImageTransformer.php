<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Transformer;

use App\Models\LocationImage;
use Illuminate\Support\Carbon;

class LocationImageTransformer extends BaseTransformer
{

    /**
     * @param LocationImage () $image
     * @return array
     * @throws \Exception
     */
    public function transform(LocationImage $image)
    {
        return [
            'image_path' => (string) "storage/$image->image_path"
        ];
    }
}
