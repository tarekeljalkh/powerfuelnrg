@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('inventories.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Inventory Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></div>
                <div class="breadcrumb-item">Inventory Details</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                {{-- Inventory Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Inventory Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" value="{{ $inventory->product->name }}" disabled>
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control" value="{{ $inventory->quantity }}" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Created At</label>
                                    <input type="text" class="form-control" value="{{ $inventory->created_at }}" disabled>
                                </div>

                                <div class="form-group col-md-12 col-12">
                                    <label>Updated At</label>
                                    <input type="text" class="form-control" value="{{ $inventory->updated_at }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('inventories.destroy', $inventory->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this inventory?')">Delete</button>
                            </form>
                        </div>
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
