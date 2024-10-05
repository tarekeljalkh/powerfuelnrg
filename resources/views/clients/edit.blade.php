@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('clients.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Client</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></div>
                <div class="breadcrumb-item"><a href="#">Edit Client</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                {{-- Client Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('clients.update', $client->id) }}" method="post" class="needs-validation" novalidate>
                            @method('put')
                            @csrf

                            <div class="card-header">
                                <h4>Edit Client</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $client->name }}" required maxlength="255">
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ $client->address }}" maxlength="255">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $client->phone }}" maxlength="15">
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $client->email }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Update Client</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Client Section --}}
            </div>
        </div>
    </section>

@endsection
