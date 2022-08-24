<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class Interkassa extends BasePaysystem implements PaysystemContract
{
    protected $base_uri;
    protected $login;
    protected $password;
    protected $business_account_id;
    protected $wallet_rub;
    protected $wallet_eur;

    public function __construct()
    {
        $this->base_uri = config("services.paysystems.interkassa.base_uri");
        $this->login = config("services.paysystems.interkassa.login");
        $this->password = config("services.paysystems.interkassa.password");
        $this->business_account_id = config("services.paysystems.interkassa.business_account_id");
        $this->wallet_rub = config("services.paysystems.interkassa.wallet_rub");
        $this->wallet_eur = config("services.paysystems.interkassa.wallet_eur");
    }

    public function send(string $address, float $amount, string $id, string $alias, array $options = []) : PaysystemResponse
    {

        $params = [
            'purseId' => $this->wallet_rub,
            'calcKey' => 'psPayeeAmount',
            'action' => 'process',
            'paymentNo' => $id,
            'currency' => 'rub',
        ];

        try {

            switch($alias) {
                case "card" :
                    $params['amount'] = $amount;
                    $params['details'] = ['card' => $address];
                    $params['method'] = 'card';
                    $params['useShortAlias'] = true;
                    break;
                case "card_eur" :
                    $params["purseId"] = $this->wallet_eur;
                    $params['amount'] = $amount;
                    $params['details'] = ['card' => $address];
                    $params['method'] = 'card';
                    $params['useShortAlias'] = true;
                    break;
                case "wmz" :
                    $params['amount'] = $this->getAmountFor($amount, 'usd');
                    unset($params['currency']);
                    $params['paywayId'] = "webmoney_webmoney_transfer_wmz";
                    $params['details'] = ['purse' => $address];
                    break;
                case "wmz_eur" :
                    $params["purseId"] = $this->wallet_eur;
                    $params['amount'] = $this->getAmountFor($amount, 'usd');
                    unset($params['currency']);
                    $params['paywayId'] = "webmoney_webmoney_transfer_wmz";
                    $params['details'] = ['purse' => $address];
                    break;
                case "usdt":
                    $params['amount'] = $this->getAmountFor($amount, 'usdt');
                    unset($params['currency']);
                    $params['details'] = ['payee' => $address];
                    $params['paywayId'] = "crypto_triplec_transfer_usdttrc";
                    break;
                case "usdt_eur":
                    $params["purseId"] = $this->wallet_eur;
                    $params['amount'] = $this->getAmountFor($amount, 'usdt');
                    unset($params['currency']);
                    $params['details'] = ['payee' => $address];
                    $params['paywayId'] = "crypto_triplec_transfer_usdttrc";
                    break;
            }

            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic '.base64_encode($this->login.":".$this->password),
                'Ik-Api-Account-Id' => $this->business_account_id
            ])->post($this->base_uri . "withdraw", $params);

            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "error", $response->body());
            }
            $data = json_decode($response->body());
            if($data->status !== "ok") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "failed", $response->body());
            }

            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->data->withdraw->id, $this->translateStatus($data->data->withdraw->state), $data->data->withdraw->state, $response->body());
        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    public function balance(): ?BalanceResource
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.base64_encode($this->login.":".$this->password),
                'Ik-Api-Account-Id' => $this->business_account_id
            ])->get($this->base_uri . "purse");
            if($response->status() !== 200) {
                return null;
            }
            $data = json_decode($response->body());
            if($data->status !== "ok") {
                return null;
            }
            $rates = $this->getRates();

            $balance = new BalanceResource();
            $balance->add('rub', floatval($data->data->{$this->wallet_rub}->balance));
            $balance->add('eur', $rates['EUR'] * floatval($data->data->{$this->wallet_eur}->balance),floatval($data->data->{$this->wallet_eur}->balance));
            return $balance;
        } catch (\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.base64_encode($this->login.":".$this->password),
                'Ik-Api-Account-Id' => $this->business_account_id
            ])->get($this->base_uri . "withdraw/".$payout->external_id);

            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id,$payout->status, 'error', $response->body());
            }
            $data = json_decode($response->body());

            if($data->status !== "ok") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id,$payout->status, 'error', $response->body());
            }

            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->data->state), $data->data->state, $response->body());

        } catch(\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
        return new PaysystemResponse(PaysystemResponse::STATUS_FAIL);

    }

    public function translateStatus(string $status): string
    {
        switch($status) {
            case 1 : return Payout::STATUS_SENT;
            case 2 : return Payout::STATUS_SENT;
            case 3 : return Payout::STATUS_FAILED;
            case 4 : return Payout::STATUS_SENT;
            case 5 : return Payout::STATUS_SENT;
            case 6 : return Payout::STATUS_SENT;
            case 7 : return Payout::STATUS_SENT;
            case 8 : return Payout::STATUS_SUCCESS;
            case 9 : return Payout::STATUS_FAILED;
            case 12 : return Payout::STATUS_SENT;
            case 20 : return Payout::STATUS_FAILED;
        }
        return Payout::STATUS_UNKNOWN;
    }

    public function getAmountFor($rub_amount, $currency) {
        $response = Http::get('https://api.interkassa.com/v1/currency');
        $data = json_decode($response->body());
        $conversionKey = $this->getConversionKey($currency);
        return $rub_amount * $this->calcAverageRate($data->data->RUB->{$conversionKey});
    }

    private function calcAverageRate($conversionRecord) {
        return ($conversionRecord->in + $conversionRecord->out) / 2;
    }

    function getRates(): ?array
    {
        $response = Http::get('https://api.interkassa.com/v1/currency');
        $data = json_decode($response->body());
        $currencies = ['EUR','USD','USDT'];
        $rates = [];
        foreach($currencies as $currency) {
            $conversionKey = $this->getConversionKey($currency);
            $rates[$currency] = $this->calcAverageRate($data->data->{$conversionKey}->RUB);
        }
        return $rates;
    }

    private function getConversionKey($currency) {
        switch(strtolower($currency)) {
            case 'usd' : $conversionKey = 'USD'; break;
            case 'usdt' : $conversionKey = 'USDTTRC'; break;
            case 'eur' : $conversionKey = 'EUR'; break;
            default : throw new \Exception('Could not find currency '.$currency);
        }
        return $conversionKey;
    }
}
