<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class EcocashPayment implements PaymentInterface
{
    private string $apiKey;
    private string $mode; // sandbox|live
    private string $currency;
    private string $defaultReason;

    public function __construct(array $paymentData)
    {
        $this->apiKey = $paymentData['ecocash_api_key'] ?? '';
        $this->mode = ($paymentData['ecocash_mode'] ?? 'sandbox') === 'live' ? 'live' : 'sandbox';
        $this->currency = $paymentData['ecocash_currency'] ?? 'USD';
        $this->defaultReason = $paymentData['ecocash_reason'] ?? 'Payment';
    }

    public function createPaymentIntent($amount, $customMetaData)
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('Ecocash API key is missing');
        }
        if (empty($customMetaData['customer_msisdn'] ?? null)) {
            throw new RuntimeException('customer_msisdn is required for Ecocash');
        }

        $baseUrl = 'https://developers.ecocash.co.zw/api/ecocash_pay';
        $path = $this->mode === 'sandbox'
            ? '/api/v2/payment/instant/c2b/sandbox'
            : '/api/v2/payment/instant/c2b/live';

        $sourceReference = (string) Str::uuid();
        $payload = [
            'customerMsisdn' => $customMetaData['customer_msisdn'],
            'amount' => (float) $amount,
            'reason' => $customMetaData['reason'] ?? $this->defaultReason,
            'currency' => $customMetaData['currency'] ?? $this->currency,
            'sourceReference' => $sourceReference,
        ];

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($baseUrl . $path, $payload);

        if (!$response->ok()) {
            throw new RuntimeException('Ecocash request failed: ' . $response->status() . ' ' . $response->body());
        }

        return [
            'ecocash_reference' => $sourceReference,
            'gateway_response' => $response->json(),
        ];
    }

    public function createAndFormatPaymentIntent($amount, $customMetaData): array
    {
        $res = $this->createPaymentIntent($amount, $customMetaData);
        return $this->formatPaymentIntent(
            $res['ecocash_reference'],
            $amount,
            $customMetaData['currency'] ?? $this->currency,
            // Ecocash returns 200 for accepted; treat as pending until verified
            'pending',
            $customMetaData,
            $res['gateway_response']
        );
    }

    public function retrievePaymentIntent($paymentId): array
    {
        // Not implemented: requires transactions lookup endpoint
        throw new RuntimeException('Ecocash retrievePaymentIntent not implemented');
    }

    public function minimumAmountValidation($currency, $amount)
    {
        return $amount;
    }

    public function formatPaymentIntent($id, $amount, $currency, $status, $metadata, $paymentIntent): array
    {
        return [
            'id' => $id,
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => $metadata,
            'status' => $status,
            'payment_gateway_response' => $paymentIntent,
        ];
    }
}
