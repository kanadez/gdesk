<?php

namespace App\Services\Payment;

class BalanceResource implements \JsonSerializable
{
    public $total;
    public $currencies;

    public function __construct()
    {
        $this->total = 0;
        $this->currencies = array();
        return $this;
    }

    public function add($currency, $amount, $nativeAmount = null) : self {
        $this->total += $amount;
        $nativeAmount = $currency === 'rub' ? $amount : ($nativeAmount ?? 0);
        if(!isset($this->currencies[$currency])) {
            $this->currencies[$currency] = array('amount' => 0, 'currency' => $currency, 'convertedAmount' => 0);
        }
        $this->currencies[$currency]['amount'] += $nativeAmount;
        $this->currencies[$currency]['convertedAmount'] += $amount;
        return $this;
    }

    public function jsonSerialize()
    {
        return ['total' => $this->total, 'currencies' => $this->currencies];
    }

    public function setFromJson($json) {

        $data = json_decode($json);
        $this->currencies = array();
        if(!isset($data->currencies)) {
            return;
        }
        foreach($data->currencies as $currencyTag => $currency) {
            $this->add($currency->currency, $currency->convertedAmount, $currency->amount);
        }
    }
}
