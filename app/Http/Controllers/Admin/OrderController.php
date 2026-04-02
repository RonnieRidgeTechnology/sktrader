<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\StoreCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statusOptions = Order::statusOptions();

        $status = $request->query('status');
        if (is_array($status)) {
            $status = $status[0] ?? null;
        }
        $status = is_string($status) ? $status : null;
        if ($status !== null && $status !== '' && ! isset($statusOptions[$status])) {
            $status = null;
        }

        $ordersTotalAll = 0;
        if (Schema::hasTable('orders')) {
            $ordersTotalAll = (int) Order::query()->count();
        }

        $query = Order::withCount('items')->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate(15)->withQueryString();

        // e.g. /admin/orders?page=5 when only 1 page exists → empty slice but total > 0 (looks "broken").
        if ($paginator->total() > 0 && $paginator->count() === 0 && $paginator->currentPage() > 1) {
            return redirect()->to($paginator->url($paginator->lastPage()));
        }

        $rowData = collect($paginator->items())->map(fn (Order $order) => [
            'id' => $order->id,
            'number' => $order->number,
            'guest_name' => $order->guest_name ?? '',
            'guest_email' => $order->guest_email ?? '',
            'status' => $order->status ?? 'pending',
            'total' => (float) $order->total,
            'currency' => $order->currency ?? StoreCurrency::code(),
            'items_count' => $order->items_count,
            'created_at' => $order->created_at?->toIso8601String(),
        ])->values()->all();

        $paginationMeta = [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem() ?? 0,
            'to' => $paginator->lastItem() ?? 0,
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
        ];

        // Plain arrays (same pattern as admin dashboard). `orderIndexRows` is a duplicate key for resilient clients.
        return Inertia::render('Admin/Orders/Index', [
            'orderIndexRows' => $rowData,
            'adminOrderRows' => $rowData,
            'adminOrderPagination' => $paginationMeta,
            'statusFilter' => $status,
            'statusOptions' => $statusOptions,
            'ordersTotalAll' => $ordersTotalAll,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');
        $order->load('user:id,name,email');

        return Inertia::render('Admin/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'number' => $order->number,
                'guest_name' => $order->guest_name,
                'guest_email' => $order->guest_email,
                'guest_phone' => $order->guest_phone,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'shipping_address' => $order->shipping_address,
                'subtotal' => (float) $order->subtotal,
                'total' => (float) $order->total,
                'currency' => $order->currency,
                'order_notes' => $order->order_notes,
                'created_at' => $order->created_at->toIso8601String(),
                'user' => $order->user ? ['id' => $order->user->id, 'name' => $order->user->name, 'email' => $order->user->email] : null,
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

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Order::statusOptions())),
        ]);
        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
}
