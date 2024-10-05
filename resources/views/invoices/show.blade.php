@extends('layouts.master')

@push('styles')
<style>
    @media print {
        @page {
            size: A4;
            margin: 20mm;
        }

        #invoiceContent {
            width: 100%;
        }

        body * {
            visibility: hidden;
        }

        #invoiceContent, #invoiceContent * {
            visibility: visible;
        }

        #invoiceContent {
            position: absolute;
            left: 0;
            top: 0;
        }

        .btn {
            display: none !important;
        }
    }

    .invoice-header {
        margin-bottom: 20px;
    }

    .invoice-header h2 {
        margin-top: 0;
    }

    .invoice-details {
        margin-bottom: 20px;
    }

    .invoice-table th, .invoice-table td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Invoice for {{ $client->name }}</h1>
    </div>

    <div class="section-body">
        <div id="invoiceContent" class="card">
            <div class="card-header invoice-header">
                <h2>Invoice #{{ $invoices->first()->invoice_number ?? 'N/A' }}</h2>
                <p>Date: {{ now()->toDateString() }}</p>
                <p>Client: {{ $client->name }}</p>
                <p>Address: {{ $client->address }}</p>
            </div>

            <div class="card-body">
                <div class="invoice-details">
                    <table class="table invoice-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lineItems as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="invoice-total">
                    <h4>Total: ${{ number_format($lineItems->sum('total'), 2) }}</h4>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <button class="btn btn-secondary mt-4" onclick="printSection('invoiceContent')">Print Invoice</button>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function printSection(divId) {
        var content = document.getElementById(divId).innerHTML;
        var printWindow = window.open('', '_blank', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset("assets/modules/bootstrap/css/bootstrap.min.css") }}" />');
        printWindow.document.write('<style>@media print { @page { size: A4; margin: 20mm; } }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>
@endpush
