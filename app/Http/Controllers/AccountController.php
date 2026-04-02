<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\StoreCurrency;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class AccountController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user = $request->user();
        $recentOrders = $user->orders()
            ->withCount('items')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn (Order $o) => [
                'id' => $o->id,
                'number' => $o->number,
                'status' => $o->status,
                'total' => (float) $o->total,
                'currency' => $o->currency ?? StoreCurrency::code(),
                'items_count' => $o->items_count,
                'created_at' => $o->created_at?->toIso8601String(),
            ]);

        $ordersCount = $user->orders()->count();

        return Inertia::render('Account/Dashboard', [
            'title' => 'My Account',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'recent_orders' => $recentOrders,
            'orders_count' => $ordersCount,
        ]);
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $statusOptions = Order::statusOptions();
        $statusFilter = $request->query('status');

        $query = $user->orders()->withCount('items')->orderByDesc('created_at');
        if ($statusFilter && isset($statusOptions[$statusFilter])) {
            $query->where('status', $statusFilter);
        }

        $orders = $query->paginate(10)->through(fn (Order $order) => [
            'id' => $order->id,
            'number' => $order->number,
            'status' => $order->status,
            'total' => (float) $order->total,
            'currency' => $order->currency ?? StoreCurrency::code(),
            'items_count' => $order->items_count,
            'created_at' => $order->created_at?->toIso8601String(),
        ]);

        return Inertia::render('Account/Orders/Index', [
            'title' => 'My Orders',
            'orders' => $orders->toArray(),
            'statusFilter' => $statusFilter,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function showOrder(Request $request, Order $order): Response|\Illuminate\Http\RedirectResponse
    {
        if ($order->user_id !== $request->user()->id) {
            abort(404);
        }

        $order->load('items');

        return Inertia::render('Account/Orders/Show', [
            'title' => 'Order ' . $order->number,
            'order' => [
                'id' => $order->id,
                'number' => $order->number,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'shipping_address' => $order->shipping_address,
                'subtotal' => (float) $order->subtotal,
                'total' => (float) $order->total,
                'currency' => $order->currency ?? StoreCurrency::code(),
                'order_notes' => $order->order_notes,
                'created_at' => $order->created_at->toIso8601String(),
                'items' => $order->items->map(fn ($item) => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_slug' => $item->product_slug,
                    'price' => (float) $item->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->line_total,
                ])->values()->all(),
            ],
            'statusOptions' => Order::statusOptions(),
        ]);
    }
}
