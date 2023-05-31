<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:27
 */

namespace App\Repository;

use App\Models\LocationTag;

class TagsRepository extends BaseRepository
{
    public function model()
    {
        return LocationTag::class;
    }
}
