@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('inventories.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Inventory</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></div>
                <div class="breadcrumb-item">Edit Inventory</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                {{-- Inventory Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('inventories.update', $inventory->id) }}" method="post" class="needs-validation" novalidate="">
                            @csrf
                            @method('PUT')

                            <div class="card-header">
                                <h4>Edit Inventory</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Product</label>
                                        <select name="product_id" class="form-control select2" required>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $product->id == $inventory->product_id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Update</button>
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
