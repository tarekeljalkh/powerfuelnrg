<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ThirdParty; // Your Client model
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function showInvoice($clientId)
    {
        // Fetch client details
        $client = ThirdParty::findOrFail($clientId);

        // Fetch all invoices or transaction details for the client
        // Assuming you have an `invoices` table
        $invoices = Invoice::where('client_id', $clientId)->get();

        // If you have line items
        $lineItems = []; // Fetch from the database or create logic for line items

        return view('invoices.show', compact('client', 'invoices', 'lineItems'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
