@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('orders.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Add New Order</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></div>
                <div class="breadcrumb-item">Add New Order</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('orders.store') }}" method="post" class="needs-validation" novalidate>
                            @csrf

                            <div class="card-header">
                                <h4>Add New Order</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Client</label>
                                        <select name="client_id" id="client_id"
                                            class="form-control select2 @error('client_id') is-invalid @enderror" required>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->first_name }} {{ $client->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Fuel Type</label>
                                        <select name="inventory_id" id="inventory_id"
                                            class="form-control select2 @error('inventory_id') is-invalid @enderror"
                                            required>
                                            @foreach ($inventories as $inventory)
                                                <option value="{{ $inventory->id }}"
                                                    {{ old('inventory_id') == $inventory->id ? 'selected' : '' }}>
                                                    {{ $inventory->fuel_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('inventory_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity"
                                            class="form-control @error('quantity') is-invalid @enderror" required
                                            value="{{ old('quantity') }}" min="0">
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Price per Unit</label>
                                        <input type="number" name="price"
                                            class="form-control @error('price') is-invalid @enderror" required
                                            value="{{ old('price') }}" step="0.01" min="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Order Date</label>
                                        <input type="date" name="order_date"
                                            class="form-control @error('order_date') is-invalid @enderror" required
                                            value="{{ old('order_date', date('Y-m-d')) }}">
                                        @error('order_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Status</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror"
                                            required>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                                Completed</option>
                                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Add Order</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Order Section --}}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // If you add any select fields in the future, initialize Select2 here
        $('.select2').select2();
    </script>
@endpush
