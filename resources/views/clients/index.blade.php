@extends('layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Clients</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Clients</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Clients</h4>
                            <div class="card-header-action">
                                <a href="{{ route('clients.create') }}" class="btn btn-success">Add New <i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="clients" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Landline</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $client)
                                            <tr>
                                                <td>{{ $client->first_name }}</td>
                                                <td>{{ $client->last_name }}</td>
                                                <td>{{ $client->mobile }}</td>
                                                <td>{{ $client->landline }}</td>
                                                <td>{{ $client->address }}</td>
                                                <td>
                                                    <a href="{{ route('clients.edit', $client->id) }}"
                                                        class="btn btn-primary">Edit</a>

                                                        <a href="{{ route('clients.destroy', $client->id) }}"
                                                            class="btn btn-danger delete-item">Delete</a>
                                                </td>

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
        new DataTable('#clients', {
            layout: {
                topStart: {
                    buttons: [
                        'excel',
                        'pdf',
                        'print',
                        // {
                        //     extend: 'print',
                        //     text: 'Print all (not just selected)',
                        //     exportOptions: {
                        //         modifier: {
                        //             selected: null
                        //         }
                        //     }
                        // }
                    ]
                }
            },
            select: true
        });

        // new DataTable('#doctors', {
        //     dom: 'Bfrtip', // Define the elements in the control layout
        //     buttons: [{
        //             extend: 'copyHtml5',
        //             text: '<i class="fas fa-files-o"></i>', // Using FontAwesome icons
        //             titleAttr: 'Copy'
        //         },

        //         <
        //         i class = "fas fa-file-excel" > < /i> {
        //             extend: 'excelHtml5',
        //             text: '<i class="fa fa-file-excel-o"></i>',
        //             titleAttr: 'Excel'
        //         },
        //         {
        //             extend: 'csvHtml5',
        //             text: '<i class="fa fa-file-text-o"></i>',
        //             titleAttr: 'CSV'
        //         },
        //         {
        //             extend: 'pdfHtml5',
        //             text: '<i class="fa fa-file-pdf-o"></i>',
        //             titleAttr: 'PDF'
        //         },
        //         {
        //             extend: 'print',
        //             text: '<i class="fa fa-print"></i> Print all (not just selected)',
        //             titleAttr: 'Print',
        //             exportOptions: {
        //                 modifier: {
        //                     selected: null
        //                 }
        //             }
        //         }
        //     ],
        //     select: true
        // });
    </script>
@endpush
