<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EsewaService
{
    protected string $merchantId;
    protected string $secretKey;
    protected bool   $sandbox;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('payment.esewa.merchant_id');
        $this->secretKey  = config('payment.esewa.secret_key');
        $this->sandbox    = config('payment.esewa.sandbox', true);
        $this->baseUrl    = $this->sandbox
            ? 'https://rc-epay.esewa.com.np'
            : 'https://epay.esewa.com.np';
    }

    public function getPaymentUrl(): string
    {
        return $this->baseUrl . '/api/epay/main/v2/form';
    }

    public function generateSignature(string $totalAmount, string $transactionUuid, string $productCode): string
    {
        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
        $hash    = hash_hmac('sha256', $message, $this->secretKey, true);
        return base64_encode($hash);
    }

    public function buildFormData(array $params): array
    {
        $transactionUuid = $params['transaction_uuid'];
        $serviceCharge   = number_format((float) setting('esewa_service_charge', 0), 2, '.', '');
        $deliveryCharge  = number_format((float) setting('esewa_delivery_charge', 0), 2, '.', '');
        $taxAmount       = number_format(
            (float) setting('tax_percentage', 0) / 100 * (float) $params['amount'],
            2, '.', ''
        );
        $totalWithCharges = number_format(
            (float) $params['total_amount'] + (float) $serviceCharge + (float) $deliveryCharge + (float) $taxAmount,
            2, '.', ''
        );
        $productCode = $this->merchantId;

        return [
            'amount'                  => number_format($params['amount'], 2, '.', ''),
            'failure_url'             => $params['failure_url'],
            'product_delivery_charge' => $deliveryCharge,
            'product_service_charge'  => $serviceCharge,
            'product_code'            => $productCode,
            'signature'               => $this->generateSignature($totalWithCharges, $transactionUuid, $productCode),
            'signed_field_names'      => 'total_amount,transaction_uuid,product_code',
            'success_url'             => $params['success_url'],
            'tax_amount'              => $taxAmount,
            'total_amount'            => $totalWithCharges,
            'transaction_uuid'        => $transactionUuid,
        ];
    }

    public function verify(string $encodedData): array
    {
        $verifyUrl = $this->baseUrl . '/api/epay/transaction/statuscheck';
        $decoded   = json_decode(base64_decode($encodedData), true);

        if (!$decoded) {
            return ['success' => false, 'message' => 'Invalid response data'];
        }

        $response = Http::get($verifyUrl, [
            'product_code'     => $this->merchantId,
            'total_amount'     => $decoded['total_amount'] ?? '',
            'transaction_uuid' => $decoded['transaction_uuid'] ?? '',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (($data['status'] ?? '') === 'COMPLETE') {
                return ['success' => true, 'data' => $data];
            }
        }

        return ['success' => false, 'message' => 'Payment verification failed', 'data' => $decoded];
    }
}
