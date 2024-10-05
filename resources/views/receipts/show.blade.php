@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('receipts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Receipt Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></div>
                <div class="breadcrumb-item">Receipt: {{ $receipt->receipt_number }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Receipt #{{ $receipt->receipt_number }}</h4>
                </div>
                <div class="card-body">
                    <!-- Receipt Details -->
                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction Code</th>
                            <td>{{ $receipt->journal->trans_code ?? 'N/A' }}</td> <!-- Display transaction code -->
                        </tr>
                        <tr>
                            <th>Client (Third Party)</th>
                            <td>{{ $receipt->thirdParty->name ?? 'None' }}</td> <!-- Display client/third party -->
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ number_format($receipt->amount, 2) }}</td> <!-- Format amount to 2 decimals -->
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ \Carbon\Carbon::parse($receipt->date)->format('Y-m-d') }}</td> <!-- Format date -->
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ $receipt->payment_method ?? 'N/A' }}</td> <!-- Handle null payment method -->
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $receipt->creator->name ?? 'System' }}</td> <!-- Display creator -->
                        </tr>
                    </table>

                    <!-- Allocations or Other Relevant Details (Optional Section) -->
                    <!-- If there are allocations or additional details for the receipt, you can add them here -->
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('receipts.edit', $receipt->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
