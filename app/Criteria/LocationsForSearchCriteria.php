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

class LocationsForSearchCriteria implements CriteriaInterface {

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
        return $model->selectRaw('locations.id, locations.title')
                        ->with('routes.route')
                        ->whereIn('locations.id', $this->locations_ids);
    }

}

