<?php

namespace App\Http\Controllers;

use App\DataTables\ThirdPartyDataTable;
use App\Models\ThirdParty;
use Illuminate\Http\Request;

class ThirdPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ThirdPartyDataTable $dataTable)
    {
        return $dataTable->render('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        ThirdParty::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'created_by' => auth()->id(),
        ]);

        // Store the data
        // $client = new ThirdParty($request->all());
        // $client->save();

        toastr()->success('Client Added Successfully');
        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = ThirdParty::findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = ThirdParty::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = ThirdParty::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        $client->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'updated_by' => auth()->id(),
        ]);

        // Update the data
        // $client->update($request->all());

        toastr()->success('Client Updated Successfully');
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = ThirdParty::findOrFail($id);
            $client->delete();
            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
