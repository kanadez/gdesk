<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;

use App\Models\Category;


class LocationCategory extends BaseModel
{
    protected $table = "locations_categories";

    protected $fillable = [
        "id",
        "location_id",
        "category_id",
        "created_at",
        "updated_at",
    ];

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'id', 'location_id');
    }

}
