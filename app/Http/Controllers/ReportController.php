<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs and normalize to plain strings.
        $fromDate = trim((string) $request->input('from_date', ''));
        $toDate = trim((string) $request->input('to_date', ''));
        $status = trim((string) $request->input('status', ''));
        $serviceType = trim((string) $request->input('service_type', ''));

        // Default date range (current month if no filters provided)
        if ($fromDate === '' || $toDate === '') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $fromDate = $start->toDateString();
            $toDate = $end->toDateString();
        } else {
            $start = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();
        }

        // Build query for transactions
        $query = Transaction::query()
            ->with(['order.booking', 'order.payment'])
            ->whereBetween('completed_at', [$start, $end]);

        // Apply payment status filter
        if (in_array($status, ['paid', 'unpaid'], true)) {
            $query->whereHas('order.payment', function ($q) use ($status) {
                $q->where('payment_status', $status);
            });
        }

        // Apply service type filter
        if ($serviceType !== '') {
            $query->whereHas('order.booking', function ($q) use ($serviceType) {
                $q->where('service_type', $serviceType);
            });
        }

        // Get daily data
        $daily = (clone $query)
            ->selectRaw('DATE(completed_at) as report_date, COUNT(*) as orders, SUM(amount) as revenue')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        // Get detailed transaction data
        $detailedTransactions = $query
            ->with('order.booking.user')
            ->latest('completed_at')
            ->paginate(15);

        // Calculate summary statistics
        $summary = [
            'total_orders' => $daily->sum('orders'),
            'total_revenue' => $daily->sum('revenue'),
            'avg_order_value' => $daily->sum('orders') > 0 ? $daily->sum('revenue') / $daily->sum('orders') : 0,
        ];

        // Get available service types for filter dropdown
        $serviceTypes = Order::query()
            ->join('bookings', 'orders.booking_id', '=', 'bookings.id')
            ->distinct()
            ->pluck('bookings.service_type')
            ->sort()
            ->values();

        return view('reports.index', compact(
            'daily',
            'summary',
            'detailedTransactions',
            'fromDate',
            'toDate',
            'status',
            'serviceType',
            'serviceTypes',
            'start',
            'end'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        // Get filter inputs and normalize to plain strings.
        $fromDate = trim((string) $request->input('from_date', ''));
        $toDate = trim((string) $request->input('to_date', ''));
        $status = trim((string) $request->input('status', ''));
        $serviceType = trim((string) $request->input('service_type', ''));

        // Default date range
        if ($fromDate === '' || $toDate === '') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
        } else {
            $start = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();
        }

        // Build query
        $query = Transaction::query()
            ->with(['order.booking.user', 'order.payment'])
            ->whereBetween('completed_at', [$start, $end]);

        if (in_array($status, ['paid', 'unpaid'], true)) {
            $query->whereHas('order.payment', function ($q) use ($status) {
                $q->where('payment_status', $status);
            });
        }

        if ($serviceType !== '') {
            $query->whereHas('order.booking', function ($q) use ($serviceType) {
                $q->where('service_type', $serviceType);
            });
        }

        $rows = $query->latest('completed_at')->get();

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Order ID', 'Customer', 'Service Type', 'Amount', 'Status']);

            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->completed_at->format('Y-m-d H:i:s'),
                    $row->order_id ?? 'N/A',
                    $row->order?->booking?->user?->name ?? 'Unknown',
                    $row->order?->booking?->service_type ?? 'N/A',
                    $row->amount,
                    $row->order?->payment?->payment_status ?? 'unpaid',
                ]);
            }

            fclose($out);
        }, 'washflow-report-'.now()->format('Ymd-His').'.csv');
    }
}
