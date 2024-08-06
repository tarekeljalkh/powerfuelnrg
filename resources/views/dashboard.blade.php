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
                        <div class="card-icon bg-warning">
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

            {{-- Suppliers Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('suppliers.index') }}" style="text-decoration:none; color: inherit;">

                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Suppliers</h4>
                            </div>
                            <div class="card-body">
                                {{ $suppliers }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Suppliers Card --}}

            {{-- Inventory Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('inventories.index') }}" style="text-decoration:none; color: inherit;">

                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Inventory</h4>
                            </div>
                            <div class="card-body">
                                {{ $inventories }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Inventory Card --}}


            {{-- Orders Card --}}
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('orders.index') }}" style="text-decoration:none; color: inherit;">

                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Orders</h4>
                            </div>
                            <div class="card-body">
                                {{ $orders }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- End Orders Card --}}

                        {{-- Reports Card --}}
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('reports.index') }}" style="text-decoration:none; color: inherit;">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-info">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Reports</h4>
                                        </div>
                                        <div class="card-body">
                                            <!-- You can display a summary here if needed -->
                                            <!-- For example, total number of reports or insights -->
                                            Reports
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {{-- End Reports Card --}}


        </div>
        <div class="section-body">
        </div>
    </section>
@endsection
