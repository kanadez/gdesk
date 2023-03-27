<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;


class LocationTag extends BaseModel
{
    protected $table = "locations_tags";

    protected $fillable = [
        "id",
        "location_id",
        "tag",
        "created_at",
        "updated_at",
    ];

}
