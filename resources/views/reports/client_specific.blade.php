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
                        <input type="date" name="start_date" class="form-control flatpickr">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control flatpickr">
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
                    <h4>{{ $client->name }}'s Transaction Report from
                        {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} to
                        {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Debit (Due)</th>
                                    <th>Credit (Paid)</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $runningBalance = 0;
                                @endphp
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($transaction->journal->trans_date)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ number_format($transaction->dc_indicator === 'D' ? $transaction->amount : 0, 2) }}
                                        </td>
                                        <td>{{ number_format($transaction->dc_indicator === 'C' ? $transaction->amount : 0, 2) }}
                                        </td>
                                        <td>{{ number_format($runningBalance += $transaction->dc_indicator === 'D' ? $transaction->amount : -$transaction->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th>{{ number_format($balances->total_due, 2) }}</th>
                                    <th>{{ number_format($balances->total_paid, 2) }}</th>
                                    <th>{{ number_format($balances->total_due - $balances->total_paid, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Print Button at the Bottom -->
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

            // After printing, restore the original content
            document.body.innerHTML = originalContent;
        }

        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".flatpickr", {
                dateFormat: "Y-m-d", // This goes to the backend (form submit)
                altInput: true, // Enables visible user-friendly display
                altFormat: "d/m/Y", // What the user sees
                allowInput: true
            });
        });
    </script>
@endpush
