<?php

namespace App\Factory;

use App\Contracts\LabeledRouteHandlerContract;
use App\Contracts\LabeledRouteHandlersFactory as Factory;
use App\Exceptions\LabeledRouteHandlerNotAvailableException;
use App\Services\AllLocationsLabeledRouteHandler;

class LabeledRouteHandlersFactory implements Factory
{
    protected static $availableHandlers = [
        'all_locations' => AllLocationsLabeledRouteHandler::class,
    ];

    public function make(string $handler_class_name): LabeledRouteHandlerContract
    {
        if (!array_key_exists($handler_class_name, self::$availableHandlers)) {
            throw new LabeledRouteHandlerNotAvailableException();
        }

        $handlerClass = self::$availableHandlers[$handler_class_name];

        return (new $handlerClass());
    }
}
