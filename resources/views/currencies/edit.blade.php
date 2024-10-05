@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('currencies.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Currency</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Currencies</a></div>
            <div class="breadcrumb-item"><a href="#">Edit Currency</a></div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('currencies.update', $currency->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Currency Code</label>
                        <input type="text" name="currency_code" class="form-control" value="{{ $currency->currency_code }}" required>
                    </div>

                    <div class="form-group">
                        <label>Currency Name</label>
                        <input type="text" name="currency_name" class="form-control" value="{{ $currency->currency_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Exchange Rate</label>
                        <input type="number" name="exchange_rate" class="form-control" step="0.000001" value="{{ $currency->exchange_rate }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Currency</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
