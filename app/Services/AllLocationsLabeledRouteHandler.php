<?php

namespace App\Services;

use App\Contracts\LabeledRouteHandlerContract;
use App\Repository\YMapsMarkerRepository;
use App\Services\FunctionResult as Result;

class AllLocationsLabeledRouteHandler implements LabeledRouteHandlerContract
{

    public function handle(): FunctionResult
    {
        $markers = app(YMapsMarkerRepository::class);
        $all_locations = $markers->all();

        return Result::success($all_locations);
    }
}
