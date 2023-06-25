<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;


class YMapsMarker extends BaseModel
{
    protected $table = "ymaps_markers";

    protected $fillable = [
        "id",
        "location_id",
        "lat",
        "lng",
        "created_at",
        "updated_at",
    ];

}
