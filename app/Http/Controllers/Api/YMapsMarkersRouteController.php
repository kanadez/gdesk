<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Route;
use Illuminate\Http\Request;

use App\Http\Requests\Api\CreateYMapsMarkerRequest;

use App\Repository\YMapsMarkerRepository;
use App\Repository\RouteRepository;

class YMapsMarkersRouteController extends Controller
{

    /**
     *
     * @var RouteRepository
     */
    private $repo;

    /**
     * @var Route
     */
    private $route;

    public function __construct(
        RouteRepository $repo,
        Route $route
    )
    {
        $this->repo = $repo;
        $this->route = $route;
    }

    public function index($route_id)
    {
        $route = $this->repo->find($route_id);

        if (empty($route->label)) {
            $markers_by_route = $this->repo
                                        ->with('route_locations.location.ymaps_marker')
                                        ->find($route_id);

            $markers = $markers_by_route->route_locations->map(function ($route_location) {
                return $route_location->location->ymaps_marker;
            });
        } else {
            $handler_response = $this->route->handleLabeled($route);
            $markers = $handler_response->returnValue;
        }

        return response()->json([
            "status" => "success",
            "data"   => $markers
        ]);
    }

}
