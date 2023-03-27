<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:27
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
