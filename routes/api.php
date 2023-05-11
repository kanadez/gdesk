<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\YMapsMarkerController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\RoutesController;
use App\Http\Controllers\Api\SearchByQueryController;
use App\Http\Controllers\Api\SearchByCategoryController;
use App\Http\Controllers\Api\LocationsImagesController;

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

    $router->post('/markers/store', [YMapsMarkerController::class, 'create'])
        ->name('ymaps-markers-store');
});

Route::group([
    'prefix'    => "search",
    'as'        => 'search.',
], function (Router $router) {
    $router->post('/query', [SearchByQueryController::class, 'index'])
        ->name('search-by-query');
    $router->post('/category', [SearchByCategoryController::class, 'index'])
        ->name('search-by-category');
});

Route::group([
    'prefix'    => "locations",
    'as'        => 'locations.',
], function (Router $router) {
    $router->get('/{id}/edit', [LocationsController::class, 'edit'])
        ->name('locations-edit');
    $router->get('/create', [LocationsController::class, 'create'])
        ->name('locations-create');
    $router->post('/store', [LocationsController::class, 'store'])
        ->name('locations-store');
    $router->post('/images/upload', [LocationsImagesController::class, 'store'])
        ->name('locations-images-upload');
});

Route::group([
    'prefix'    => "routes",
    'as'        => 'routes.',
], function (Router $router) {
    $router->post('/', [RoutesController::class, 'index'])
        ->name('routes-index');
    $router->post('/store', [RoutesController::class, 'store'])
        ->name('routes-store');
});
