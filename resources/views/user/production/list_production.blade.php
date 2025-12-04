@extends('user.layout.main_layout')
@section('title', 'User | Production List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Production List</h5>
                            </div>
                            <div class="header-action">
                                @if (isset($permissions['production']) && $permissions['production']->add == 1)
                                    <a class="btn btn-primary" href="{{ route('production.create') }}" role="button"><i
                                            class="bi bi-plus"></i> Add Production</a>
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
                                            <th>SrNo</th>
                                            <th>Date</th>
                                            <th>Employee Name</th>
                                            <th>Unit No</th>
                                            <th>Component No</th>
                                            <th>Description</th>
                                            <th>Process</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total weight</th>
                                            <th>Remark</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productions as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->sr_no }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                <td>{{ $item->employee->emp_no ?? ''}} - {{ $item->employee->employee_name ?? ''}}</td>
                                                <td>{{ $item->salesOrder->unit_no ?? '' }}</td>
                                                <td>{{ $item->component_no }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->processDetails->process_number }} - {{ $item->processDetails->process_name }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->weight }}</td>
                                                <td>{{ $item->total_weight }}</td>
                                                <td>{{ $item->remark }}</td>
                                                <td>
                                                    @if (isset($permissions['production']) && $permissions['production']->edit == 1)
                                                        <a class="btn btn-primary btn-sm mb-2"
                                                            href="{{ route('production.edit', $item->id) }}" role="button">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                    @if (isset($permissions['production']) && $permissions['production']->delete == 1)
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
                                "{{ URL::to('user/production/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
