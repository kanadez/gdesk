<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenter;

use App\Transformer\RouteForPopularTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class RouteForPopularPresenter extends FractalPresenter
{

    /**
     * @returs LocationRouteForSearchTransformer|\League\Fractal\TransformerAbstract
     * @throws \Exception
     */
    public function getTransformer()
    {
        return new RouteForPopularTransformer();
    }
}
