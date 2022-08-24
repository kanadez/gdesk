<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class Binance extends BasePaysystem implements PaysystemContract
{

    protected $base_uri;
    protected $api_key;
    protected $api_secret;

    public static $ALLOWED_CURRENCIES = ['USDT','BTC','LTC','ETH','BNB','XRP','DOGE','BUSD'];

    public function __construct($base_uri, $api_key, $api_secret)
    {
        $this->base_uri = $base_uri;
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;

    }

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        try {
            $requestUrl = $this->base_uri . "/sapi/v1/capital/withdraw/apply";

            $rates = $this->getRates();
            info("RATES");
            info($rates);
            info("RATE");
            info($rates[strtoupper($alias)]);
            info("Amount");
            $amount = round($amount * $rates[strtoupper($alias)], 6); // todo: 6 знаков - только для USDT
            info($amount);

            $params = [
                'timestamp' => round(microtime(true) * 1000),
                'recvWindow' => 30000,
                'coin' => $alias,
                'withdrawOrderId' => $id,
                'address' => $address,
                'amount' => $amount,
                'transactionFeeFlag' => true,
                'network' => 'TRX'
            ];


            switch($alias) {
                case "eth" : $params["network"] = "ETH"; break;
            }

            $params['signature'] = hash_hmac('sha256', http_build_query($params), $this->api_secret);
            $headers = [
                'X-MBX-APIKEY' => $this->api_key,
                'Content-type' => "application/x-www-form-urlencoded"
            ];

            $body = http_build_query($params);

            $response = Http::withHeaders($headers)->asForm()->post($requestUrl, $params);
            if($response->status()!==200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', $response->body());
            }

            $data = json_decode($response->body());
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->id, Payout::STATUS_SENT, "pending", $response->body());

        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }

    }

    function balance(): ?BalanceResource
    {
        try {
            $rates = $this->getRates();
            if(is_null($rates)) {
                return null;
            }

            $headers['X-MBX-APIKEY'] = $this->api_key;
            $params = [];
            $params['timestamp'] = round(microtime(true) * 1000);
            $params['recvWindow'] = 30 * 1000;
            $params['signature'] = hash_hmac('sha256', http_build_query($params), $this->api_secret);
            $requestUrl = join("?", [$this->base_uri."/api/v3/account", http_build_query($params)]);
            $response = Http::withHeaders($headers)->get($requestUrl);
            if($response->status()!==200) {
                return null;
            }
            $balances = json_decode($response->body());
            $balance = new BalanceResource();
            foreach ($balances->balances as $balanceItem) {
                if(in_array($balanceItem->asset, self::$ALLOWED_CURRENCIES)) {
                    $balance->add($balanceItem->asset, $balanceItem->free / $rates[$balanceItem->asset], $balanceItem->free);
                }
            }
            return $balance;
        } catch(\Exception $exception) {
            return null;
        }
    }

    function getRates() : ?array {
        try {
            $rates = [];
            foreach(self::$ALLOWED_CURRENCIES as $currency) {
                $response = Http::get($this->base_uri."/api/v3/avgPrice?symbol=".$currency."RUB");
                if($response->status()!==200) {
                    continue;
                }
                $rate = json_decode($response->body());
                $rates[$currency] = $rate->price ? 1/$rate->price : 0;
            }
            return $rates;

        } catch(\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {
            $params = [
                'timestamp' =>  round(microtime(true) * 1000),
                'recvWindow' => 30000,
                'withdrawOrderId' => $payout->id
            ];
            $params['signature'] = hash_hmac('sha256', http_build_query($params), $this->api_secret);
            $headers = [
                'X-MBX-APIKEY' => $this->api_key,
            ];
            $requestUrl = join("?", [$this->base_uri."/sapi/v1/capital/withdraw/history", http_build_query($params)]);

            $response = Http::withHeaders($headers)->get($requestUrl);
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', $response->body());
            }
            $data = json_decode($response->body());
            $data = $data[0];
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->status), $data->status, $response->body());

        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        switch($status) {
            case "0" : return Payout::STATUS_SENT; // 0:Email Sent
            case "1" : return Payout::STATUS_FAILED; // 1:Cancelled
            case "2" : return Payout::STATUS_SENT; // 2:Awaiting Approval
            case "3" : return Payout::STATUS_FAILED; //  3:Rejected
            case "4" : return Payout::STATUS_SENT; // 4:Processing
            case "5" : return Payout::STATUS_FAILED; // 5:Failure
            case "6" : return Payout::STATUS_SUCCESS; // 6:Completed
            default : return Payout::STATUS_SENT;
        }
    }
}
