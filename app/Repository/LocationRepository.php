<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
 */

namespace App\Repository;

use App\Models\Location;

class LocationRepository extends BaseRepository
{
    public function model()
    {
        return Location::class;
    }
}
