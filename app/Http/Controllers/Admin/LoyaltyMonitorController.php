<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyCard;
use App\Models\User;
use Illuminate\Http\Request;

class LoyaltyMonitorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $loyalties = LoyaltyCard::query()
            ->with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.loyalty.index', compact('loyalties', 'search'));
    }

    public function show(User $user)
    {
        $loyalty = $user->loyaltyCard()->first();
        $orders = $user->orders()
            ->with('payment')
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('admin.loyalty.show', compact('user', 'loyalty', 'orders'));
    }
}
