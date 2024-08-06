@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('orders.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Order Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></div>
                <div class="breadcrumb-item">Order #{{ $order->id }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Client Name</label>
                                        <p>{{ $order->client->first_name }} {{ $order->client->last_name }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Fuel Type</label>
                                        <p>{{ $order->inventory->fuel_type }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <p>{{ $order->quantity }} liters</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price per Unit</label>
                                        <p>${{ number_format($order->price, 2) }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Total Price</label>
                                        <p>${{ number_format($order->total, 2) }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Order Date</label>
                                        <p>{{ $order->order_date->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <p class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">Edit Order</a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
