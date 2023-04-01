<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;

use App\Models\Category;


class LocationRoute extends BaseModel
{
    protected $table = "locations_routes";

    protected $fillable = [
        "id",
        "location_id",
        "route_id",
        "created_at",
        "updated_at",
    ];

    public function route()
    {
        return $this->hasOne('App\Models\Route', 'id', 'route_id');
    }

}
