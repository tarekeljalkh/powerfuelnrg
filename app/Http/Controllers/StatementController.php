<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\ThirdParty;
use Illuminate\Http\Request; 
use NumberFormatter;
use Pdf;

class StatementController extends Controller
{
    public function index($client_id, Request $request)
    {
        
        // 1. Fetch client data
        $client =  ThirdParty::findOrFail($client_id);

        // 2. Get date range
        $start_date = $request->input('start_date', now()->startOfYear()->toDateString());
        $end_date   = $request->input('end_date', now()->format('Y-m-d'));

        // 3. Query all transactions (Rv = payment, Jv = withdrawal)
        $rows = Journal::from('journals as j')
            ->join('journal_line_items as jli', 'j.trans_id', '=', 'jli.trans_id')
            ->where('jli.third_party_id', $client_id)
            ->whereDate('j.trans_date', '>=', $start_date)
            ->whereDate('j.trans_date', '<=', $end_date)
            ->selectRaw('
                j.trans_date,
                j.trans_code,
                CASE WHEN j.trans_code = "Rv" then j.manual_ref else jli.reference end manual_ref,
                jli.description,
                jli.amount,
                jli.currency,
                CASE WHEN j.trans_code = "Jv" THEN jli.amount END as debit,
                CASE WHEN j.trans_code = "Rv" THEN jli.amount END as credit
            ')
            ->orderBy('j.trans_date', 'asc')
            ->orderBy('j.trans_code', 'asc')
            ->get();

        // 4. Running balance
        $balance = 0;
        foreach ($rows as $row) {
            if ($row->debit)  $balance += $row->debit;
            if ($row->credit) $balance -= $row->credit;
            $row->running_balance = $balance;
        }

                // j.manual_ref as trx_number,
        // 5. Totals
        $total_debit = $rows->sum('debit');
        $total_credit = $rows->sum('credit');

        // 6. Amount in words
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $amount_words = ucfirst($formatter->format($balance)) . " USD";

        return view('reports.statement', compact(
            'client', 'rows', 'total_debit', 'total_credit',
            'balance', 'amount_words', 'start_date', 'end_date'
        ));
    }

//     public function pdf($client_id, Request $request)
// {
//     $html = $this->index($client_id, $request)->render();

//     $pdf = \PDF::loadHTML($html)->setPaper('A4', 'portrait');

//     return $pdf->download('StatementOfAccount.pdf');
// }

public function pdf($client_id, Request $request)
{
    // reuse the data of index() but render directly to view:
    $view = $this->index($client_id, $request);

    $pdf = Pdf::loadHTML($view->render())
              ->setPaper('A4', 'portrait');

    return $pdf->download('StatementOfAccount.pdf');
}

public function filter()
{
    $clients = \App\Models\ThirdParty::orderBy('name')->get();

    return view('reports.statement_filter', [
        'clients'    => $clients,
        'start_date' => now()->startOfYear()->format('Y-m-d'),
        'end_date'   => now()->format('Y-m-d'),
    ]);
}

}
