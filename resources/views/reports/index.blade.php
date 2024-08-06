@extends('layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Reports</h1>
    </div>
    <div class="section-body">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Order Reports</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Client</th>
                                        <th>Fuel Type</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->client->first_name }} {{ $order->client->last_name }}</td>
                                            <td>{{ $order->inventory->fuel_type }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>{{ $order->order_date->format('Y-m-d') }}</td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>
                                                <a href="{{ route('reports.generate-invoice', $order->id) }}" class="btn btn-sm btn-info">Print Invoice</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
