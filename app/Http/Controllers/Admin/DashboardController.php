<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Support\StoreCurrency;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\VideoReel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $recentInquiries = Inquiry::latest()
            ->take(8)
            ->get(['id', 'name', 'email', 'product_interest', 'created_at']);

        $now = Carbon::now();
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = Inquiry::whereDate('created_at', $date)->count();
            $last7Days[] = ['date' => $date->format('Y-m-d'), 'label' => $date->format('D'), 'count' => $count];
        }

        $thisMonth = Inquiry::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $lastMonth = Inquiry::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)->count();
        $inquiriesTrend = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : ($thisMonth > 0 ? 100 : 0);

        $topProductInterests = Inquiry::query()
            ->select('product_interest', DB::raw('count(*) as total'))
            ->whereNotNull('product_interest')
            ->where('product_interest', '!=', '')
            ->groupBy('product_interest')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($r) => ['name' => $r->product_interest ?: 'General', 'count' => $r->total]);

        $categoryCounts = Category::query()
            ->select('categories.id', 'categories.name', DB::raw('count(products.id) as products_count'))
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->where('categories.parent_id', null)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $recentOrders = [];
        if (Schema::hasTable('orders')) {
            $recentOrders = Order::query()
                ->withCount('items')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->map(fn (Order $o) => [
                    'id' => $o->id,
                    'number' => $o->number,
                    'guest_name' => $o->guest_name ?? '',
                    'status' => $o->status,
                    'total' => (float) $o->total,
                    'currency' => $o->currency ?? StoreCurrency::code(),
                    'items_count' => $o->items_count,
                    'created_at' => $o->created_at?->toIso8601String(),
                ])
                ->values()
                ->all();
        }

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'inquiries'    => Inquiry::count(),
                'subscribers'  => NewsletterSubscriber::count(),
                'orders'       => Schema::hasTable('orders') ? Order::query()->count() : 0,
                'products'     => Product::count(),
                'categories'   => Category::count(),
                'testimonials' => Testimonial::count(),
                'video_reels'  => VideoReel::count(),
                'faqs'         => Faq::count(),
            ],
            'recentOrders' => $recentOrders,
            'recentInquiries' => $recentInquiries,
            'analytics' => [
                'inquiriesLast7Days' => $last7Days,
                'inquiriesThisMonth' => $thisMonth,
                'inquiriesLastMonth' => $lastMonth,
                'inquiriesTrendPercent' => $inquiriesTrend,
                'topProductInterests' => $topProductInterests,
                'categoryProductCounts' => $categoryCounts,
            ],
        ]);
    }
}
