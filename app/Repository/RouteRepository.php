<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:27
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
