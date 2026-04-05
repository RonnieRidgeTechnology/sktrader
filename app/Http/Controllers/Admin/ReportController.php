<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\StoreCurrency;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);

        // Monthly Sales for selected year
        $monthlySales = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(id) as total_orders'),
            DB::raw('SUM(total) as total_sales')
        )->whereYear('created_at', $year)
         ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
         ->groupBy('month')
         ->get()
         ->keyBy('month');

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthDate = Carbon::create()->month($i);
            $monthData = $monthlySales->get($i);
            
            $monthlyData[] = [
                'month' => $monthDate->format('M'),
                'orders' => $monthData ? $monthData->total_orders : 0,
                'sales' => $monthData ? (float) $monthData->total_sales : 0,
            ];
        }

        // yearly totals
        $yearlyOrders = Order::whereYear('created_at', $year)->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])->count();
        $yearlySales = Order::whereYear('created_at', $year)->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])->sum('total');

        // Payment method breakdown all time
        $paymentBreakdown = Order::select('payment_method', DB::raw('COUNT(id) as total'))
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $item->payment_method,
                    'total' => $item->total,
                ];
            });

        // Current Year vs Last Year growth
        $lastYearSales = Order::whereYear('created_at', $year - 1)->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])->sum('total');
        $growth = 0;
        if ($lastYearSales > 0) {
            $growth = (($yearlySales - $lastYearSales) / $lastYearSales) * 100;
        }

        return Inertia::render('Admin/Reports/Index', [
             'currency' => StoreCurrency::code(),
             'year' => (int) $year,
             'monthlyData' => $monthlyData,
             'stats' => [
                 'yearlyOrders' => $yearlyOrders,
                 'yearlySales' => (float) $yearlySales,
                 'lastYearSales' => (float) $lastYearSales,
                 'growth' => round($growth, 1)
             ],
             'paymentBreakdown' => $paymentBreakdown
        ]);
    }
}
