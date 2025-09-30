<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $inventoryMovements = InventoryMovement::with(['inventory.productVariant.product', 'inventory.warehouse', 'user', 'reference'])->latest()->paginate(10);
        return view('inventory-movements.index', compact('inventoryMovements'));
    }

    public function create()
    {
        $inventories = Inventory::with('productVariant.product', 'warehouse')->get();
        return view('inventory-movements.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'type' => 'required|string|max:255',
            'reference_id' => 'nullable|integer',
            'reference_type' => 'nullable|string|max:255',
        ]);

        $inventoryMovement = new InventoryMovement($request->all());
        $inventoryMovement->user_id = auth()->id();
        $inventoryMovement->save();

        // Update inventory quantity
        $inventory = Inventory::find($request->inventory_id);
        $inventory->quantity += $request->quantity;
        $inventory->save();

        return redirect()->route('inventory-movements.index')
                         ->with('success', 'Inventory movement created successfully.');
    }

    public function show(InventoryMovement $inventoryMovement)
    {
        $inventoryMovement->load(['inventory.productVariant.product', 'inventory.warehouse', 'user', 'reference']);
        return view('inventory-movements.show', compact('inventoryMovement'));
    }

    public function edit(InventoryMovement $inventoryMovement)
    {
        $inventories = Inventory::with('productVariant.product', 'warehouse')->get();
        return view('inventory-movements.edit', compact('inventoryMovement', 'inventories'));
    }

    public function update(Request $request, InventoryMovement $inventoryMovement)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'type' => 'required|string|max:255',
            'reference_id' => 'nullable|integer',
            'reference_type' => 'nullable|string|max:255',
        ]);

        // Revert old inventory quantity
        $oldInventory = Inventory::find($inventoryMovement->inventory_id);
        $oldInventory->quantity -= $inventoryMovement->quantity;
        $oldInventory->save();

        $inventoryMovement->update($request->all());

        // Update new inventory quantity
        $newInventory = Inventory::find($request->inventory_id);
        $newInventory->quantity += $request->quantity;
        $newInventory->save();

        return redirect()->route('inventory-movements.index')
                         ->with('success', 'Inventory movement updated successfully.');
    }

    public function destroy(InventoryMovement $inventoryMovement)
    {
        // Revert inventory quantity
        $inventory = Inventory::find($inventoryMovement->inventory_id);
        $inventory->quantity -= $inventoryMovement->quantity;
        $inventory->save();

        $inventoryMovement->delete();

        return redirect()->route('inventory-movements.index')
                         ->with('success', 'Inventory movement deleted successfully.');
    }
}
