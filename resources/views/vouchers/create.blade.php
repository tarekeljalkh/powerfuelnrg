@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('vouchers.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Add New Voucher</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('vouchers.index') }}">Vouchers</a></div>
            <div class="breadcrumb-item"><a href="#">Add New Voucher</a></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('vouchers.store') }}" method="post" class="needs-validation" novalidate="">
                        @csrf

                        <div class="card-header">
                            <h4>Transaction Details</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Transaction Type</label>
                                    <select name="type_id" class="form-control select2 @error('type_id') is-invalid @enderror" required="">
                                        @foreach($transactionTypes as $transactionType)
                                            <option value="{{ $transactionType->id }}" {{ old('type_id') == $transactionType->id ? 'selected' : '' }}>
                                                {{ $transactionType->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Transaction Reference</label>
                                    <input type="text" name="trx_ref" class="form-control" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Transaction Date</label>
                                    <input type="date" name="trx_date" class="form-control" value="{{ old('trx_date', now()->toDateString()) }}" required="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Activation Date</label>
                                    <input type="date" name="activation_date" class="form-control" value="{{ old('activation_date', now()->toDateString()) }}" required="">
                                </div>
                            </div>

                            <!-- Line Items Section -->
                            <div class="form-group">
                                <label>Line Items</label>
                                <table class="table table-bordered" id="line-items-table">
                                    <thead>
                                        <tr>
                                            <th>Account</th>
                                            <th>Currency</th>
                                            <th>Reference</th>
                                            <th>Description</th>
                                            <th>Debit/Credit</th>
                                            <th>Amount</th>
                                            <th>Third Party</th>
                                            <th><button type="button" class="btn btn-success add-row">+</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="line_items[0][account]" class="form-control" required="">
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->account_code}}">{{ $account->account_code }} {{ $account->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="line_items[0][currency]" class="form-control" required="">
                                                    @foreach($currencies as $currency)
                                                        <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="line_items[0][reference]" class="form-control"></td>
                                            <td><input type="text" name="line_items[0][description]" class="form-control"></td>
                                            <td>
                                                <select name="line_items[0][dc_indicator]" class="form-control" required="">
                                                    <option value="D">Debit</option>
                                                    <option value="C">Credit</option>
                                                </select>
                                            </td>
                                            <td><input type="number" name="line_items[0][amount]" class="form-control" step="0.01" required=""></td>
                                            <td>
                                                <select name="line_items[0][third_party_id]" class="form-control">
                                                    @foreach($thirdParties as $thirdParty)
                                                        <option value="{{ $thirdParty->id }}">{{ $thirdParty->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <button class="btn btn-primary" id="submit-btn" onclick="this.disabled=true;this.form.submit();">Add Voucher</button>
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
        let rowIndex = 1;

        // Add new line item row
        $('.add-row').on('click', function() {
            $('#line-items-table tbody').append(`
                <tr>
                    <td>
                        <select name="line_items[${rowIndex}][account]" class="form-control" required="">
                            @foreach($accounts as $account)
                                <option value="{{ $account->account_code }}">{{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="line_items[${rowIndex}][currency]" class="form-control" required="">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="line_items[${rowIndex}][reference]" class="form-control"></td>
                    <td><input type="text" name="line_items[${rowIndex}][description]" class="form-control"></td>
                    <td>
                        <select name="line_items[${rowIndex}][dc_indicator]" class="form-control" required="">
                            <option value="D">Debit</option>
                            <option value="C">Credit</option>
                        </select>
                    </td>
                    <td><input type="number" name="line_items[${rowIndex}][amount]" class="form-control" step="0.01" required=""></td>
                    <td>
                        <select name="line_items[${rowIndex}][third_party_id]" class="form-control">
                            @foreach($thirdParties as $thirdParty)
                                <option value="{{ $thirdParty->id }}">{{ $thirdParty->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                </tr>
            `);
            rowIndex++;
        });

        // Remove line item row
        $('#line-items-table').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush
