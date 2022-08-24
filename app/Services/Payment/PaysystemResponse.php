<?php

namespace App\Services\Payment;

class PaysystemResponse implements \JsonSerializable
{
    public const STATUS_OK = "OK";
    public const STATUS_FAIL = "FAIL";

    public $status;
    public $externalId;
    public $payoutStatus;
    public $payoutExternalStatus;
    public $payload;

    public function __construct(string $status, $externalId = null, $payoutStatus = null, $payoutExternalStatus = null, string $payload = null)
    {
        $this->status = $status;
        $this->externalId = $externalId;
        $this->payoutStatus = $payoutStatus;
        $this->payoutExternalStatus = $payoutExternalStatus;
        $this->payload = $payload;
    }

    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'external_id' => $this->externalId,
            'payout_status' => $this->payoutStatus,
            'paysystem_status' => $this->payoutExternalStatus,
            'payload' => $this->payload
        ];
    }
}
