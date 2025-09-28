<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use App\Models\JournalLineItem;
use Illuminate\Support\Facades\DB;

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
        $start_date = $request->input('start_date', now()->startOfYear()->format('Y-m-d'));
        $end_date   = $request->input('end_date', now()->format('Y-m-d'));

        // Current totals in date range
        $balances = DB::table('journal_line_items as jli')
            ->join('journals as j', 'j.trans_id', '=', 'jli.trans_id')
            ->join('third_parties as tp', 'tp.id', '=', 'jli.third_party_id')
            // ->whereBetween('j.trans_date', [$start_date, $end_date])
            ->whereBetween('j.trans_date', [$start_date, \Carbon\Carbon::parse($end_date)->subDay()->format('Y-m-d')])
            ->select(
                'jli.third_party_id',
                'tp.name as client_name',
                'jli.account_code',
                DB::raw('SUM(CASE WHEN jli.dc_indicator = "D" THEN jli.amount ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN jli.dc_indicator = "C" THEN jli.amount ELSE 0 END) as total_credit'),
                DB::raw('SUM(CASE WHEN jli.dc_indicator = "D" THEN jli.amount ELSE 0 END)
                     - SUM(CASE WHEN jli.dc_indicator = "C" THEN jli.amount ELSE 0 END) as balance')
            )
            ->groupBy('jli.third_party_id', 'jli.account_code', 'tp.name')
            ->havingRaw('(SUM(CASE WHEN jli.dc_indicator = "D" THEN jli.amount ELSE 0 END)
                     - SUM(CASE WHEN jli.dc_indicator = "C" THEN jli.amount ELSE 0 END)) > 0')
            ->orderByDesc(DB::raw('MAX(j.trans_date)'))
            ->get();

        // Previous totals per client (before start_date)
        $previousTotalsRaw = DB::table('journal_line_items as jli')
            ->join('journals as j', 'j.trans_id', '=', 'jli.trans_id')
            ->select(
                'jli.third_party_id',
                DB::raw('SUM(CASE WHEN jli.dc_indicator = "D" THEN jli.amount ELSE 0 END) as prev_debit'),
                DB::raw('SUM(CASE WHEN jli.dc_indicator = "C" THEN jli.amount ELSE 0 END) as prev_credit')
            )
            ->when($start_date, function ($q) use ($start_date) {
                $q->where('j.trans_date', '<', $start_date);
            })
            ->groupBy('jli.third_party_id')
            ->get();

        $previousTotals = $previousTotalsRaw->keyBy('third_party_id');

        return view('reports.client_statement', compact('balances', 'previousTotals', 'start_date', 'end_date'));
    }
}
