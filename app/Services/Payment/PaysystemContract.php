<?php

namespace App\Services\Payment;

interface PaysystemContract
{
    function send(string $address, float $amount, string $id, string $alias, array $options = []) : PaysystemResponse;
    function balance() : ?BalanceResource;
    function updateStatus($payout) : PaysystemResponse;
    function translateStatus(string $status) : string;
    function getRates() : ?array;
}
