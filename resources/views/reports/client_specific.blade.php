@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('clients.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $client->name }}'s Balance Report</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></div>
            <div class="breadcrumb-item"><a href="#">{{ $client->name }}</a></div>
        </div>
    </div>

    <div class="section-body">
        <!-- Date Filter Form -->
        <form action="{{ route('reports.client_specific', ['id' => $client->id]) }}" method="GET">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" class="form-control flatpickr" value="{{ $start_date }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" class="form-control flatpickr" value="{{ $end_date }}">
                </div>
                <div class="form-group col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>

        <!-- Display the Report -->
        <div id="reportContent" class="card">
            <div class="card-header">
                <h4>{{ $client->name }}'s Transaction Report
                    @if($start_date && $end_date)
                        from {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                    @else
                        (All Time)
                    @endif
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Debit (Previous)</th>
                                <th>Credit (Previous)</th>
                                <th>Debit (Current)</th>
                                <th>Credit (Current)</th>
                                <th>Balance</th>
                                <th>Description</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $runningBalance = $previousTotals ? $previousTotals->prev_debit - $previousTotals->prev_credit : 0;
                            @endphp

                            <!-- Previous Totals Row -->
                            @if($previousTotals)
                                <tr class="table-secondary">
                                    {{-- <td colspan="2"><strong>Previous Totals (Before {{ $start_date }})</strong></td> --}}
                                </tr>
                            @endif

                            <!-- Current Period Transactions -->
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction->journal->trans_date)->format('d/m/Y') }}</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>{{ number_format($transaction->dc_indicator === 'D' ? $transaction->amount : 0, 3) }}</td>
                                    <td>{{ number_format($transaction->dc_indicator === 'C' ? $transaction->amount : 0, 3) }}</td>
                                    <td>{{ number_format($runningBalance += $transaction->dc_indicator === 'D' ? $transaction->amount : -$transaction->amount, 3) }}</td>
                                     <td> {{$transaction->description}} </td>
                                     <td>{{ $transaction->reference}}  </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Current Period Totals</th>
                                <th>0</th>
                                <th>0</th>
                                <th>{{ number_format($currentTotals->current_debit, 3) }}</th>
                                <th>{{ number_format($currentTotals->current_credit, 3) }}</th>
                                <th>{{ number_format($currentTotals->balance, 3) }}</th>
                                  
                            </tr>

                            <tr>
                                <th>Grand Total (All Time)</th>
                                <th>{{ number_format($previousTotals->prev_debit - $previousTotals->prev_credit ?? 0, 3) }}</th>
                                <th>0</th>
                                <th>{{ number_format($currentTotals->current_debit, 3) }}</th>
                                <th>{{ number_format($currentTotals->current_credit, 3) }}</th>
                                <th>{{ number_format(($previousTotals->prev_debit  - $previousTotals->prev_credit + $currentTotals->balance), 3) }}</th>
                                 
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <button class="btn btn-secondary mt-4" onclick="printSection('reportContent')">Print Report</button>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function printSection(divId) {
    var content = document.getElementById(divId).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = originalContent;
}

document.addEventListener('DOMContentLoaded', function() {
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        allowInput: true
    });
});
</script>
@endpush
