<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenter;

use App\Transformer\LocationImageTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class LocationImagePresenter extends FractalPresenter
{

    /**
     * @returs LocationImageTransformer|\League\Fractal\TransformerAbstract
     * @throws \Exception
     */
    public function getTransformer()
    {
        return new LocationImageTransformer();
    }
}
