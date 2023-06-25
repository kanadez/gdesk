<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
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
