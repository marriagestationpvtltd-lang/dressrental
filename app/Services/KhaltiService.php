<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class KhaltiService
{
    protected string $secretKey;
    protected string $publicKey;
    protected bool   $sandbox;
    protected string $baseUrl;

    public function __construct()
    {
        // Prefer database settings; fall back to config / env when not set.
        $dbSecretKey = Setting::getRaw('khalti_secret_key');
        $dbPublicKey = Setting::getRaw('khalti_public_key');
        $dbSandbox   = Setting::getRaw('khalti_sandbox');

        $this->secretKey = ($dbSecretKey !== null && $dbSecretKey !== '')
            ? $dbSecretKey
            : config('payment.khalti.secret_key');

        $this->publicKey = ($dbPublicKey !== null && $dbPublicKey !== '')
            ? $dbPublicKey
            : config('payment.khalti.public_key');

        $this->sandbox = ($dbSandbox !== null && $dbSandbox !== '')
            ? filter_var($dbSandbox, FILTER_VALIDATE_BOOLEAN)
            : config('payment.khalti.sandbox', true);

        $this->baseUrl = $this->sandbox
            ? 'https://a.khalti.com'
            : 'https://khalti.com';
    }

    public function initiatePayment(array $params): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post($this->baseUrl . '/api/v2/epayment/initiate/', [
            'return_url'      => $params['return_url'],
            'website_url'     => config('app.url'),
            'amount'          => (int) round($params['amount'] * 100), // in paisa
            'purchase_order_id' => $params['order_id'],
            'purchase_order_name' => $params['order_name'],
            'customer_info'   => [
                'name'  => $params['customer_name'] ?? '',
                'email' => $params['customer_email'] ?? '',
                'phone' => $params['customer_phone'] ?? '',
            ],
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        return ['success' => false, 'message' => 'Failed to initiate Khalti payment', 'errors' => $response->json()];
    }

    public function verify(string $pidx): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post($this->baseUrl . '/api/v2/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (($data['status'] ?? '') === 'Completed') {
                return ['success' => true, 'data' => $data];
            }
        }

        return ['success' => false, 'message' => 'Khalti payment not completed', 'data' => $response->json()];
    }
}
