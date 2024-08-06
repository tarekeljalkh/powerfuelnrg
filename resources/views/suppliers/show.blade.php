@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('suppliers.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Supplier Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></div>
                <div class="breadcrumb-item">Supplier: {{ $supplier->name }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h4>Supplier Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier Name</label>
                                        <p>{{ $supplier->name }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <p>{{ $supplier->email }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <p>{{ $supplier->contact_number }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <p>{{ $supplier->address }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Company</label>
                                        <p>{{ $supplier->company }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Joined Date</label>
                                        <p>{{ $supplier->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Supplied Inventory</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="inventory-table" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Inventory ID</th>
                                            <th>Fuel Type</th>
                                            <th>Quantity Available</th>
                                            <th>Price per Unit</th>
                                            <th>Added Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supplier->inventories as $inventory)
                                            <tr>
                                                <td>{{ $inventory->id }}</td>
                                                <td>{{ $inventory->fuel_type }}</td>
                                                <td>{{ $inventory->quantity }} liters</td>
                                                <td>${{ number_format($inventory->price, 2) }}</td>
                                                <td>{{ $inventory->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('inventories.show', $inventory->id) }}" class="btn btn-info btn-sm">View</a>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#inventory-table').DataTable({
                layout: {
                    topStart: {
                        buttons: ['excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush
