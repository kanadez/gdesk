<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
 */

namespace App\Criteria;

use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class RoutesWithImageCriteria implements CriteriaInterface {

    /**
     * RoutesWithImageCriteria constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        /*select routes.*,
            (select locations_images.image_path from locations_images where locations_images.location_id =
                (select locations_routes.location_id from locations_routes where locations_routes.route_id = routes.id limit 1)
            limit 1) as image_path
        from `routes`
        order by routes.created_at desc;*/

        $response = $model
                ->with('route_locations.location')
                ->selectRaw('routes.*,
                    (select locations_images.image_path from locations_images where locations_images.location_id =
                        (select locations_routes.location_id from locations_routes where locations_routes.route_id = routes.id limit 1)
                    limit 1) as image_path');

        if (Auth::check() && Auth::user()->isAdmin()) {
            $response->whereRaw('routes.hidden = 1 && routes.hidden = 0');
        } else {
            $response->where('routes.hidden', 0);
        }

        return $response;
    }

}

