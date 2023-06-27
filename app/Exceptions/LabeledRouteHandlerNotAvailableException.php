<?php

namespace App\Exceptions;

use Exception;

class LabeledRouteHandlerNotAvailableException extends Exception
{
    public function render($request)
    {
        return response()->json(["error" => true, "message" => 'This labeled route handler not found!'], 404);
    }
}
