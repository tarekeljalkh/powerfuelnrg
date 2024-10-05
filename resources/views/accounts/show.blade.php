@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('accounts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Account Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></div>
            <div class="breadcrumb-item">Account: {{ $account->account_code }}</div>
        </div>
</div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Account #{{ $account->account_code }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Account Code</th>
                        <td>{{ $account->account_code }}</td>
                    </tr>
                    <tr>
                        <th>Account Name</th>
                        <td>{{ $account->account_name }}</td>
                    </tr>
                    <tr>
                        <th>Account Type</th>
                        <td>{{ $account->account_type }}</td>
                    </tr>
                    <tr>
                        <th>Currency Code</th>
                        <td>{{ $account->currency_code }}</td>
                    </tr>
                    <tr>
                        <th>Is Active</th>
                        <td>{{ $account->is_active ? 'Yes' : 'No' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
