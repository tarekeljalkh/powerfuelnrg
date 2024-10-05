@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('receipts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Receipt</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></div>
            <div class="breadcrumb-item">Edit Receipt</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('receipts.update', $receipt->trans_id) }}" method="post" class="needs-validation" novalidate="">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Receipt Details</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Voucher (Journal) Dropdown -->
                                <div class="form-group col-md-6">
                                    <label>Voucher (Journal)</label>
                                    <select name="trans_id" class="form-control select2 @error('trans_id') is-invalid @enderror" required>
                                        <option value="">Select Voucher</option>
                                        @foreach($journals as $journal)
                                            <option value="{{ $journal->trans_id }}" {{ $receipt->trans_id == $journal->trans_id ? 'selected' : '' }}>
                                                {{ $journal->trans_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('trans_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Amount Input -->
                                <div class="form-group col-md-6">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ $receipt->amount }}" step="0.01" required>
                                    @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date and Payment Method -->
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($receipt->date)->format('Y-m-d') }}" required>
                                    @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Payment Method</label>
                                    <input type="text" name="payment_method" class="form-control" value="{{ $receipt->payment_method }}">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button class="btn btn-primary" id="submit-btn" onclick="this.disabled=true;this.form.submit();">Update Receipt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Any specific JavaScript related to the receipt edit can go here
    });
</script>
@endpush
