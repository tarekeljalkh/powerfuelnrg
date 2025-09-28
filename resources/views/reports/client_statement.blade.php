@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Client Statement (Clients Who Owe Money)</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('reports.filter') }}">Reports</a></div>
        </div>
    </div>

    <div class="section-body">

        <!-- Date Filter Form -->
        <form action="{{ route('reports.client_statement') }}" method="GET" class="mb-4">
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

        <!-- Statement Table -->
        <div id="statementContent" class="card">
            <div class="card-header">
                <h4>
                    Clients Who Owe Money
                    @if($start_date && $end_date)
                        from {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        to {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                    @endif
                </h4>
            </div>
            <div class="card-body">
                @if ($balances && $balances->isNotEmpty())
                    @php
                        $grandDebit = 0;
                        $grandCredit = 0;
                        $grandBalance = 0;
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Previous Debit</th>
                                    <th>Previous Credit</th>
                                    <th>Current Debit</th>
                                    <th>Current Credit</th>
                                    <th>Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($balances as $balance)
    @php
        $prev = $previousTotals[$balance->third_party_id] ?? null;
        $prevDebit = $prev->prev_debit ?? 0;
        $prevCredit = $prev->prev_credit ?? 0;

        $currDebit = $balance->total_debit ?? 0;
        $currCredit = $balance->total_credit ?? 0;
        $currBalance = $balance->balance ?? 0;

        if ($currDebit == 0 && $currCredit == 0 && $currBalance == 0) continue;

        $grandDebit += $currDebit;
        $grandCredit += $currCredit;
        $grandBalance += $currBalance;
    @endphp
    <tr>
        <td>{{ $balance->client_name }}</td>
        <td>{{ number_format($prevDebit - $prevCredit, 3) }}</td>
        <td>0</td>
        <td>{{ number_format($currDebit, 3) }}</td>
        <td>{{ number_format($currCredit, 3) }}</td>
        <td>{{ number_format($currBalance, 3) }}</td>
        <td>
            <a href="{{ route('reports.client_specific', ['id' => $balance->third_party_id]) }}" class="btn btn-info">
                View Detailed Report
            </a>
        </td>
    </tr>
@endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th>{{ number_format($grandDebit, 2) }}</th>
                                    <th>{{ number_format($grandCredit, 2) }}</th>
                                    <th>{{ number_format($grandBalance, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p>No clients owe money for the selected date range.</p>
                @endif
            </div>
        </div>

        <button class="btn btn-secondary mt-4" onclick="printSection('statementContent')">Print Statement</button>
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
