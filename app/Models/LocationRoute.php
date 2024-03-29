<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
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

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

}
