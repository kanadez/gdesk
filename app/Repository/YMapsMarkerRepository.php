<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:27
 */

namespace App\Repository;

use App\Models\YMapsMarker;

class YMapsMarkerRepository extends BaseRepository
{
    public function model()
    {
        return YMapsMarker::class;
    }
}
