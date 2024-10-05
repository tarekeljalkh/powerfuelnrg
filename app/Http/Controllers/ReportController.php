<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;

class ReportController extends Controller
{
    public function clientBalanceReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $balances = Journal::with('thirdParty')
            ->join('journal_line_items', 'journals.trans_id', '=', 'journal_line_items.trans_id')
            ->whereBetween('journals.trans_date', [$request->start_date, $request->end_date])
            ->selectRaw('third_party_id, sum(case when dc_indicator = "D" then amount else 0 end) as total_debit, sum(case when dc_indicator = "C" then amount else 0 end) as total_credit')
            ->groupBy('third_party_id')
            ->get();

        return view('reports.client_balance', compact('balances'));
    }
}
