<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:06
 */

namespace App\Models;


use Laravel\Scout\Searchable;

class LocationTagLocation extends BaseModel
{

    protected $table = "locations_tags_locations";

    protected $fillable = [
        "id",
        "location_id",
        "tag_id",
        "created_at",
        "updated_at",
    ];

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }

    public function tag()
    {
        return $this->hasOne('App\Models\LocationTag', 'id', 'tag_id');
    }
}
