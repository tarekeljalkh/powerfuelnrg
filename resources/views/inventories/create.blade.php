@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Add New Inventory</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></div>
                <div class="breadcrumb-item">Add New Inventory</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                {{-- Inventory Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('inventories.store') }}" method="post" class="needs-validation" novalidate="">
                            @csrf

                            <div class="card-header">
                                <h4>Add New Inventory</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">

                                    <div class="form-group col-md-6 col-12">
                                        <label>Supplier</label>
                                        <select name="supplier_id" class="form-control select2" required>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Fuel Type</label>
                                        <input type="text" name="fuel_type" class="form-control" required="">
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" class="form-control" required="" min="0">
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Inventory Section --}}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Additional scripts can be added here if needed
    </script>
@endpush
