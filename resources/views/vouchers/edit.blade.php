@extends('layouts.master')

@push('styles')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* GLOBAL RESET */
        html,
        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .main-content,
        .section,
        .container,
        .card,
        .section-body,
        .row,
        .col-12 {
            max-width: 100%;
            overflow-x: visible;
        }

        /* SELECT2 Styling */
        .select2 {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 48px !important;
            padding: 8px 12px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .select2-selection__rendered {
            line-height: 1.5 !important;
        }

        .select2-selection__arrow {
            height: 48px !important;
        }

        /* TABLE STYLING */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #line-items-table {
            width: 100%;
            min-width: 900px;
            /* force horizontal scroll when needed */
        }

        #line-items-table th,
        #line-items-table td {
            white-space: nowrap;
            font-size: 14px;
            vertical-align: middle;
        }

        #line-items-table select,
        #line-items-table input {
            font-size: 14px;
            height: 42px;
            padding: 6px 10px;
        }

        /* BUTTONS */
        .btn {
            font-size: 14px;
            padding: 8px 16px;
        }

        /* MOBILE */
        @media (max-width: 768px) {

            .card-header,
            .card-body,
            .card-footer {
                padding: 1rem !important;
            }

            .table-responsive {
                margin-bottom: 1rem;
            }

            #line-items-table {
                min-width: 768px;
            }
        }
    </style>
@endpush


@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('vouchers.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Voucher</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('vouchers.index') }}">Vouchers</a></div>
                <div class="breadcrumb-item">Edit Voucher</div>
            </div>
        </div>

        <div class="section-body zoom-50">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('vouchers.update', $voucher->trans_id) }}" method="post"
                            class="needs-validation" novalidate="">
                            @csrf
                            @method('PUT')

                            <div class="card-header">
                                <h4>Transaction Details</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Transaction Type</label>
                                        <select name="type_id"
                                            class="form-control select2 @error('type_id') is-invalid @enderror"
                                            required="">
                                            @foreach ($transactionTypes as $transactionType)
                                                <option value="{{ $transactionType->id }}"
                                                    {{ $voucher->type_id == $transactionType->id ? 'selected' : '' }}>
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
                                        <input type="text" name="trx_ref" class="form-control"
                                            value="{{ old('trx_ref', $voucher->manual_ref) }}" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Transaction Date</label>
                                        <input type="date" name="trx_date" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($voucher->trans_date)->format('Y-m-d') }}"
                                            required="">
                                    </div>
                                </div>

                                <!-- Line Items Section -->
                                <div class="form-group">
                                    <label>Line Items</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="line-items-table">
                                            <thead>
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Third Party</th>
                                                    <th>VAT</th>
                                                    <th>Description</th>
                                                    <th>Debit</th>
                                                    <th>Amount</th>
                                                    <th>Currency</th>
                                                    <th><button type="button" class="btn btn-success add-row">+</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($voucher->lineItems as $index => $item)
                                                    <tr>
                                                        <td>
                                                            <select name="line_items[{{ $index }}][account]"
                                                                class="form-control select2" required="">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->account_code }}"
                                                                        {{ $item->account_code == $account->account_code ? 'selected' : '' }}>
                                                                        {{ $account->account_code }}
                                                                        {{ $account->account_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="line_items[{{ $index }}][third_party_id]"
                                                                class="form-control select2">
                                                                @foreach ($thirdParties as $thirdParty)
                                                                    <option value="{{ $thirdParty->id }}"
                                                                        {{ $item->third_party_id == $thirdParty->id ? 'selected' : '' }}>
                                                                        {{ $thirdParty->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text"
                                                                name="line_items[{{ $index }}][reference]"
                                                                class="form-control" value="{{ $item->reference }}"></td>
                                                        <td><input type="text"
                                                                name="line_items[{{ $index }}][description]"
                                                                class="form-control" value="{{ $item->description }}"></td>
                                                        <td>
                                                            <select name="line_items[{{ $index }}][dc_indicator]"
                                                                class="form-control" required="">
                                                                <option value="D"
                                                                    {{ $item->dc_indicator == 'D' ? 'selected' : '' }}>
                                                                    Debit</option>
                                                                 
                                                            </select>
                                                        </td>
                                                        <td><input type="number"
                                                                name="line_items[{{ $index }}][amount]"
                                                                class="form-control" value="{{ $item->amount }}"
                                                                step="0.01" required=""></td>
                                                        
                                                        <td>
                                                            <select name="line_items[{{ $index }}][currency]"
                                                                class="form-control" required="">
                                                                @foreach ($currencies as $currency)
                                                                    <option value="{{ $currency->currency_code }}"
                                                                        {{ $item->currency == $currency->currency_code ? 'selected' : '' }}>
                                                                        {{ $currency->currency_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><button type="button"
                                                                class="btn btn-danger remove-row">-</button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-primary" id="submit-btn"
                                    onclick="this.disabled=true;this.form.submit();">Update Voucher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            let rowIndex = {{ count($voucher->lineItems) }};

            // Add new line item row
            $('.add-row').on('click', function() {
                $('#line-items-table tbody').append(`
                <tr>
                    <td>
                        <select name="line_items[${rowIndex}][account]" class="form-control" required="">
                            @foreach ($accounts as $account)
                                <option value="{{ $account->account_code }}">{{ $account->account_code }} {{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="line_items[${rowIndex}][currency]" class="form-control" required="">
                            @foreach ($currencies as $currency)
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
                            @foreach ($thirdParties as $thirdParty)
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
