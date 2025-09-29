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
                            <td>{{ $receipt->journal->trans_code ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ \Carbon\Carbon::parse($receipt->date)->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ $receipt->payment_method ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $receipt->creator ?? 'System' }}</td>
                        </tr>
                    </table>

                    <!-- Journal Line Items -->
                    <h5 class="mt-4">Journal Line Items</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Account Code</th>
                                <th>Third Party</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($receipt->lineItems as $item)
                                <tr>
                                    <td>{{ $item->account_code }}</td>
                                    <td>{{ $item->thirdParty->name ?? 'N/A' }}</td>
                                    <td>{{ $item->dc_indicator === 'D' ? number_format($item->amount, 2) : '-' }}</td>
                                    <td>{{ $item->dc_indicator === 'C' ? number_format($item->amount, 2) : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No line items found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('receipts.edit', $receipt) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('receipts.destroy', $receipt) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
