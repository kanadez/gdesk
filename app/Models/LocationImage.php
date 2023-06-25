<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;


class LocationImage extends BaseModel
{
    protected $table = "locations_images";

    protected $fillable = [
        "id",
        "location_id",
        "image_path",
        "created_at",
        "updated_at",
    ];

}
