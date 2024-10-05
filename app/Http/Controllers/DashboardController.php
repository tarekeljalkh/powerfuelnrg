<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\Journal;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\ThirdParty;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ThirdParty::all()->count();
        // Fetch clients whose total debit is greater than total credit
        $balances = Journal::with('thirdParty')
            ->join('journal_line_items', 'journals.trans_id', '=', 'journal_line_items.trans_id')
            ->selectRaw('third_party_id, sum(case when dc_indicator = "D" then amount else 0 end) as total_debit, sum(case when dc_indicator = "C" then amount else 0 end) as total_credit')
            ->groupBy('third_party_id')
            ->havingRaw('sum(case when dc_indicator = "D" then amount else 0 end) > sum(case when dc_indicator = "C" then amount else 0 end)') // Only clients who owe money
            ->get();
            $vouchers = Journal::where('trans_code', 'Jv')->count();
            $receipts = Journal::where('trans_code', 'Rv')->count();
            return view('dashboard', compact('clients', 'balances', 'vouchers', 'receipts'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
