@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
 
 

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Vouchers</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="#">Vouchers</a></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Vouchers</h4>
                        <div class="card-header-action">
                            <a href="{{ route('vouchers.create') }}" class="btn btn-success">Add New <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
    $defaultStart = request('start_date', now()->startOfYear()->toDateString());
    $defaultEnd   = request('end_date', now()->toDateString());
@endphp
                       {{-- FILTERS --}}
                       <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Filters</h5>
    <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#filtersBox">
        <i class="fas fa-chevron-down" id="filter-arrow"></i>
    </button>
</div>
<div class="collapse" id="filtersBox">
    <form method="GET" class="mb-3" id="voucher-filter-form">
        <div class="row">
            {{-- Date From --}}
            <div class="form-group col-md-4">
                <label for="start_date">From Date</label>
                <input type="date" name="start_date" id="start_date"
                        class="form-control flatpickr"
                       value="{{ $defaultStart }}">
            </div>

            {{-- Date To --}}
            <div class="form-group col-md-4">
                <label for="end_date">To Date</label>
                <input type="date" name="end_date" id="end_date"
                       class="form-control flatpickr"
                       value="{{ $defaultEnd }}">
            </div>

            {{-- Client (name from third_parties) --}}
            <div class="form-group col-md-4">
                <label for="client_name">Client</label>
                <!-- <input type="text" name="client_name" id="client_name"
                       class="form-control"
                       placeholder="Client name"
                       value="{{ request('client_name') }}"> -->
                       <select name="third_party_id" id="third_party_id" class="form-control"></select>
            </div>
</div>
<div class="row mt-2">
            {{-- Manual Ref --}}
            <div class="form-group col-md-4">
                <label for="manual_ref">Manual Ref</label>
                <input type="text" name="manual_ref" id="manual_ref"
                       class="form-control"
                       value="{{ request('manual_ref') }}">
            </div>

            {{-- Reference --}}
            <div class="form-group col-md-4">
                <label for="reference">Reference</label>
                <input type="text" name="reference" id="reference"
                       class="form-control"
                       value="{{ request('reference') }}">
            </div>

            {{-- Description --}}
            <div class="form-group col-md-4">
                <label for="description">Description</label>
                <input type="text" name="description" id="description"
                       class="form-control"
                       value="{{ request('description') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-2">
            Apply Filters
        </button>
    </form>
</div>

    {{-- DATATABLE --}}
    {{ $dataTable->table() }} 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
 
<script>
    $('#filtersBox').on('show.bs.collapse', function () {
        $('#filter-arrow').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    });

    $('#filtersBox').on('hide.bs.collapse', function () {
        $('#filter-arrow').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
</script>

  <script>
    $(function () {
        // Init Select2 for client
        $('#third_party_id').select2({
            placeholder: 'Search client...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: '{{ route('thirdparties.autocomplete') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // If a client is already selected (from previous filter), set it as initial value
        @if(request('third_party_id') && request('client_name'))
            let option = new Option('{{ request('client_name') }}', '{{ request('third_party_id') }}', true, true);
            $('#third_party_id').append(option).trigger('change');
        @endif
    });
</script>  

   
 
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function printSection(divId) {
    var content = document.getElementById(divId).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = originalContent;
}

document.addEventListener('DOMContentLoaded', function() {
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        allowInput: true
    });
});
</script> 

@endpush
