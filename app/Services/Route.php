<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

use App\Services\FunctionResult as Result;

class Route
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

    public function store(array $data): Result
    {
        $created_route = $this->routes->create([
            'name' => $data['name']
        ]);

        return Result::success($created_route->id);
    }

}
