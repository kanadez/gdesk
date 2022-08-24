<?php

namespace App\Filters\Payments;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class PayoutsFilter
{

    public static function filter(Builder $payouts, Request $request): Builder
    {

        $request_data = $request->all();

        foreach ($request_data as $key => $data) {

            if ($data === null) continue;

            switch ($key) {
                case 'id':
                    $payouts = $payouts->where('p.id', $data);
                    break;

                case 'paysystem_id':
                    $payouts = $payouts->where('pm.paysystem_id', $data);
                    break;

                case 'payment_method_id':
                    $payouts = $payouts->where('p.payment_method_id', $data);
                    break;

                case 'address':
                    $payouts = $payouts->whereRaw("p.address LIKE '%$data%'");
                    break;

                case 'amount':
                    $amt_exploded = explode("-", $data);

                    if (count($amt_exploded) === 1) {
                        $amt = (float)$data;
                        if ($amt < 1) continue;

                        $payouts = $payouts->where('p.amount','=', $amt);
                    } elseif (count($amt_exploded) === 2) {
                        $payouts = $payouts->where('p.amount', '>=', trim($amt_exploded[0]))->where('p.amount', '<=', trim($amt_exploded[1]));
                    }

                    break;

                case 'status':
                    $payouts = $payouts->where('p.status', $data);
                    break;

                case 'external_id':
                    if (strlen($data) === 0) continue;

                    $payouts = $payouts->where('p.external_id', $data);
                    break;

                case 'comment':
                    $payouts = $payouts->whereRaw("p.comment LIKE '%$data%'");
                    break;

                case 'category_id':
                    $payouts = $payouts->where('p.category_id', $data);
                    break;

                case 'is_internal':
                    $payouts = $payouts->where('p.is_internal', true);
                    break;

                case 'dates':
                    $payouts = $payouts
                                    ->where('p.created_at', '>=', $data[0])
                                    ->where('p.created_at', '<=', $data[1]);
                    break;
            }
        }

        return $payouts;
    }

}
