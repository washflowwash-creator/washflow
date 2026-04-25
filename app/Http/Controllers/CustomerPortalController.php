<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
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

        return view('customer.portal', compact('bookings', 'orders'));
    }
}
