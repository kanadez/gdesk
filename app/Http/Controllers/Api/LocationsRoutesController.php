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

class LocationsRoutesController extends Controller
{

    /**
     * @var Route
     */
    private $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function index()
    {

    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->route->linkToLocation($data['location_id'], $data['route_id']);
    }

}
