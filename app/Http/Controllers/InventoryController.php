<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Inventory::query()->orderBy('name')->paginate(20);
        $lowStockItems = Inventory::query()
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->get();

        return view('inventories.index', compact('items', 'lowStockItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:inventories,name'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        Inventory::create($validated);

        return redirect()->route('inventories.index')->with('success', 'Inventory item created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:inventories,name,'.$inventory->id],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        $inventory->update($validated);

        return redirect()->route('inventories.show', $inventory)->with('success', 'Inventory updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Inventory deleted.');
    }
}
