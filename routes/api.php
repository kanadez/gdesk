<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\YMapsMarkerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'    => "ymaps",
    'as'        => 'ymaps.',
], function (Router $router) {
    $router->get('/markers', [YMapsMarkerController::class, 'index'])
        ->name('ymaps-markers-index');

    $router->post('/markers/create', [YMapsMarkerController::class, 'create'])
        ->name('ymaps-markers-create');
});
