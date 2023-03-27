<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:06
 */

namespace App\Models;


class Category extends BaseModel
{
    protected $table = "categories";

    protected $fillable = [
        "id",
        "name",
        "order",
        "created_at",
        "updated_at",
    ];

}
