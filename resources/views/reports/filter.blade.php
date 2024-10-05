@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('reports.filter') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Filter Client Balance Report</h1>
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
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request()->get('start_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request()->get('end_date', now()->toDateString()) }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>

            <!-- Display the Report -->
            <div id="reportContent" class="card mt-3">
                <div class="card-header">
                    <h4>Client Balance Report from {{ request()->get('start_date', now()->toDateString()) }} to
                        {{ request()->get('end_date', now()->toDateString()) }}</h4>
                </div>
                <div class="card-body">
                    @if (isset($balances) && !$balances->isEmpty())
                        @php
                            $totalDebit = 0;
                            $totalCredit = 0;
                            $totalBalance = 0;
                        @endphp
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Total Debit</th>
                                    <th>Total Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($balances as $balance)
                                    <tr>
                                        <td>
                                            {{ $balance->thirdParty ? $balance->thirdParty->name : 'Unknown Client' }}
                                        </td>
                                        <td>{{ number_format($balance->total_debit, 2) }}</td>
                                        <td>{{ number_format($balance->total_credit, 2) }}</td>
                                        <td>{{ number_format($balance->total_debit - $balance->total_credit, 2) }}</td>
                                    </tr>
                                    @php
                                        $totalDebit += $balance->total_debit;
                                        $totalCredit += $balance->total_credit;
                                        $totalBalance += $balance->total_debit - $balance->total_credit;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>{{ number_format($totalDebit, 2) }}</th>
                                    <th>{{ number_format($totalCredit, 2) }}</th>
                                    <th>{{ number_format($totalBalance, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p>No data found for the selected date range.</p>
                    @endif
                </div>
            </div>

            <!-- Move the Print Button to the Bottom -->
            <button class="btn btn-secondary mt-4" onclick="printSection('reportContent')">Print Report</button>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function printSection(divId) {
            var content = document.getElementById(divId).innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = content;
            window.print();

            // After printing, restore the original content
            document.body.innerHTML = originalContent;
        }
    </script>
@endpush
