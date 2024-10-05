<?php

namespace App\Http\Controllers;

use App\DataTables\VoucherDataTable;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Journal;
use App\Models\JournalLineItem;
use App\Models\Receipt;
use App\Models\ThirdParty;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(VoucherDataTable $dataTable)
    {
        return $dataTable->render('vouchers.index');
    }

    public function create()
    {
        $thirdParties = ThirdParty::all(); // Fetch all third parties (clients)
        $transactionTypes = TransactionType::where('trans_code', 'Jv')->get(); // Fetch only transaction types where trans_code is 'JV'
        $accounts = Account::all(); // Fetch all accounts
        $currencies = Currency::all(); // Fetch all currencies

        return view('vouchers.create', compact('thirdParties', 'transactionTypes', 'accounts', 'currencies'));
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

        // Retrieve the trans_code from the transaction_types table using the type_id
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
        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
    }

    public function show($id)
    {
        $voucher = Journal::with('lineItems')->findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }

    public function edit($id)
    {
        $voucher = Journal::with('lineItems')->findOrFail($id); // Fetch voucher with related line items
        $thirdParties = ThirdParty::all(); // Fetch all third parties
        $transactionTypes = TransactionType::where('trans_code', 'Jv')->get(); // Fetch only transaction types where trans_code is 'JV'
        $accounts = Account::all(); // Fetch all accounts
        $currencies = Currency::all(); // Fetch all currencies

        return view('vouchers.edit', compact('voucher', 'thirdParties', 'transactionTypes', 'accounts', 'currencies'));
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

        // Retrieve the trans_code from the transaction_types table using the type_id
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

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
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

    public function storeReceipt(Request $request)
    {
        $request->validate([
            'trans_id' => 'required|exists:journals,trans_id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'payment_method' => 'nullable|string',
        ]);

        // Find the journal entry to link with this receipt
        $journal = Journal::findOrFail($request->trans_id);

        // Create a new receipt entry
        $receipt = Receipt::create([
            'trans_id' => $journal->trans_id,
            'receipt_number' => uniqid('REC-'),
            'amount' => $request->amount,
            'date' => $request->date,
            'payment_method' => $request->payment_method,
            'third_party_id' => $journal->third_party_id,  // Use third_party_id from the journal
            'created_by' => auth()->user()->id,
        ]);

        // Update the journal entry to reflect the payment
        $journal->update([
            'status' => 1, // or another logic to mark it as paid or partially paid
        ]);

        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
    }


    public function clientBalanceReport(Request $request)
    {
        // Get the default dates or use the provided ones
        $today = now()->toDateString();
        $start_date = $request->get('start_date', $today);
        $end_date = $request->get('end_date', $today);

        // Fetch the balance data
        $balances = JournalLineItem::with('thirdParty')
            ->whereHas('journal', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('trans_date', [$start_date, $end_date]);
            })
            ->selectRaw('third_party_id,
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as total_debit,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('third_party_id')
            ->get();

        // Pass the balances to the view
        return view('reports.filter', compact('balances', 'start_date', 'end_date'));
    }

    public function clientSpecificReport(Request $request, $clientId)
    {
        // Define default dates (from the beginning of time)
        $start_date = $request->get('start_date', '1970-01-01');  // Default start date
        $end_date = $request->get('end_date', now()->toDateString());  // Default end date (today)

        // Fetch all journal line items for the specific client within the date range
        $transactions = JournalLineItem::where('third_party_id', $clientId)
            ->whereHas('journal', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('trans_date', [$start_date, $end_date]);
            })
            ->get();

        // Calculate total due and total paid
        $balances = JournalLineItem::where('third_party_id', $clientId)
            ->whereHas('journal', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('trans_date', [$start_date, $end_date]);
            })
            ->selectRaw('
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as total_due,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as total_paid')
            ->first();

        // Fetch the client details
        $client = ThirdParty::findOrFail($clientId);

        return view('reports.client_specific', compact('balances', 'client', 'start_date', 'end_date', 'transactions'));
    }
}
