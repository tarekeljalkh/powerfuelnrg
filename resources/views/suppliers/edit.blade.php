@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Supplier</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Suppliers</a></div>
                <div class="breadcrumb-item"><a href="#">Edit Supplier</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                {{-- Supplier Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('supplier.update', $supplier->id) }}" method="post"
                            class="needs-validation" novalidate="">
                            @method('put')
                            @csrf

                            <div class="card-header">
                                <h4>Edit Supplier</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">

                                    <div class="form-group col-md-6 col-12">
                                        <label>Name</label>
                                        <input type="text" name="first_name" class="form-control"
                                            value="{{ $supplier->name }}" required="">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $supplier->email }}" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Phone</label>
                                        <input type="number" name="mobile" value="{{ $supplier->phone }}"
                                            class="form-control" required="">
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Supplier Section --}}


            </div>
        </div>
    </section>
@endsection
