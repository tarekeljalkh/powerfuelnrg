<?php

namespace App\Http\Controllers;

use App\DataTables\InventoriesDataTable;
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
        return view('inventories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => ['string', 'max:200'],
            'description' => ['string', 'max:200'],
            'price' => ['required', 'numeric'],
        ]);


        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        toastr()->success('Product Added Successfully');
        // Redirect or return a response
        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //make it show address as map
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Validation
        $request->validate([
            'name' => ['string', 'max:200'],
            'description' => ['string', 'max:200'],
            'price' => ['required', 'numeric'],
        ]);


        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        toastr()->success('Client Updated Successfully');
        // Redirect or return a response
        return to_route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            //return response(['status' => 'error', 'message' =>  $e->getMessage()]);
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
