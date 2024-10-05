@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('currencies.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Currency Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Currencies</a></div>
                <div class="breadcrumb-item">Currency: {{ $currency->currency_code }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Currency #{{ $currency->currency_code }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Currency Code</th>
                            <td>{{ $currency->currency_code }}</td>
                        </tr>
                        <tr>
                            <th>Currency Name</th>
                            <td>{{ $currency->currency_name }}</td>
                        </tr>
                        <tr>
                            <th>Exchange Rate</th>
                            <td>{{ $currency->exchange_rate }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
