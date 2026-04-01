<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ZynlePay\WebhookHandler;

class ZynlePayWebhookController extends Controller
{

    /**
     * ZynlePay calls this URL to notify payment result. No auth; validate by reference_no.
     */
    public function handle(Request $request): Response
    {
        $payload = $request->all();
        if (empty($payload)) {
            return response('', 400);
        }

        try {
            $handler = new WebhookHandler();
            $result = $handler->handle($payload);
        } catch (\InvalidArgumentException $e) {
            return response('Invalid payload', 400);
        }

        $referenceNo = $result['reference_no'] ?? null;
        if (! $referenceNo) {
            return response('', 200);
        }

        $order = Order::where('number', $referenceNo)->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_ZYNLEPAY) {
            return response('', 200);
        }

        if (($result['status'] ?? '') === 'success') {
            $order->update(['status' => Order::STATUS_CONFIRMED]);
        }

        return response('', 200);
    }
}
