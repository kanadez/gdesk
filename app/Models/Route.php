<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;

use App\Models\RouteImage;
use App\Models\RouteCategory;

class Route extends BaseModel
{
    protected $table = "routes";

    protected $fillable = [
        "id",
        "name",
        "created_at",
        "updated_at",
    ];

    public function route_locations()
    {
        return $this->hasMany(LocationRoute::class);
    }

}
