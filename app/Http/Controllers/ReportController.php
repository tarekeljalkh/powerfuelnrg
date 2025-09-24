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

        // Totals within date range
        $dateTotals = JournalLineItem::where('third_party_id', $clientId)
            ->whereHas('journal', function ($query) use ($start_date, $end_date) {
                $query->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('trans_date', [$start_date, $end_date]);
                });
            })
            ->selectRaw('
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as total_due,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as total_paid
            ')
            ->first();

        // Grand totals (all time)
        $grandTotals = JournalLineItem::where('third_party_id', $clientId)
            ->selectRaw('
                SUM(CASE WHEN dc_indicator = "D" THEN amount ELSE 0 END) as total_debit,
                SUM(CASE WHEN dc_indicator = "C" THEN amount ELSE 0 END) as total_credit
            ')
            ->first();

        // Only include clients who owe money in the date range
        if ($dateTotals && ($dateTotals->total_due > $dateTotals->total_paid)) {
            $balances->push((object)[
                'third_party_id' => $clientId,
                'thirdParty' => $client->thirdParty,
                'total_due' => $dateTotals->total_due,
                'total_paid' => $dateTotals->total_paid,
                'grand_total_debit' => $grandTotals->total_debit,
                'grand_total_credit' => $grandTotals->total_credit,
                'grand_balance' => $grandTotals->total_debit - $grandTotals->total_credit,
            ]);
        }
    }

    return view('reports.client_statement', compact('balances', 'start_date', 'end_date'));
}
}
