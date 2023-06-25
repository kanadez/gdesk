<?php

namespace App\Services;

use App\Criteria\RoutesWithImageForSearchCriteria;
use App\Criteria\LocationsForSearchCriteria;
use App\Models\LocationRoute;
use App\Presenter\RouteForPopularPresenter;
use App\Presenter\LocationForSearchPresenter;
use App\Presenter\RouteForSearchPresenter;
use App\Repository\CategoryRepository;
use App\Repository\RouteRepository;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;
use App\Models\Location;
use App\Models\LocationTag;

class SearchByQuery
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
        $finded_locations = Location::search($data['query'])->get();// TODO пагинировать
        $finded_tags = LocationTag::search($data['query'])->query(function ($builder) {
            $builder->with('tag_locations');
        })->get();// TODO пагинировать

        $merged_results = $finded_locations->pluck('id')->toArray();

        foreach ($finded_tags as $tag) {
            $merged_results = array_merge($merged_results, $tag->tag_locations->pluck('location_id')->toArray());
        }

        $unique_locations_ids = array_unique($merged_results);
        $routes_with_images          = $this->locations
                                            ->setPresenter(RouteForSearchPresenter::class)
                                            ->getByCriteria(new RoutesWithImageForSearchCriteria($unique_locations_ids));

        $locations_titles_with_route = $this->locations
                                            ->setPresenter(LocationForSearchPresenter::class)
                                            ->getByCriteria(new LocationsForSearchCriteria($unique_locations_ids));

        return Result::success([
            'routes' => $routes_with_images['data'],
            'routes_locations' => $locations_titles_with_route['data']
        ]);
    }

}
