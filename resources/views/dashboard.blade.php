@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route('clients.index') }}"
                    style="text-decoration:none; color: inherit;">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i>
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

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#"
                style="text-decoration:none; color: inherit;">

                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>#</h4>
                        </div>
                        <div class="card-body">
                            #
                        </div>
                    </div>
                </div>
            </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#"
                style="text-decoration:none; color: inherit;">

                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-vials"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>#</h4>
                        </div>
                        <div class="card-body">
                            #
                        </div>
                    </div>
                </div>
            </a>
            </div>
        </div>
        <div class="section-body">
        </div>
    </section>
@endsection
