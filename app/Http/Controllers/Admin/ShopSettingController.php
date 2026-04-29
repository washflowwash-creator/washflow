<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;

class ShopSettingController extends Controller
{
    public function edit()
    {
        $shop = ShopSetting::current();
        return view('admin.shop-settings.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'in:OPEN,CLOSED,TEMPORARILY_UNAVAILABLE'],
            'capacity' => ['required', 'integer', 'min:1'],
            'processing_capacity' => ['required', 'integer', 'min:1'],
            'current_active_loads' => ['nullable', 'integer', 'min:0'],
        ]);

        $shop = ShopSetting::current();
        $shop->update($data);

        return back()->with('status', 'Shop settings updated.');
    }
}
