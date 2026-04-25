<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $today = Carbon::now()->startOfDay();

        $ordersQuery = Order::query();
        $transactionsQuery = Transaction::query();

        if ($user->isCustomer()) {
            $ordersQuery->where('user_id', $user->id);
            $transactionsQuery->where('user_id', $user->id);
        }

        $recentOrders = (clone $ordersQuery)
            ->with(['booking', 'payment'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', [
            'orderCount' => (clone $ordersQuery)->whereDate('created_at', $today)->count(),
            'pendingCount' => (clone $ordersQuery)->whereDate('created_at', $today)->where('status', '!=', 'completed')->count(),
            'completedCount' => (clone $ordersQuery)->whereDate('created_at', $today)->where('status', 'completed')->count(),
            'revenue' => $transactionsQuery->whereDate('created_at', $today)->sum('amount'),
            'recentOrders' => $recentOrders,
            'lowStockItems' => Inventory::query()
                ->whereColumn('quantity', '<=', 'low_stock_threshold')
                ->orderBy('quantity')
                ->take(5)
                ->get(),
        ]);
    }
}
