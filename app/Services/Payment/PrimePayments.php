<?php

namespace App\Services\Payment;

use App\Models\Payments\Payout;
use Illuminate\Support\Facades\Http;

class PrimePayments extends BasePaysystem implements PaysystemContract
{
    protected $base_uri;
    protected $project_id;
    protected $secret1;
    protected $email;

    public function __construct($base_uri, $project_id, $secret1, $email)
    {
        $this->base_uri = $base_uri;
        $this->project_id = $project_id;
        $this->secret1 = $secret1;
        $this->email = $email;
    }

    public function send(string $address, float $amount, string $id, string $alias, array $options = []) : PaysystemResponse
    {
        try {
            $response = Http::asForm()->post($this->base_uri, [
                'action' => "initPayout",
                'project' => $this->project_id,
                'sum' => $amount,
                'currency' => 'RUB',
                'payWay' => $alias,
                'email' => $this->email,
                'innerID' => $address,
                'purse' => $address,
                'doc_number' => '5019999432',
                'sign' => md5($this->secret1."initPayout".$this->project_id.$amount."RUB".$alias.$this->email.$address)
            ]);
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "error", $response->body());
            }
            $data = json_decode($response->body());
            if($data->status !== "OK") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, "failed", $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $data->result->payout_id, Payout::STATUS_SENT, 0,$response->body());

        } catch (\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, null, Payout::STATUS_FAILED, 'fail', json_encode(["message" => $exception->getMessage()]));
        }
    }

    public function balance(): ?BalanceResource
    {
        try {
            $response = Http::asForm()->post($this->base_uri, [
                'action' => 'getProjectBalance',
                'project' => $this->project_id,
                'sign' => md5($this->secret1 . 'getProjectBalance' . $this->project_id)
            ]);
            if($response->status() !== 200) {
                return null;
            }
            $data = json_decode($response->body());
            if($data->status !== "OK") {
                return null;
            }
            return (new BalanceResource())->add('rub', $data->result->balance_RUB);
        } catch(\Exception $exception) {
            return null;
        }
    }

    function updateStatus($payout): PaysystemResponse
    {
        try {

            $response = Http::asForm()->post($this->base_uri, [
                'action' => 'getPayoutInfo',
                'project' => $this->project_id,
                'payoutID' => $payout->external_id,
                'sign' => md5($this->secret1 . 'getPayoutInfo' . $this->project_id . $payout->external_id)
            ]);

            $data = json_decode($response->body());
            if($response->status() !== 200) {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', $response->body());
            }
            if($data->status !== "OK") {
                return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', $response->body());
            }
            return new PaysystemResponse(PaysystemResponse::STATUS_OK, $payout->external_id, $this->translateStatus($data->result->pay_status), $data->result->pay_status, $response->body());
        } catch(\Exception $exception) {
            return new PaysystemResponse(PaysystemResponse::STATUS_FAIL, $payout->external_id, $payout->status, 'error', json_encode(['message' => $exception->getMessage()]));
        }
    }

    function translateStatus(string $status): string
    {
        switch ($status) {
            case "0" : return Payout::STATUS_SENT;
            case "1" : return Payout::STATUS_SUCCESS;
            case "-1" : return Payout::STATUS_FAILED;
            default : return Payout::STATUS_UNKNOWN;
        }
    }

    function getRates(): ?array
    {
        return null;
    }
}
