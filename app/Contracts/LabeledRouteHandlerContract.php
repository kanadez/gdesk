<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 25.06.23
 * Time: 14:00
 */

namespace App\Contracts;

use App\Services\FunctionResult;

interface LabeledRouteHandlerContract
{
    public function handle(): FunctionResult;
}
