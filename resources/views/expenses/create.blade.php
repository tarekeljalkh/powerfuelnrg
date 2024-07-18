@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Add New Expense</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></div>
                <div class="breadcrumb-item"><a href="#">Add New Expense</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                {{-- Expenses Section --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('expenses.store') }}" method="post" class="needs-validation"
                            novalidate="">
                            @csrf

                            <div class="card-header">
                                <h4>Add New Expense</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">

                                    <div class="form-group col-md-6 col-12">
                                        <label>Description</label>
                                        <input type="text" name="description" class="form-control" required="">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Amount</label>
                                        <input type="numer" name="amount" class="form-control" required="">
                                    </div>
                                </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Expense Date</label>
                                        <input type="date" name="expense_date" class="form-control" required="">
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Expenses Section --}}

            </div>
        </div>
    </section>
@endsection

@push('scripts')

