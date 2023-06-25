<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
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
