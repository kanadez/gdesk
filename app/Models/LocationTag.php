<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;


use Laravel\Scout\Searchable;

class LocationTag extends BaseModel
{

    use Searchable;

    protected $table = "locations_tags";

    protected $fillable = [
        "id",
        "location_id",
        "tag",
        "created_at",
        "updated_at",
    ];

    public function locations()
    {
        return $this->hasMany('App\Models\Location', 'id', 'location_id');
    }

    public function tag_locations()
    {
        return $this->hasMany('App\Models\LocationTagLocation', 'tag_id', 'id');
    }

}
