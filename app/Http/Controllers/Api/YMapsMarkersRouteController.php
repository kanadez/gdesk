<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function __construct(RouteRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index($route_id)
    {
        $markers_by_route = $this->repo
                                ->with('route_locations.location.ymaps_marker')
                                ->find($route_id);

        $markers = $markers_by_route->route_locations->map(function($route_location){
            return $route_location->location->ymaps_marker;
        });

        return response()->json([
            "status" => "success",
            "data"   => $markers
        ]);
    }

}
