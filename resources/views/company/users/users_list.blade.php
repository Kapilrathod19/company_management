@extends('company.layout.main_layout')
@section('title', 'Company | Users List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Users List</h5>
                            </div>
                            <div class="header-action">
                                <a class="btn btn-primary" href="{{ route('company.create_user') }}" role="button"><i
                                        class="bi bi-plus"></i> Add User</a>
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
                                            <th>Company Name</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comanyUsers as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->company->name ?? '' }}</td>
                                                <td>{{ $item->name ?? '' }}</td>
                                                <td>{{ $item->email ?? '' }}</td>
                                                <td>{{ $item->mobile ?? '' }}</td>
                                                <td>{{ $item->department ?? '' }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm mb-2"
                                                        href="{{ route('company.edit_user', $item->id) }}" role="button">
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
                                "{{ URL::to('company/users/destroy') }}/" + id;
                        }
                    });
                });
            });
        });
    </script>
@endsection
