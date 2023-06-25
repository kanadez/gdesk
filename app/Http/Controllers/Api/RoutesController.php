<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\CreateRouteRequest;

use App\Services\Route;

class RoutesController extends Controller
{

    /**
     * @var Route
     */
    private $route;

    public function __construct(
        Route $route
    )
    {
        $this->route = $route;
    }

    public function index()
    {
        $all_routes_result = $this->route->all();

        return response()->json([
            'status' => $all_routes_result->success,
            'routes' => $all_routes_result->returnValue
        ]);
    }

    /**
     * Сохраняет вновь созданную локацию
     *
     * @return array
     */
    public function store(CreateRouteRequest $request)
    {
        $store_result = $this->route->store($request->validated());

        return response()->json([
            'status' => $store_result->success,
            'route_id' => $store_result->returnValue
        ]);
    }

    public function destroy()
    {

    }

}
