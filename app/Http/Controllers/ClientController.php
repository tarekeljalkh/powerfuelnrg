<?php

namespace App\Http\Controllers;

use App\DataTables\ClientsDataTable;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClientsDataTable $dataTable)
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
            'first_name' => ['string', 'max:200'],
            'last_name' => ['string', 'max:200'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'numeric'],
            'landline' => ['required', 'numeric'],
            'address' => ['required', 'max:255'],
        ]);


        $client = new Client();
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->email = $request->email;
        $client->mobile = $request->mobile;
        $client->landline = $request->landline;
        $client->address = $request->address;
        $client->save();

        toastr()->success('Client Added Successfully');
        // Redirect or return a response
        return to_route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //make it show address as map
        $client = Client::findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        // Validation
        $request->validate([
            'first_name' => ['string', 'max:200'],
            'last_name' => ['string', 'max:200'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'numeric'],
            'landline' => ['required', 'numeric'],
            'address' => ['required', 'max:255'],
        ]);


        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->email = $request->email;
        $client->mobile = $request->mobile;
        $client->landline = $request->landline;
        $client->address = $request->address;
        $client->save();

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
            $client = Client::findOrFail($id);
            $client->delete();
            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            //return response(['status' => 'error', 'message' =>  $e->getMessage()]);
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
