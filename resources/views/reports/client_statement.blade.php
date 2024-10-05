@extends('layouts.master')

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
            <div id="statementContent" class="card">
                <div class="card-header">
                    <h4>Clients Who Owe Money</h4>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($balances as $balance)
                                    <tr>
                                        <td>{{ $balance->thirdParty ? $balance->thirdParty->name : 'Unknown Client' }}</td>
                                        <td>{{ number_format($balance->total_debit, 2) }}</td>
                                        <td>{{ number_format($balance->total_credit, 2) }}</td>
                                        <td>{{ number_format($balance->total_debit - $balance->total_credit, 2) }}</td>
                                        <td>
                                            @if ($balance->thirdParty)
                                                <a href="{{ route('reports.client_specific', ['id' => $balance->thirdParty->id]) }}"
                                                    class="btn btn-info">
                                                    View Detailed Report
                                                </a>
                                            @else
                                                <span class="text-muted">No client data available</span>
                                            @endif
                                        </td>
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
                        <p>No clients owe money.</p>
                    @endif
                </div>
            </div>

            <button class="btn btn-secondary mt-4" onclick="printSection('statementContent')">Print Statement</button>
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
