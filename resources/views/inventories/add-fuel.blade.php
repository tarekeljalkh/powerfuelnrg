@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('inventories.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Add Fuel</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></div>
            <div class="breadcrumb-item">Add Fuel</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Fuel to Inventory</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventories.addFuel', $inventory->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="quantity">Fuel Quantity</label>
                                <input type="number" name="quantity" class="form-control" required min="0">
                            </div>
                            <div class="form-group">
                                <label for="transaction_date">Transaction Date</label>
                                <input type="date" name="transaction_date" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Fuel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
