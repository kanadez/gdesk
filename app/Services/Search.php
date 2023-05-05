<?php

namespace App\Services;

use App\Models\LocationCategory;
use App\Models\LocationRoute;
use App\Models\LocationTag;
use App\Models\User;
use App\Models\LocationImage;

use App\Repository\CategoryRepository;
use App\Repository\RouteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

use App\Presenter\LocationPresenter;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;
use App\Models\Location;

class Search
{

    /**
     * @var LocationRepository
     */
    protected $locations;

    /**
     * @var CategoryRepository
     */
    protected $categories;

    /**
     * @var RouteRepository
     */
    protected $routes;

    public function __construct(
        LocationRepository $locations,
        CategoryRepository $categories,
        RouteRepository $routes
    )
    {
        $this->locations = $locations;
        $this->categories = $categories;
        $this->routes = $routes;
    }

    public function find(array $data)
    {
        $finded = Location::search($data['query'])->get();

        return Result::success($finded);
    }

}
