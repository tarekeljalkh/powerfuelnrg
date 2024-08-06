<?php

namespace App\Http\Controllers;

use App\DataTables\InventoriesDataTable;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InventoriesDataTable $dataTable)
    {
        return $dataTable->render('inventories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventories.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'fuel_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            // Create Inventory
            $inventory = Inventory::create($request->only('supplier_id', 'fuel_type', 'quantity'));

            // Record initial transaction
            InventoryTransaction::create([
                'supplier_id' => $inventory->supplier_id,
                'inventory_id' => $inventory->id,
                'quantity' => $inventory->quantity,
                'transaction_date' => now(),
                'type' => 'addition',
            ]);

            toastr()->success('Inventory Added Successfully');
        } catch (\Exception $e) {
            toastr()->error('Failed to add inventory.');
        }

        // Redirect or return a response
        return to_route('inventories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = Inventory::with(['supplier', 'transactions'])->findOrFail($id);
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $suppliers = Supplier::all();
        return view('inventories.edit', compact('inventory', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);

        // Validation
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'fuel_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            // Update Inventory
            $quantityDifference = $request->quantity - $inventory->quantity;
            $inventory->update($request->only('supplier_id', 'fuel_type', 'quantity'));

            // Record transaction if quantity has changed
            if ($quantityDifference != 0) {
                InventoryTransaction::create([
                    'supplier_id' => $inventory->supplier_id,
                    'inventory_id' => $inventory->id,
                    'quantity' => abs($quantityDifference),
                    'transaction_date' => now(),
                    'type' => $quantityDifference > 0 ? 'addition' : 'reduction',
                ]);
            }

            toastr()->success('Inventory Updated Successfully');
        } catch (\Exception $e) {
            toastr()->error('Failed to update inventory.');
        }

        // Redirect or return a response
        return to_route('inventories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();
            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            //return response(['status' => 'error', 'message' =>  $e->getMessage()]);
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }

        return redirect()->route('inventories.index');
    }

    /**
     * Show the form for adding fuel to the inventory.
     */
    public function showAddFuelForm($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventories.add-fuel', compact('inventory'));
    }

    /**
     * Add fuel to the inventory.
     */
    public function addFuel(Request $request, $inventoryId)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $request->validate([
            'quantity' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        try {
            $additionalQuantity = $request->quantity;

            // Update inventory
            $inventory->increment('quantity', $additionalQuantity);

            // Record transaction
            InventoryTransaction::create([
                'supplier_id' => $inventory->supplier_id,
                'inventory_id' => $inventory->id,
                'quantity' => $additionalQuantity,
                'transaction_date' => $request->transaction_date,
                'type' => 'addition',
            ]);

            toastr()->success('Fuel added successfully and transaction recorded.');
        } catch (\Exception $e) {
            toastr()->error('Failed to add fuel.');
        }

        return redirect()->route('inventories.show', $inventory->id);
    }
}
