<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
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

}
