<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\YMapsMarkerController;
use App\Http\Controllers\Api\YMapsMarkersRouteController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\LocationsRoutesController;
use App\Http\Controllers\Api\RoutesController;
use App\Http\Controllers\Api\SearchTagsController;
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
    'middleware' => ['api'],
    'prefix'    => "ymaps",
    'as'        => 'ymaps.',
], function (Router $router) {
    $router->get('/markers', [YMapsMarkerController::class, 'index'])
        ->name('ymaps-markers-index'); // TODO в будущем вообще убрать возможность вывода всех маркеров подряд
    $router->get('/markers/route/{route_id}', [YMapsMarkersRouteController::class, 'index'])
        ->name('ymaps-markers-route');
    $router->post('/markers/store', [YMapsMarkerController::class, 'create'])
        ->name('ymaps-markers-store');
});

Route::group([
    'middleware' => ['api'],
    'prefix'    => "search",
    'as'        => 'search.',
], function (Router $router) {
    $router->post('/query', [SearchByQueryController::class, 'index'])
        ->name('search-by-query');
    $router->post('/category', [SearchByCategoryController::class, 'index'])
        ->name('search-by-category');
});

Route::group([
    'middleware' => ['api'],
    'prefix'    => "locations",
    'as'        => 'locations.',
], function (Router $router) {
    $router->get('/{id}/show', [LocationsController::class, 'show'])
        ->name('locations-show');
    $router->get('/{id}/edit', [LocationsController::class, 'edit'])
        ->name('locations-edit');
    $router->get('/create', [LocationsController::class, 'create'])
        ->name('locations-create');
    $router->post('/store', [LocationsController::class, 'store'])
        ->name('locations-store');
    $router->post('{id}/update', [LocationsController::class, 'update'])
        ->name('locations-update');
    $router->post('/images/upload', [LocationsImagesController::class, 'store'])
        ->name('locations-images-upload');
    $router->post('/route/add', [LocationsRoutesController::class, 'store'])
        ->name('locations-add-to-route');
});

Route::group([
    'middleware' => ['api'],
    'prefix'    => "routes",
    'as'        => 'routes.',
], function (Router $router) {
    $router->post('/', [RoutesController::class, 'index'])
        ->name('routes-index');
    $router->post('/store', [RoutesController::class, 'store'])
        ->name('routes-store');
});

Route::group([
    'middleware' => ['api'],
    'prefix'    => "tags",
    'as'        => 'tags.',
], function (Router $router) {
    $router->post('/find', [SearchTagsController::class, 'index'])
        ->name('tags-search');
});
