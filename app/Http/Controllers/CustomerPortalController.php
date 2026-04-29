<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\ShopSetting;
use App\Models\ServiceRate;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $orders = Order::query()
            ->with('payment')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $loyalty = $user->loyaltyCard()->first();
        $shop = ShopSetting::current();
        $serviceRates = ServiceRate::query()->orderBy('service_type')->get();

        return view('customer.portal', compact('bookings', 'orders', 'loyalty', 'shop', 'serviceRates'));
    }
}
