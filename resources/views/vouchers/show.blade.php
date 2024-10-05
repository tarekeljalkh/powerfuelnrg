@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('vouchers.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Voucher Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('vouchers.index') }}">Vouchers</a></div>
                <div class="breadcrumb-item">Voucher: {{ $voucher->trans_id }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Voucher #{{ $voucher->trans_id }}</h4>
                </div>
                <div class="card-body">
                    <!-- Transaction Details -->
                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction Code</th>
                            <td>{{ $voucher->trans_code }}</td>
                        </tr>
                        <tr>
                            <th>Manual Reference</th>
                            <td>{{ $voucher->manual_ref }}</td>
                        </tr>
                        <tr>
                            <th>Transaction Date</th>
                            <td>{{ \Carbon\Carbon::parse($voucher->trans_date)->format('Y-m-d') }}</td>
                            <!-- Format the date -->
                        </tr>
                        <tr>
                            <th>Activation Date</th>
                            <td>{{ \Carbon\Carbon::parse($voucher->activation_date)->format('Y-m-d') }}</td>
                            <!-- Format the date -->
                        </tr>
                        <tr>
                            <th>Transaction Type</th>
                            <td>{{ $voucher->transactionType->description }}</td>
                            <!-- Assuming there's a relation to get transaction type -->
                        </tr>
                        <!-- Add more fields as needed -->
                    </table>

                    <!-- Line Items Section -->
                    <h5>Line Items</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Account Code</th>
                                <th>Aux</th>
                                <th>Currency</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Debit/Credit</th>
                                <th>Third Party</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($voucher->lineItems as $item)
                                <tr>
                                    <td>{{ $item->account_code }}</td>
                                    <td>{{ $item->aux ?? 'N/A' }}</td> <!-- Handle optional auxiliary field -->
                                    <td>{{ $item->currency }}</td> <!-- Display currency -->
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->dc_indicator == 'D' ? 'Debit' : 'Credit' }}</td>
                                    <!-- Display Debit/Credit -->
                                    <td>{{ optional($item->thirdParty)->name ?? 'None' }}</td>
                                    <!-- Handle third party name if exists -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
