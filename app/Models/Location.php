<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;

use App\Models\LocationImage;
use App\Models\LocationCategory;
use Laravel\Scout\Searchable;

class Location extends BaseModel
{

    use Searchable;

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
        return $this->hasMany(LocationTagLocation::class);
    }

    public function route()
    {
        return $this->hasOne(LocationRoute::class);
    }

    public function ymaps_marker()
    {
        return $this->hasOne(YMapsMarker::class);
    }
}
