<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Paypalych extends BasePaysystem implements PaysystemContract
{
    protected $api_key;
    protected $shop_id;
    protected $base_uri;

    public function __construct()
    {
        $this->base_uri = "https://paypalych.com/";
        $this->api_key = config("services.paysystems.paypalych.api_key");
        $this->shop_id = config("services.paysystems.paypalych.shop_id");
    }

    function send(string $address, float $amount, string $id, string $alias, array $options = []): PaysystemResponse
    {
        try {
            $params = [
                'amount' => $amount,
                'currency' => 'RUB',
                'account_type' => $alias,
                'account_identifier' => $address,
                'card_holder' => $options['card_holder'] ?? 'CARD HOLDER',
            ];

            $response = Http::withHeaders(["Authorization" => "Bearer ".$this->api_key])->asForm()->post($this->base_uri."api/v1/payout/regular/create", $params);
            if($response->status() !== 200) {
                Log::channel('paysystem')->info("[PayPalych] Send failed, HTTP status is not 200: ".$response->status());
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "error", $response->body());
            }

            $data = json_decode($response->body());


            $data = json_decode($response->body());

            if($data->success !== "true" && $data->success !== true) {
                Log::channel('paysystem')->info("[PayPalych] Send failed status is not true: ".$data->success);
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "error", $response->body());
            }

            $ids = collect($data->data)->pluck("id")->join(":");
            $statuses = collect($data->data)->pluck("status")->join(":");

            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $ids, Payout::STATUS_SENT, $statuses,$response->body());

        } catch (\Exception $exception) {
            Log::channel('paysystem')->info("[PayPalych] Exception: ".$exception->getMessage());
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    function balance(): ?BalanceResource
    {
        try {
            $response = Http::withHeaders(["Authorization" => "Bearer ".$this->api_key])->get($this->base_uri."api/v1/merchant/balance");
            if($response->status() !== 200) {
                Log::channel('paysystem')->info("[PayPalych] Status is not 200: ".$response->status());
            }

            $data = json_decode($response->body());
            if($data->success !== "true" && $data->success !== true) {
                Log::channel('paysystem')->info("[PayPalych] Status is not success");
                Log::channel('paysystem')->info($response->body());
                return null;
            }
            $balance = new BalanceResource();
            $rates = $this->getRates();
            foreach($data->balances as $balanceItem) {
                $balance->add($balanceItem->currency, $balanceItem->currency === 'RUB' ? $balanceItem->balance_available : $rates[$balanceItem->currency] * $balanceItem->balance_available, $balanceItem->balance_available);
            }
            return $balance;
        } catch (\Exception $exception) {
            Log::channel('paysystem')->info("[PayPalych] balance error: ".$exception->getMessage());
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {
            $ids = explode(":", $payout->external_id);
            $statuses = [];
            $responses = [];
            foreach($ids as $id) {
                $response = Http::withHeaders(["Authorization" => "Bearer ".$this->api_key])->get($this->base_uri."api/v1/payout/status",http_build_query(['id' => $id]));
                $responses[] = json_decode($response->body());
                if($response->status() !== 200) {
                    return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode($responses));
                }
                $data = json_decode($response->body());
                $statuses[] = $data->status;
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus(implode(":", $statuses)), implode(":", $statuses), json_encode($responses));
        } catch(\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        $statuses = explode(":", $status);
        $success = 0;
        $errors = 0;
        $pending = 0;
        foreach($statuses as $status) {
            if($status === 'SUCCESS') $success++; elseif(in_array($status, ['FAIL','ERROR','DECLINED'])) $errors++; else $pending++;
        }
        if($success === count($statuses)) return Payout::STATUS_SUCCESS;
        if($errors === count($statuses)) return Payout::STATUS_FAILED;
        if($pending + $success === count($statuses)) return Payout::STATUS_PENDING;
        if($pending + $errors === count($statuses)) return Payout::STATUS_PENDING;
        if($success + $errors === count($statuses)) return Payout::STATUS_PARTIAL;
        return Payout::STATUS_SENT;
    }

    function getRates(): ?array
    {
        //$url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req='.Carbon::now()->format('d/m/Y');
        $url = 'https://www.cbr-xml-daily.ru/daily.xml?date_req='.Carbon::now()->format('d/m/Y');
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
