@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">

            {{-- Clients Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('clients.index') }}" style="text-decoration:none; color: inherit;">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Clients</h4>
                            </div>
                            <div class="card-body">
                                {{ $clients }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Clients Card --}}

            {{-- Clients Statements Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('reports.client_statement') }}" style="text-decoration:none; color: inherit;">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pending Balance</h4>
                            </div>
                            <div class="card-body">
                                {{ $balances->count() }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Clients Statements Card --}}


            {{-- Vouchers Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('vouchers.index') }}" style="text-decoration:none; color: inherit;">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Vouchers</h4>
                            </div>
                            <div class="card-body">
                                {{ $vouchers }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Vouchers Card --}}


            {{-- Receipts Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('receipts.index') }}" style="text-decoration:none; color: inherit;">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Receipts</h4>
                            </div>
                            <div class="card-body">
                                {{ $receipts }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Receipts Card --}}


        </div>
        <div class="section-body">
        </div>
    </section>
@endsection
