<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;


class YMapMarker extends BaseModel
{
    protected $table = "ymap_markers";

    protected $fillable = [
        "id",
        "lat",
        "lng",
        "created_at",
        "updated_at",
    ];

}
