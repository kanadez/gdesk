<?php

namespace App\Services\Payment;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Stripe extends BasePaysystem implements PaysystemContract
{
    protected $public_key;
    protected $secret_key;
    protected $webhook_key;

    public function __construct()
    {
        $this->public_key = config("services.paysystems.stripe.public_key");
        $this->secret_key = config("services.paysystems.stripe.secret_key");
        $this->webhook_key = config("services.paysystems.stripe.webhook_key");
    }

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        // TODO: Implement send() method.
    }

    function balance(): ?BalanceResource
    {
        try {
            $stripe = new \Stripe\StripeClient($this->secret_key);
            $balanceResponse = $stripe->balance->retrieve();
            $balance = new BalanceResource();
            $rates = $this->getRates();
            foreach($balanceResponse->pending as $balanceItem) {
                $balance->add($balanceItem->currency, $balanceItem->amount / 100 * $rates[$balanceItem->currency], $balanceItem->amount / 100);
            }
            return $balance;
        } catch(\Exception $exception) {
            dd($exception->getMessage());
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        // TODO: Implement updateStatus() method.
    }

    function translateStatus(string $status): string
    {
        // TODO: Implement translateStatus() method.
    }

    function getRates(): ?array
    {
        //$response = Http::get('https://www.cbr.ru/scripts/XML_daily.asp?date_req='.Carbon::now()->format('d/m/Y'));

        $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req='.Carbon::now()->format('d/m/Y');
        $context = stream_context_create(
            [
                'http' => [
                    'max_redirects' => 101,
                ],
            ]
        );

        $response = file_get_contents($url, false, $context);
        $file = simplexml_load_string($response);
        $currencies = [
            [
                'currency' => 'usd',
                'code' => 'R01235'
            ],
            [
                'currency' => 'eur',
                'code' => 'R01239'
            ],
        ];
        $rates = [];
        foreach($currencies as $currency) {
            $xml = $file->xpath("//Valute[@ID='".$currency['code']."']");
            $rate = round(floatval(Str::replaceFirst(',', '.', strval($xml[0]->Value))),2);
            $rates[$currency['currency']] = $rate;
        }
        return $rates;
    }
}
