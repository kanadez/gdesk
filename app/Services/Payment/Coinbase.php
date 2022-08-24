<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use App\Models\Payments\Paysystem;
use Illuminate\Support\Facades\Http;

class Coinbase extends BasePaysystem implements PaysystemContract
{

    protected $base_uri;
    protected $api_key;
    protected $secret;
    protected $accounts;

    public function __construct()
    {
        $this->base_uri = config("services.paysystems.coinbase.base_uri");
        $this->api_key = config("services.paysystems.coinbase.api_key");
        $this->secret = config("services.paysystems.coinbase.secret");
        $this->accounts = config("services.paysystems.coinbase.accounts");
    }

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        if(!isset($this->accounts[$alias])) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL,null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => "Способ выплаты не найден"]));
        }
        $accountId = $this->accounts[$alias];
        try {
            $response = $this->request('POST','accounts/'.$accountId.'/transactions',[
                'type' => 'send',
                'to' => $address,
                'amount' => $amount,
                'currency' => 'RUB',
            ]);
            if($response->status() !== 201) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', $response->body());
            }
            $data = json_decode($response->body());
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->data->id, $this->translateStatus($data->data->status), $data->data->status, $response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    function balance(): ?BalanceResource
    {
        try {
            $response = $this->request("GET", "exchange-rates?currency=RUB");
            $rates = json_decode($response->body())->data->rates;
            $response = $this->request("GET", "accounts");
            if($response->status() !== 200) {
                return null;
            }
            $data = json_decode($response->body());
            $accounts = $data->data;
            while(!is_null($data->pagination->next_starting_after)) {
                $response = $this->request("GET", "accounts?starting_after=".$data->pagination->next_starting_after);
                $data = json_decode($response->body());
                $accounts = array_merge($accounts, $data->data);
            }
            $balance = new BalanceResource();
            foreach($accounts as $account) {
                if($account->balance->amount > 0) {
                    $balance = $balance->add($account->balance->currency, $account->balance->amount / $rates->{$account->balance->currency}, $account->balance->amount);
                }
            }
            return $balance;

        } catch (\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {
            if(!isset($this->accounts[$payout->alias])) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL,$payout->external_id, $payout->status, 'error', json_encode(["message" => "Способ выплаты не найден"]));
            }
            $accountId = $this->accounts[$payout->alias];
            $response = $this->request('GET', 'accounts/'.$accountId.'/transactions/'.$payout->external_id);
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', $response->body());
            }
            $data = json_decode($response->body());
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->data->status), $data->data->status, $response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        switch($status) {
            case "pending" : return Payout::STATUS_SENT;
            case "completed" : return Payout::STATUS_SUCCESS;
            case "failed" : return Payout::STATUS_FAILED;
            case "expired" : return Payout::STATUS_FAILED;
            case "canceled" : return Payout::STATUS_FAILED;
            case "waiting_for_signature" : return Payout::STATUS_SENT;
            case "waiting_for_clearing" : return Payout::STATUS_SENT;
            default : return Payout::STATUS_UNKNOWN;
        }
    }

    public function request($method, $endpoint, $params = []) {
        $path = "/v2/".$endpoint;
        $timestamp = time();
        $body = !empty($params) ? json_encode($params) : "";

        $request =  Http::withHeaders([
            'CB-ACCESS-KEY' => $this->api_key,
            'CB-ACCESS-SIGN' => hash_hmac('sha256', $timestamp.$method.$path.$body, $this->secret),
            'CB-ACCESS-TIMESTAMP' => $timestamp,
        ])->asJson();

        if($method === "GET") {
            return $request->get($this->base_uri . $endpoint);
        }
        return $request->post($this->base_uri . $endpoint, $params);
    }

    function getRates(): ?array
    {
        $response = $this->request("GET", "exchange-rates?currency=RUB");
        return json_decode($response->body())->data->rates;
    }
}
