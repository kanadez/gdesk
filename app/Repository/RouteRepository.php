<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
 */

namespace App\Repository;

use App\Models\Route;

class RouteRepository extends BaseRepository
{
    public function model()
    {
        return Route::class;
    }
}
