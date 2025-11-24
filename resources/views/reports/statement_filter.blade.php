@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Statement of Account Report</h1>
    </div>

    <div class="section-body">

        <form onsubmit="event.preventDefault(); openReport();">

            <div class="form-group">
                <label>Client</label>
                <select id="client_id" name="client_id" class="form-control client-select">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select> 
            </div>

            <div class="form-group">
                <label>From Date</label>
                <input type="date" id="start_date" value="{{ $start_date }}" class="form-control flatpickr">
            </div>

            <div class="form-group">
                <label>To Date</label>
                <input type="date" id="end_date" value="{{ $end_date }}" class="fform-control flatpickr">
            </div>

            <button class="btn btn-primary">Generate Statement</button>
        </form>

    </div>
</div>

<script>
function openReport() {
    const clientId  = document.getElementById('client_id').value;
    const startDate = document.getElementById('start_date').value;
    const endDate   = document.getElementById('end_date').value;

    const url = "{{ url('/reports/statement') }}/" + clientId +
        "?start_date=" + encodeURIComponent(startDate) +
        "&end_date=" + encodeURIComponent(endDate);

    window.location.href = url;
}
</script>
@endsection

@push('scripts') 
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.client-select').select2({
                placeholder: 'Select a client',
                width: '100%' // match form-control width
            });
        });
        $('.client-select').on('select2:open', function () {
        setTimeout(function () {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        }, 100);
    });
    </script>
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
