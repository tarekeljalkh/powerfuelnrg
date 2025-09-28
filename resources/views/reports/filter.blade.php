@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('reports.filter') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Client Balance Report</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('reports.filter') }}">Reports</a></div>
            </div>
        </div>

        <div class="section-body">
            <!-- Date Filter Form -->
            <form action="{{ route('reports.client_balance') }}" method="GET">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="start_date">Start Date</label>
                        <input type="text" name="start_date" class="form-control flatpickr"
                            value="{{ request()->get('start_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">End Date</label>
                        <input type="text" name="end_date" class="form-control flatpickr"
                            value="{{ request()->get('end_date', now()->toDateString()) }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>

            <!-- Report Content -->
            <div id="reportContent" class="card mt-3">
                <div class="card-header">
                    <h4>Client Balances ({{ request()->get('start_date', now()->toDateString()) }} →
                        {{ request()->get('end_date', now()->toDateString()) }})</h4>
                </div>
                <div class="card-body">
                    @if(isset($balances) && !$balances->isEmpty())
                        @php
                            $grandPrev = $grandCurrDebit = $grandCurrCredit = $grandCurrBalance = $grandTotal = 0;
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Previous Credit - Debit</th>
                                        <th>Current Debit</th>
                                        <th>Current Credit</th>
                                        <th>Balance (Current)</th>
                                        <th>Grand Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($balances as $balance)
                                        @php
                                            $prevBalance = ($balance->previousTotals->prev_credit ?? 0) - ($balance->previousTotals->prev_debit ?? 0);
                                            $currDebit = $balance->currentTotals->current_debit ?? 0;
                                            $currCredit = $balance->currentTotals->current_credit ?? 0;
                                            $currBalance = $balance->currentTotals->balance ?? 0;
                                            $grandBalance = $prevBalance + $currBalance;

                                            $grandPrev += $prevBalance;
                                            $grandCurrDebit += $currDebit;
                                            $grandCurrCredit += $currCredit;
                                            $grandCurrBalance += $currBalance;
                                            $grandTotal += $grandBalance;
                                        @endphp
                                        <tr>
                                            <td>{{ $balance->thirdParty ? $balance->thirdParty->name : 'Unknown Client' }}</td>
                                            <td>{{ number_format($prevBalance, 2) }}</td>
                                            <td>{{ number_format($currDebit, 2) }}</td>
                                            <td>{{ number_format($currCredit, 2) }}</td>
                                            <td>{{ number_format($currBalance, 2) }}</td>
                                            <td>{{ number_format($grandBalance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>{{ number_format($grandPrev, 2) }}</th>
                                        <th>{{ number_format($grandCurrDebit, 2) }}</th>
                                        <th>{{ number_format($grandCurrCredit, 2) }}</th>
                                        <th>{{ number_format($grandCurrBalance, 2) }}</th>
                                        <th>{{ number_format($grandTotal, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p>No data found for this date range.</p>
                    @endif
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
