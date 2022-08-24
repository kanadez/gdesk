<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;

class Manual extends BasePaysystem implements PaysystemContract
{

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        return new PaysystemResponse(PaysystemResponse::STATUS_OK, null, Payout::STATUS_SUCCESS, 'success');
    }

    function balance(): ?BalanceResource
    {
        return null;
    }

    function updateStatus($payout): PaysystemResponse
    {
        return new PaysystemResponse(PaysystemResponse::STATUS_OK);
    }

    function translateStatus(string $status): string
    {
        return "";
    }

    function getRates(): ?array
    {
        return null;
    }
}
