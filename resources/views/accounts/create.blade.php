@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('accounts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Add New Account</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></div>
            <div class="breadcrumb-item"><a href="#">Add New Account</a></div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('accounts.store') }}" method="post" class="needs-validation" novalidate="">
                    @csrf
                    <div class="form-group">
                        <label>Account Name</label>
                        <input type="text" name="account_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" name="account_type" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Currency Code</label>
                        <select name="currency_code" class="form-control" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->currency_code }}">{{ $currency->currency_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Is Active</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Account</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
