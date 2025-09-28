<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use App\Models\JournalLineItem;

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

public function clientStatementReport(Request $request)
{
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    // Fetch all clients who have journal entries
    $clients = Journal::with('thirdParty')
        ->join('journal_line_items', 'journals.trans_id', '=', 'journal_line_items.trans_id')
        ->select('third_party_id')
        ->groupBy('third_party_id')
        ->get();

    $balances = collect();

    foreach ($clients as $client) {
        $clientId = $client->third_party_id;

        // Previous totals (before start date)
        $previousTotals = JournalLineItem::where('third_party_id', $clientId)
            ->when($start_date, function ($query) use ($start_date) {
                $query->whereHas('journal', function ($q) use ($start_date) {
                    $q->where('trans_date', '<', $start_date);
                });
            })
            ->selectRaw('
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as prev_debit,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as prev_credit
            ')
            ->first();

        // Current period totals (between start and end date)
        $currentTotals = JournalLineItem::where('third_party_id', $clientId)
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereHas('journal', function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('trans_date', [$start_date, $end_date]);
                });
            })
            ->selectRaw('
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as current_debit,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as current_credit,
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) - SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as balance
            ')
            ->first();

        // Only include clients who owe money in the date range
        if ($currentTotals && ($currentTotals->balance > 0 || ($previousTotals->prev_credit ?? 0) - ($previousTotals->prev_debit ?? 0) > 0)) {
            $balances->push((object)[
                'third_party_id' => $clientId,
                'thirdParty' => $client->thirdParty ?? null,
                'previousTotals' => $previousTotals,
                'currentTotals' => $currentTotals,
                'balance_range' => $currentTotals->balance ?? 0,
                'grand_balance' => (($previousTotals->prev_credit ?? 0) - ($previousTotals->prev_debit ?? 0)) + ($currentTotals->balance ?? 0),
            ]);
        }
    }

    return view('reports.client_statement', compact('balances', 'start_date', 'end_date'));
}
}
