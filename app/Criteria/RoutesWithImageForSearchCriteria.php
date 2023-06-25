<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class RoutesWithImageForSearchCriteria implements CriteriaInterface {

    protected $locations_ids;

    /**
     * RoutesWithImageCriteria constructor.
     */
    public function __construct(array $locations_ids)
    {
        $this->locations_ids = $locations_ids;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        /*select routes.*, (select image_path from locations_images where locations_images.location_id = locations.id limit 1) as image_path
        from locations
        join locations_routes on locations_routes.location_id = locations.id
        join routes on routes.id = locations_routes.route_id
        where locations.id in (1, 2, 3)
        group by routes.id;*/

        return $model->selectRaw('routes.*, (select image_path from locations_images where locations_images.location_id = locations.id limit 1) as image_path')
                        ->join('locations_routes', 'locations_routes.location_id', '=', 'locations.id')
                        ->join('routes',  'routes.id', '=', 'locations_routes.route_id')
                        ->whereIn('locations.id', $this->locations_ids)
                        ->groupBy('routes.id');
    }

}

