<?php

namespace App\Services;

use App\Models\LocationCategory;
use App\Repository\CategoryRepository;
use App\Repository\RouteRepository;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;
use App\Models\Location;
use App\Models\LocationTag;

class SearchByCategory
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
        $category = $this->categories
                        ->with('location_category.locations.route.route')
                        ->find($data['category']);

        $merged_results = [];

        foreach ($category->location_category as $category_locations) {
            foreach ($category_locations->locations as $location) {
                if (!empty($location->route)) {
                    $merged_results[] = $location->route->route->toArray();
                }
            }
        }

        $uniqued_results = array_unique($merged_results, SORT_REGULAR);

        return Result::success($uniqued_results);
    }

}
