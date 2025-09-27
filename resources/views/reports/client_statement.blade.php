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
                        <input type="date" name="start_date" class="form-control flatpickr"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control flatpickr"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </form>

            <div id="statementContent" class="card">
                <div class="card-header">
                    <h4>
                        Clients Who Owe Money
                        @if (request('start_date') && request('end_date'))
                            from {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                            to {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if (isset($balances) && !$balances->isEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Balance (Range)</th>
                                        <th>Credit (Range)</th>
                                        <th>Debit (Range)</th>
                                        <th>Grand Credit (All Time)</th>
                                        <th>Grand Debit (All Time)</th>
                                        <th>Grand Balance (All Time)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($balances as $balance)
                                        <tr>
                                            <td>{{ $balance->thirdParty?->name ?? 'Unknown Client' }}</td>

                                            <!-- Balance within selected date range -->
                                            <td>{{ number_format($balance->balance_range, 2) }}</td>

                                            {{-- <!-- Totals for selected date range -->
                                            <td>{{ number_format($balance->total_credit_range, 2) }}</td>
                                            <td>{{ number_format($balance->total_debit_range, 2) }}</td> --}}

                                            <!-- Grand totals (all time) -->
                                            <td>{{ number_format($balance->grand_total_credit, 2) }}</td>
                                            <td>{{ number_format($balance->grand_total_debit, 2) }}</td>
                                            <td>{{ number_format($balance->grand_balance, 2) }}</td>

                                            <td>
                                                @if ($balance->thirdParty)
                                                    <a href="{{ route('reports.client_specific', ['id' => $balance->thirdParty->id]) }}"
                                                        class="btn btn-info">View Detailed Report</a>
                                                @else
                                                    <span class="text-muted">No client data available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
