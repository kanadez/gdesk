<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;

use App\Models\LocationImage;
use App\Models\LocationCategory;

class Location extends BaseModel
{
    protected $table = "locations";

    protected $fillable = [
        "id",
        "title",
        "description",
        "created_at",
        "updated_at",
    ];

    public function images()
    {
        return $this->hasMany(LocationImage::class);
    }

    public function category()
    {
        return $this->hasOne(LocationCategory::class);
    }

    public function tags()
    {
        return $this->hasMany(LocationTag::class);
    }

    public function route()
    {
        return $this->hasOne(LocationRoute::class);
    }
}
