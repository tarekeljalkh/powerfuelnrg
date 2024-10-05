@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('accounts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Account</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></div>
            <div class="breadcrumb-item"><a href="#">Edit Account</a></div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('accounts.update', $account->account_code) }}" method="post" class="needs-validation" novalidate="">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Account Name</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $account->account_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" name="account_type" class="form-control" value="{{ $account->account_type }}">
                    </div>

                    <div class="form-group">
                        <label>Currency Code</label>
                        <select name="currency_code" class="form-control" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->currency_code }}" {{ $account->currency_code == $currency->currency_code ? 'selected' : '' }}>{{ $currency->currency_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Is Active</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ $account->is_active ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$account->is_active ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Account</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
