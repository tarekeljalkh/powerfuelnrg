<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\Models\Order;
use App\Models\Client;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $inventories = Inventory::all();
        return view('orders.create', compact('clients', 'inventories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'inventory_id' => ['required', 'exists:inventories,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
        ], [
            'client_id.required' => 'Please select a client.',
            'inventory_id.required' => 'Please select a fuel type.',
            'quantity.min' => 'The quantity must be at least 1.',
        ]);

        DB::beginTransaction();

        try {
            $inventory = Inventory::findOrFail($request->inventory_id);

            // Check if the requested quantity is available
            if ($inventory->quantity < $request->quantity) {
                return redirect()->back()->withErrors(['quantity' => 'Not enough stock available.']);
            }

            // Create Order
            $order = Order::create([
                'client_id' => $request->client_id,
                'inventory_id' => $request->inventory_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total' => $request->quantity * $request->price,
                'order_date' => $request->order_date,
                'status' => 'pending',
            ]);

            // Update inventory quantity
            $inventory->decrement('quantity', $request->quantity);

            DB::commit();

            toastr()->success('Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            toastr()->error('Failed to create order.');
        }

        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['client', 'inventory'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        $clients = Client::all();
        $inventories = Inventory::all();
        return view('orders.edit', compact('order', 'clients', 'inventories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        // Validation
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'inventory_id' => ['required', 'exists:inventories,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
        ], [
            'client_id.required' => 'Please select a client.',
            'inventory_id.required' => 'Please select a fuel type.',
            'quantity.min' => 'The quantity must be at least 1.',
        ]);

        DB::beginTransaction();

        try {
            $inventory = Inventory::findOrFail($request->inventory_id);

            // Check if the requested quantity is available
            if ($inventory->quantity + $order->quantity < $request->quantity) {
                return redirect()->back()->withErrors(['quantity' => 'Not enough stock available.']);
            }

            // Update inventory quantity (restore the previous quantity before updating)
            $inventory->increment('quantity', $order->quantity);
            $inventory->decrement('quantity', $request->quantity);

            // Update Order
            $order->update([
                'client_id' => $request->client_id,
                'inventory_id' => $request->inventory_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total' => $request->quantity * $request->price,
                'order_date' => $request->order_date,
            ]);

            DB::commit();

            toastr()->success('Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order update failed: ' . $e->getMessage());
            toastr()->error('Failed to update order.');
        }

        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);

            // Restore the inventory quantity when an order is deleted
            $inventory = Inventory::findOrFail($order->inventory_id);
            $inventory->increment('quantity', $order->quantity);

            $order->delete();

            DB::commit();

            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order deletion failed: ' . $e->getMessage());
            //return response(['status' => 'error', 'message' =>  $e->getMessage()]);
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }

        return redirect()->route('orders.index');
    }
}
