<?php

namespace App\Contracts;

interface LabeledRouteHandlersFactory
{
    public function make(string $handler_class_name): LabeledRouteHandlerContract;
}
