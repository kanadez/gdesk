<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use App\Repository\RouteRepository;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;
use App\Models\Location;
use App\Models\LocationTag;

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
        $finded_locations = Location::search($data['query'])->query(function ($builder) {
            $builder->with('route.route');
        })->get();// TODO пагинировать
        $finded_tags = LocationTag::search($data['query'])->query(function ($builder) {
            $builder->with('locations.route.route');
        })->get();// TODO пагинировать

        $merged_results = [];

        foreach ($finded_tags as $tag) {
            foreach ($tag->locations as $location) {
                if (!empty($location->route)) {
                    $merged_results[] = $location->route->route->toArray();
                }
            }
        }

        foreach ($finded_locations as $location) {
            if (!empty($location->route)) {
                $merged_results[] = $location->route->route->toArray();
            }
        }

        $uniqued_results = array_unique($merged_results, SORT_REGULAR);

        return Result::success($uniqued_results);
    }

}
