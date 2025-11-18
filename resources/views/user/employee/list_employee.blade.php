@extends('user.layout.main_layout')
@section('title', 'User | Employees List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Employees List</h5>
                            </div>
                            <div class="header-action">
                                <a class="btn btn-primary" href="{{ route('employee.create') }}" role="button"><i
                                        class="bi bi-plus"></i> Add Employee</a>
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
                                            <th>Contractor Name</th>
                                            <th>Employee No</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Contact No</th>
                                            <th>Certificate</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->contractor_name }}</td>
                                                <td>{{ $item->emp_no }}</td>
                                                <td>{{ $item->employee_name }}</td>
                                                <td>{{ $item->designation }}</td>
                                                <td>{{ $item->contact_no }}</td>
                                                <td>
                                                    @if ($item->certificate)
                                                        <img src="{{ asset('certificate/' . $item->certificate) }}"
                                                            class="img-thumbnail" style="max-width:250px;">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == 'Active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm mb-2"
                                                        href="{{ route('employee.edit', $item->id) }}" role="button">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete-confirm mb-2"
                                                        href="javascript:void(0)" data-id="{{ $item->id }}"
                                                        role="button">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
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
                                "{{ URL::to('user/employee/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
