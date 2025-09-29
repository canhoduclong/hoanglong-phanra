<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with(['productVariant.product', 'warehouse'])->latest()->paginate(10);
        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productVariants = ProductVariant::with('product')->get();
        $warehouses = Warehouse::all();
        return view('inventories.create', compact('productVariants', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => [
                'required',
                'exists:product_variants,id',
                Rule::unique('inventories')->where(function ($query) use ($request) {
                    return $query->where('warehouse_id', $request->warehouse_id);
                }),
            ],
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load(['productVariant.product', 'warehouse']);
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $productVariants = ProductVariant::with('product')->get();
        $warehouses = Warehouse::all();
        return view('inventories.edit', compact('inventory', 'productVariants', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'product_variant_id' => [
                'required',
                'exists:product_variants,id',
                Rule::unique('inventories')->where(function ($query) use ($request) {
                    return $query->where('warehouse_id', $request->warehouse_id);
                })->ignore($inventory->id),
            ],
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory record deleted successfully.');
    }
}