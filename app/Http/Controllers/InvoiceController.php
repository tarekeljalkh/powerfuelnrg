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

        // Fetch all invoices for the client
        $invoices = Invoice::with('lineItems') // Assuming Invoice has a relationship with LineItem
                            ->where('client_id', $clientId)
                            ->get();

        // Optionally calculate totals for the invoices and line items
        $totalDue = $invoices->sum(function($invoice) {
            return $invoice->lineItems->sum('amount'); // Assuming 'amount' is in the lineItems
        });

        return view('invoices.show', compact('client', 'invoices', 'totalDue'));
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
