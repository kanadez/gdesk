<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class Enot extends BasePaysystem implements PaysystemContract
{
    protected $base_uri;
    protected $api_key;
    protected $email;

    public function __construct($base_uri, $api_key, $email)
    {
        $this->base_uri = $base_uri;
        $this->api_key = $api_key;
        $this->email = $email;
    }

    public function send(string $address, float $amount, string $id, string $alias, array $options = []) : PaysystemResponse
    {
        try {
            $response = Http::get($this->base_uri."payoff?".http_build_query([
                    "api_key" => $this->api_key,
                    "email" => $this->email,
                    "service" => $alias,
                    "wallet" => $address,
                    "amount" => $amount,
                    "orderid" => $id
                ]));
            $data = json_decode($response->body());
            if($data->status !== "success") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->id, Payout::STATUS_SENT, "wait", $response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    public function balance(): ?BalanceResource
    {
        try {
            $response = Http::get($this->base_uri."balance?api_key=".$this->api_key."&email=".$this->email);
            $data = json_decode($response->body());
            if($data->status === "success") {
                return (new BalanceResource())->add('rub', floatval($data->balance));
            }
            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function updateStatus($payout) : PaysystemResponse
    {
        try {
            $response = Http::get($this->base_uri . "payoff-info?".http_build_query([
                    'api_key' => $this->api_key,
                    'email' => $this->email,
                    'id' => $payout->external_id
                ]));
            $data = json_decode($response->body());
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id,$payout->status, 'error', $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->status), $data->status,$response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        switch($status) {
            case "success" : return Payout::STATUS_SUCCESS;
            case "wait" : return Payout::STATUS_SENT;
            case "fail" : return Payout::STATUS_FAILED;
            default : return Payout::STATUS_UNKNOWN;
        }
    }

    function getRates(): ?array
    {
        return null;
    }
}
