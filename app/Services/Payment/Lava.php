<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class Lava extends BasePaysystem implements PaysystemContract
{

    protected $base_uri;
    protected $api_token;
    protected $wallet_rub;
    protected $order_prefix;

    public function __construct()
    {
        $this->base_uri = config("services.paysystems.lava.base_uri");
        $this->api_token = config("services.paysystems.lava.api_key");
        $this->wallet_rub = config("services.paysystems.lava.wallet_rub");
        $this->order_prefix = config("services.paysystems.lava.order_prefix");
    }

    public function send(string $address, float $amount, string $id, string $alias, array $options = []) : PaysystemResponse
    {
        try {
            $response = Http::asForm()->withHeaders(["Authorization" => $this->api_token])->post($this->base_uri . "withdraw/create", [
                'account' => $this->wallet_rub,
                'amount' => $amount,
                'order_id' => $this->order_prefix . $id,
                'subtract' => 1, // списывать комиссию с баланса, 0 - с получателя
                'service' => $alias,
                'wallet_to' => $address,
            ]);
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "error", $response->body());
            }
            $data = json_decode($response->body());
            if($data->status !== "success") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "failed", $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->id, Payout::STATUS_SENT, 'pending',$response->body());

        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    public function balance(): ?BalanceResource
    {
        try {
            $response = Http::withHeaders(["Authorization" => $this->api_token])->get($this->base_uri."wallet/list");
            if($response->status() !== 200) {
                return null;
            }
            $data = json_decode($response->body());
            foreach($data as $wallet) {
                if($wallet->account === $this->wallet_rub) {
                    return (new BalanceResource())->add('rub', $wallet->balance);
                }
            }
            return null;

        } catch (\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {
            $response = Http::asForm()->withHeaders(["Authorization" => $this->api_token])->post($this->base_uri."withdraw/info", [
                "id" => $payout->external_id
            ]);

            $data = json_decode($response->body());
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id,$payout->status, 'error', $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->status), $data->status, $response->body());
        } catch(\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        switch($status) {
            case "cancel" : return Payout::STATUS_FAILED;
            case "pending" : return Payout::STATUS_SENT;
            case "success" : return Payout::STATUS_SUCCESS;
            default : return Payout::STATUS_UNKNOWN;
        }
    }

    function getRates(): ?array
    {
        return null;
    }
}
