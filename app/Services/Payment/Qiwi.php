<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class Qiwi extends BasePaysystem implements PaysystemContract
{
    protected $base_uri;
    protected $api_key;
    protected $number;

    public function __construct()
    {
        $this->base_uri = config("services.paysystems.qiwi.base_uri");
        $this->api_key = config("services.paysystems.qiwi.api_key");
        $this->number = config("services.paysystems.qiwi.number");
    }

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        switch($alias) {
            default : $providerId = 99; break;
        }
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->api_key,
                "Accept" => "application/json",
                "Content-type" => "application/json"
            ])->asJson()->post(
                $this->base_uri . "sinap/api/v2/terms/".$providerId."/payments",
                [
                    "id" => strval($id),
                    "sum" => [
                        "amount" => strval($amount),
                        "currency" => "643"
                    ],
                    "paymentMethod" => [
                        "type" => "Account",
                        "accountId" => "643",
                    ],
                    "fields" => [
                        "account" => strval($address)
                    ]
                ]
            );
            info($response->status());
            info($response->body());
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', $response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    function balance(): ?BalanceResource
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->api_key,
                "Accept" => "application/json",
                "Content-type" => "application/json",
            ])->get($this->base_uri . "funding-sources/v2/persons/".$this->number."/accounts");

            if($response->status() !== 200) {
                return null;
            }
            $data = json_decode($response->body());

            foreach($data->accounts as $account) {
                if($account->defaultAccount) {
                    return (new BalanceResource())->add('rub', $account->balance->amount);
                }
            }
            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        return new PaysystemResponse(PaysystemResponse::STATUS_OK);
    }

    function translateStatus(string $status): string
    {
        return Payout::STATUS_UNKNOWN;
    }

    function getRates(): ?array
    {
        return null;
    }
}
