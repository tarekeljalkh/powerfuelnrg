@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Product</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></div>
                <div class="breadcrumb-item"><a href="#">Edit Product</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                {{-- Product Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('products.update', $product->id) }}" method="post"
                            class="needs-validation" novalidate="">
                            @method('put')
                            @csrf

                            <div class="card-header">
                                <h4>Edit Product</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">

                                    <div class="form-group col-md-6 col-12">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $product->name }}" required="">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Description</label>
                                        <input type="text" name="description" class="form-control"
                                            value="{{ $product->description }}" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Price</label>
                                        <input type="number" name="price" value="{{ $product->price }}"
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
                {{-- End Product Section --}}


            </div>
        </div>
    </section>
@endsection
