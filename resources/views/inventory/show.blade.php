@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>{{ trans('messages.doctor') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ trans('messages.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ trans('messages.doctor') }} : {{ $doctor->first_name }}, {{ $doctor->last_name }}</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ trans('messages.doctor') }} : {{ $doctor->first_name }}, {{ $doctor->last_name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('messages.patient') }}</th>
                                            <th>{{ trans('messages.lab') }}</th>
                                            <th>{{ trans('messages.external_lab') }}</th>
                                            <th>{{ trans('messages.type') }}</th>
                                            <th>{{ trans('messages.due_date') }}</th>
                                            <th>{{ trans('messages.scan_date') }}</th>
                                            <th>{{ trans('messages.stl_upper') }}</th>
                                            <th>{{ trans('messages.stl_lower') }}</th>
                                            <th>PDF</th>
                                            <th>{{ trans('messages.lab_files') }}</th>
                                            <th>{{ trans('messages.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctor->doctorScans as $scan)
                                            <tr>
                                                <td>{{ isset($scan->patient->first_name) ? $scan->patient->first_name : 'No Data' }}</td>
                                                <td>{{ isset($scan->lab->first_name) ? $scan->lab->first_name : 'No Data' }}</td>
                                                <td>{{ isset($scan->external_lab->first_name) ? $scan->external_lab->first_name : 'No Data' }}</td>
                                                <td>{{ isset($scan->typeofwork->name) ? $scan->typeofwork->name : 'No Data' }}</td>
                                                <td>{{ isset($scan->scan_date) ? $scan->scan_date : 'No Data' }}</td>
                                                <td>{{ isset($scan->due_date) ? $scan->due_date : 'No Data' }}</td>
                                                <td>{{ isset($scan->stl_upper) ? $scan->stl_upper : 'No Data' }}</td>
                                                <td>{{ isset($scan->stl_lower) ? $scan->stl_lower : 'No Data' }}</td>
                                                <td>{{ isset($scan->pdf) ? $scan->pdf : 'No Data' }}</td>
                                                <td>{{ isset($scan->lab_file) ? $scan->lab_file : 'No Data' }}</td>
                                                <td>
                                                <div
                                                    class="badge
                                                        {{ optional($scan->latestStatus)->status == 'pending' ? 'badge-warning' : '' }}
                                                        {{ optional($scan->latestStatus)->status == 'resubmitted' ? 'badge-info' : '' }}
                                                        {{ optional($scan->latestStatus)->status == 'completed' ? 'badge-success' : '' }}
                                                        {{ optional($scan->latestStatus)->status == 'rejected' ? 'badge-danger' : '' }}">
                                                    {{ optional($scan->latestStatus)->status ?? 'No status' }}
                                                </div>
                                            </td>
                                                {{-- <td>
                                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                                            class="btn btn-info">See Scans</a>
                                                </td>
 --}}
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
        new DataTable('#example', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print']
                }
            }
        });
    </script>
@endpush
