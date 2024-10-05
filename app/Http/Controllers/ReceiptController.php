<?php

namespace App\Http\Controllers;

use App\DataTables\ReceiptDataTable;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Journal;
use App\Models\JournalLineItem;
use App\Models\ThirdParty;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index(ReceiptDataTable $dataTable)
    {
        return $dataTable->render('receipts.index');
    }

    public function create()
    {
        // Fetch all necessary data for the form
        $thirdParties = ThirdParty::all(); // Fetch all third parties (clients)
        $transactionTypes = TransactionType::where('trans_code', 'Rv')->get(); // Fetch only transaction types where trans_code is 'Rv'
        $accounts = Account::all(); // Fetch all accounts
        $currencies = Currency::all(); // Fetch all currencies

        return view('receipts.create', compact('thirdParties', 'transactionTypes', 'accounts', 'currencies'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'type_id' => 'required|exists:transaction_types,id',  // Validate type_id to ensure it exists in transaction_types table
            'trx_ref' => 'required|string|max:50',
            'trx_date' => 'nullable|date',  // Allow null and date format
            'activation_date' => 'nullable|date',  // Allow null and date format
            'line_items.*.account' => 'required|string',
            'line_items.*.currency' => 'required|string',
            'line_items.*.dc_indicator' => 'required|string',
            'line_items.*.amount' => 'required|numeric',
        ]);

        // Set the trans_code to 'Rv' for receipts
        $trans_code = TransactionType::find($request->type_id)->trans_code;

        // Use current date if trx_date or activation_date is not provided
        $currentDate = now();

        // Create a new journal entry using the trans_code and type_id
        $journal = Journal::create([
            'trans_code' => $trans_code,  // Store the retrieved trans_code
            'type_id' => $request->type_id,
            'manual_ref' => $request->trx_ref,
            'trans_date' => $request->trx_date ?? $currentDate,  // Use provided date or current date
            'activation_date' => $request->activation_date ?? $currentDate,  // Use provided date or current date
            'locked' => false,
            'created_by' => auth()->user()->id,
        ]);

        // Iterate over each line item and save it to the journal_line_items table
        foreach ($request->line_items as $index => $item) {
            JournalLineItem::create([
                'trans_id' => $journal->trans_id,  // Use the ID from the newly created journal entry
                'ligne_id' => $index + 1,    // Assign line item ID starting from 1
                'account_code' => $item['account'],
                'aux' => $item['aux'] ?? null,
                'currency' => $item['currency'],
                'branch' => $item['branch'] ?? null,
                'reference' => $item['reference'] ?? null,
                'description' => $item['description'] ?? null,
                'dc_indicator' => $item['dc_indicator'],
                'amount' => $item['amount'],
                'third_party_id' => $item['third_party_id'] ?? null,  // Handle third party
                'created_by' => auth()->user()->id,
            ]);
        }

        // Redirect to the index page with a success message
        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
    }

    public function show($id)
    {
        $receipt = Journal::with('lineItems')->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }

    public function edit($id)
    {
        $receipt = Journal::with('lineItems')->findOrFail($id); // Fetch receipt with related line items
        $thirdParties = ThirdParty::all(); // Fetch all third parties
        $transactionTypes = TransactionType::where('trans_code', 'Rv')->get(); // Fetch only transaction types where trans_code is 'Rv'
        $accounts = Account::all(); // Fetch all accounts
        $currencies = Currency::all(); // Fetch all currencies

        return view('receipts.edit', compact('receipt', 'thirdParties', 'transactionTypes', 'accounts', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'type_id' => 'required|exists:transaction_types,id',  // Validate type_id to ensure it exists in transaction_types table
            'trx_ref' => 'required|string|max:50',
            'trx_date' => 'nullable|date',  // Allow null and date format
            'activation_date' => 'nullable|date',  // Allow null and date format
            'line_items.*.account' => 'required|string',
            'line_items.*.currency' => 'required|string',
            'line_items.*.dc_indicator' => 'required|string',
            'line_items.*.amount' => 'required|numeric',
        ]);

        // Find the existing journal entry
        $journal = Journal::findOrFail($id);

        // Set the trans_code to 'Rv' for receipts
        $trans_code = TransactionType::find($request->type_id)->trans_code;

        // Update the journal entry
        $journal->update([
            'trans_code' => $trans_code,
            'type_id' => $request->type_id,
            'manual_ref' => $request->trx_ref,
            'trans_date' => $request->trx_date ?? now(),
            'activation_date' => $request->activation_date ?? now(),
            'locked' => false,
            'created_by' => auth()->user()->id,
        ]);

        // Delete existing line items
        $journal->lineItems()->delete();

        // Iterate over each line item and save it to the journal_line_items table
        foreach ($request->line_items as $index => $item) {
            JournalLineItem::create([
                'trans_id' => $journal->trans_id,
                'ligne_id' => $index + 1,
                'account_code' => $item['account'],
                'aux' => $item['aux'] ?? null,
                'currency' => $item['currency'],
                'branch' => $item['branch'] ?? null,
                'reference' => $item['reference'] ?? null,
                'description' => $item['description'] ?? null,
                'dc_indicator' => $item['dc_indicator'],
                'amount' => $item['amount'],
                'third_party_id' => $item['third_party_id'] ?? null,
                'created_by' => auth()->user()->id,
            ]);
        }

        return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $journal = Journal::findOrFail($id);
            $journal->delete();
            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
