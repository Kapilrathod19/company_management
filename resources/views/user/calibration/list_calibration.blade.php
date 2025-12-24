@extends('user.layout.main_layout')
@section('title', 'User | Calibration List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Calibration List</h5>
                            </div>
                            <div class="header-action">
                                @if (isset($permissions['calibration']) && $permissions['calibration']->add == 1)
                                    <a class="btn btn-primary" href="{{ route('calibration.create') }}" role="button"><i
                                            class="bi bi-plus"></i> Add Calibration</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table data-table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Machine Name</th>
                                            <th>Machine No</th>
                                            <th>Calibration Due Date</th>
                                            <th>Certificate</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($calibration as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                <td>{{ $item->machine->machine_name ?? '' }}</td>
                                                <td>{{ $item->machine_no }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->calibration_date)) }}</td>
                                                <td>
                                                    @if ($item->certificate)
                                                        <a href="{{ asset('certificate/' . $item->certificate) }}"
                                                            class="btn btn-outline-primary btn-sm" target="_blank">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> View
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($permissions['calibration']) && $permissions['calibration']->edit == 1)
                                                        <a class="btn btn-primary btn-sm mb-2"
                                                            href="{{ route('calibration.edit', $item->id) }}" role="button">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                    @if (isset($permissions['calibration']) && $permissions['calibration']->delete == 1)
                                                        <a class="btn btn-danger btn-sm delete-confirm mb-2"
                                                            href="javascript:void(0)" data-id="{{ $item->id }}"
                                                            role="button">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                    @endif
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
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ URL::to('user/calibration/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
