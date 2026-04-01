<?php

namespace App\Services;

use App\Models\Setting;
use ZynlePay\Client;
use ZynlePay\Exception\ApiException;
use ZynlePay\Exception\InvalidConfigurationException;
use ZynlePay\MomoDeposit;

class ZynlePayService
{
    public static function isConfigured(): bool
    {
        $merchantId = Setting::get('zynlepay_merchant_id');
        $apiId = Setting::get('zynlepay_api_id');
        $apiKey = Setting::get('zynlepay_api_key');
        $channel = Setting::get('zynlepay_channel');

        return ! empty($merchantId) && ! empty($apiId) && ! empty($apiKey) && ! empty($channel);
    }

    /**
     * Build ZynlePay client from stored settings.
     *
     * @throws InvalidConfigurationException
     */
    public function getClient(): Client
    {
        $merchantId = Setting::get('zynlepay_merchant_id');
        $apiId = Setting::get('zynlepay_api_id');
        $apiKey = Setting::get('zynlepay_api_key');
        $channel = Setting::get('zynlepay_channel') ?: 'momo';
        $serviceId = Setting::get('zynlepay_service_id') ?: '1002';
        $sandbox = (bool) filter_var(Setting::get('zynlepay_sandbox'), FILTER_VALIDATE_BOOLEAN);

        return new Client(
            merchantId: (string) $merchantId,
            apiId: (string) $apiId,
            apiKey: (string) $apiKey,
            channel: (string) $channel,
            serviceId: (string) $serviceId,
            sandbox: $sandbox
        );
    }

    /**
     * Initiate MOMO bill payment. Use order number as reference for status checks.
     *
     * @return array{success: bool, message?: string, transaction_id?: string, data?: array}
     */
    public function runBillPayment(string $senderPhone, string $referenceNo, float $amount, string $description = 'Order payment'): array
    {
        try {
            $client = $this->getClient();
            $momo = new MomoDeposit($client);
            $result = $momo->runBillPayment(
                senderId: $this->normalizePhone($senderPhone),
                referenceNo: $referenceNo,
                amount: round($amount, 2),
                description: $description
            );

            $transactionId = $result['transaction_id'] ?? $result['response']['transaction_id'] ?? null;
            $responseCode = $result['response_code'] ?? $result['response']['response_code'] ?? null;
            $responseDescription = $result['response_description'] ?? $result['response']['response_description'] ?? 'Payment initiated';

            return [
                'success' => true,
                'message' => $responseDescription,
                'transaction_id' => $transactionId,
                'data' => $result,
            ];
        } catch (InvalidConfigurationException $e) {
            return ['success' => false, 'message' => 'ZynlePay is not configured correctly. Please contact the store.'];
        } catch (ApiException $e) {
            return ['success' => false, 'message' => $e->getMessage() ?: 'Payment request failed.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage() ?: 'An error occurred while initiating payment.'];
        }
    }

    /**
     * Check payment status by reference number (order number).
     *
     * @return array{success: bool, paid: bool, message?: string, data?: array}
     */
    public function checkPaymentStatus(string $referenceNo): array
    {
        try {
            $client = $this->getClient();
            $momo = new MomoDeposit($client);
            $result = $momo->checkPaymentStatus($referenceNo);

            $responseCode = $result['response_code'] ?? $result['response']['response_code'] ?? null;
            $paid = (string) $responseCode === '100';

            return [
                'success' => true,
                'paid' => $paid,
                'data' => $result,
            ];
        } catch (InvalidConfigurationException $e) {
            return ['success' => false, 'paid' => false, 'message' => 'ZynlePay is not configured.'];
        } catch (ApiException $e) {
            return ['success' => false, 'paid' => false, 'message' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['success' => false, 'paid' => false, 'message' => $e->getMessage()];
        }
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\s+/', '', $phone);
        if (preg_match('/^0/', $phone)) {
            return '260' . substr($phone, 1);
        }
        if (preg_match('/^\+260/', $phone)) {
            return substr($phone, 1);
        }
        if (preg_match('/^260/', $phone)) {
            return $phone;
        }

        return '260' . $phone;
    }
}
