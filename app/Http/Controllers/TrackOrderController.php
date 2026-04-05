<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrackOrderController extends Controller
{
    public function index()
    {
        return Inertia::render('TrackOrder/Index', [
            'title' => 'Track Your Order',
        ]);
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:32',
            'guest_email' => 'required|email|max:255',
        ]);

        $order = Order::with('items')->where('number', trim($validated['order_number']))
            ->where('guest_email', trim($validated['guest_email']))
            ->first();

        if (!$order) {
            return back()->with('error', 'No order found with the provided details. Please check your order number and email.');
        }

        return Inertia::render('TrackOrder/Index', [
            'title' => 'Order Tracking Result',
            'trackingResult' => [
                'number' => $order->number,
                'status' => $order->status,
                'created_at' => $order->created_at->format('M d, Y H:i A'),
                'courier_name' => $order->courier_name,
                'tracking_number' => $order->tracking_number,
                'tracking_url' => $order->tracking_url,
                'total' => (float) $order->total,
                'currency' => $order->currency,
                'items' => $order->items->map(fn($item) => [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                ])
            ]
        ]);
    }
}
