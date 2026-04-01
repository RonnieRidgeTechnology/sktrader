<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    /**
     * Checkout shows PayPal when REST credentials exist. The admin checkbox only *disables*
     * PayPal when explicitly off ('0'), so a missing flag still works after saving Client ID + Secret.
     */
    public static function isConfigured(): bool
    {
        $id = trim((string) Setting::get('paypal_client_id', ''));
        $secret = trim((string) Setting::get('paypal_client_secret', ''));
        if ($id === '' || $secret === '') {
            return false;
        }

        $enabledFlag = Setting::get('paypal_enabled');
        if ($enabledFlag === '0' || $enabledFlag === 'false' || $enabledFlag === false || $enabledFlag === 0) {
            return false;
        }

        return true;
    }

    protected function baseUrl(): string
    {
        $sandbox = filter_var(Setting::get('paypal_sandbox', '0'), FILTER_VALIDATE_BOOLEAN);

        return $sandbox
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    public function getAccessToken(): ?string
    {
        $id = Setting::get('paypal_client_id');
        $secret = Setting::get('paypal_client_secret');
        $response = Http::asForm()
            ->withBasicAuth((string) $id, (string) $secret)
            ->post($this->baseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        if (! $response->successful()) {
            Log::warning('PayPal OAuth failed', ['status' => $response->status(), 'body' => $response->body()]);

            return null;
        }

        return $response->json('access_token');
    }

    /**
     * ISO 4217 code for the PayPal charge (store may use a different display currency).
     */
    protected function currencyForOrder(Order $order): string
    {
        $fromSettings = Setting::get('paypal_currency');
        if (is_string($fromSettings) && strlen($fromSettings) === 3) {
            return strtoupper($fromSettings);
        }
        $c = strtoupper((string) ($order->currency ?: 'USD'));
        if (strlen($c) === 3 && ctype_alpha($c)) {
            return $c;
        }

        return 'USD';
    }

    /**
     * @return array{success: bool, approval_url?: string, paypal_order_id?: string, message?: string}
     */
    public function createCheckoutOrder(Order $order, string $returnUrl, string $cancelUrl): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return ['success' => false, 'message' => 'Could not connect to PayPal. Check credentials in Settings.'];
        }

        $currency = $this->currencyForOrder($order);
        $value = number_format((float) $order->total, 2, '.', '');

        $brand = Setting::get('site_name') ?: config('app.name', 'Store');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->number,
                'custom_id' => $order->number,
                'description' => 'Order '.$order->number,
                'amount' => [
                    'currency_code' => $currency,
                    'value' => $value,
                ],
            ]],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => mb_substr((string) $brand, 0, 127),
                'landing_page' => 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
            ],
        ];

        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ])
            ->post($this->baseUrl().'/v2/checkout/orders', $payload);

        if (! $response->successful()) {
            Log::warning('PayPal create order failed', ['status' => $response->status(), 'body' => $response->body()]);

            return ['success' => false, 'message' => 'PayPal could not start the payment. Try again or choose another method.'];
        }

        $data = $response->json();
        $paypalOrderId = $data['id'] ?? null;
        $approvalUrl = null;
        foreach ($data['links'] ?? [] as $link) {
            if (($link['rel'] ?? '') === 'approve') {
                $approvalUrl = $link['href'];
                break;
            }
        }

        if (! $paypalOrderId || ! $approvalUrl) {
            return ['success' => false, 'message' => 'Invalid response from PayPal.'];
        }

        return [
            'success' => true,
            'paypal_order_id' => $paypalOrderId,
            'approval_url' => $approvalUrl,
        ];
    }

    /**
     * @return array{success: bool, captured?: bool, capture_id?: string, message?: string}
     */
    public function captureOrder(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return ['success' => false, 'message' => 'Could not connect to PayPal.'];
        }

        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ])
            ->post($this->baseUrl().'/v2/checkout/orders/'.rawurlencode($paypalOrderId).'/capture');

        if ($response->status() === 422) {
            $data = $response->json();
            $issue = $data['details'][0]['issue'] ?? '';
            if ($issue === 'ORDER_ALREADY_CAPTURED') {
                return $this->extractCaptureFromOrder($paypalOrderId, $token);
            }
        }

        if (! $response->successful()) {
            Log::warning('PayPal capture failed', ['status' => $response->status(), 'body' => $response->body()]);

            return ['success' => false, 'message' => 'Payment could not be completed. You were not charged.'];
        }

        $data = $response->json();
        if (($data['status'] ?? '') !== 'COMPLETED') {
            return ['success' => false, 'message' => 'Payment was not completed.', 'captured' => false];
        }

        $captureId = null;
        foreach ($data['purchase_units'][0]['payments']['captures'] ?? [] as $cap) {
            if (($cap['status'] ?? '') === 'COMPLETED') {
                $captureId = $cap['id'] ?? null;
                break;
            }
        }

        return [
            'success' => true,
            'captured' => true,
            'capture_id' => $captureId ?? $paypalOrderId,
        ];
    }

    /**
     * @return array{success: bool, captured?: bool, capture_id?: string, message?: string}
     */
    protected function extractCaptureFromOrder(string $paypalOrderId, string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->withHeaders(['Accept' => 'application/json'])
            ->get($this->baseUrl().'/v2/checkout/orders/'.rawurlencode($paypalOrderId));

        if (! $response->successful()) {
            return ['success' => true, 'captured' => true, 'capture_id' => $paypalOrderId];
        }

        $data = $response->json();
        $captureId = null;
        foreach ($data['purchase_units'][0]['payments']['captures'] ?? [] as $cap) {
            if (($cap['status'] ?? '') === 'COMPLETED') {
                $captureId = $cap['id'] ?? null;
                break;
            }
        }

        return [
            'success' => true,
            'captured' => true,
            'capture_id' => $captureId ?? $paypalOrderId,
        ];
    }
}
