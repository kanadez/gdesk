<?php

namespace App\Services;

use App\Models\LocationRoute;
use App\Models\User;

use App\Presenter\RoutePresenter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

use App\Criteria\RoutesWithImageCriteria;

use App\Repository\RouteRepository;
use App\Services\FunctionResult as Result;

class AdminRoute
{

    /**
     * @var RouteRepository
     */
    protected $routes;

    public function __construct(
        RouteRepository $routes
    )
    {
        $this->routes = $routes;
    }

    public function all(): Result
    {
        $routes = $this->routes
                    ->pushCriteria(RoutesWithImageCriteria::class)
                    ->setPresenter(RoutePresenter::class)
                    ->orderBy('created_at', 'desc')
                    ->get(); // TODO сделать вывод маршрутов только текущего юзера

        return Result::success($routes['data']);
    }

    public function store(array $data): Result
    {
        $created_route = $this->routes->create([
            'name' => $data['name']
        ]);

        return Result::success($created_route->id);
    }

    /**
     * Прикревляет лоакцию к маршруту
     *
     * @param int $location_id
     * @param int $route_id
     */
    public function linkToLocation(int $location_id, int $route_id) {
        $new_route = new LocationRoute();
        $new_route->location_id = $location_id;
        $new_route->route_id = $route_id;
        $new_route->save();
    }

}
