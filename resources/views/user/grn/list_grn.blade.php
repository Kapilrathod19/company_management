@extends('user.layout.main_layout')
@section('title', 'User | GRN List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">GRN List</h5>
                            </div>
                            <div class="header-action">
                                @if (isset($permissions['grn']) && $permissions['grn']->add == 1)
                                    <a class="btn btn-primary" href="{{ route('grn.create') }}" role="button"><i
                                            class="bi bi-plus"></i> Add GRN</a>
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
                                            <th>GRN No</th>
                                            <th>GRN Date</th>
                                            <th>Category</th>
                                            <th>Party Name</th>
                                            <th>PO No</th>
                                            <th>Party Challan No</th>
                                            <th>Party Challan Date</th>
                                            <th>Unit No</th>
                                            <th>Part No</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total Weight</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($grns as $item)
                                            <tr>
                                                <td>{{ $item->grn_no }}</td>
                                                <td class="text-nowrap">
                                                    {{ date('d-m-Y', strtotime($item->grn_date)) }}
                                                </td>
                                                <td>{{ $item->category }}</td>
                                                <td>{{ $item->party->name }}</td>
                                                <td>{{ $item->po_no }}</td>
                                                <td>{{ $item->party_challan_no }}</td>
                                                <td class="text-nowrap">
                                                    {{ date('d-m-Y', strtotime($item->party_challan_date)) }}
                                                </td>
                                                <td>{{ $item->unit_no }}</td>
                                                <td>{{ $item->part_no }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->weight }}</td>
                                                <td>{{ $item->total_weight }}</td>
                                                <td>
                                                    @if (isset($permissions['grn']) && $permissions['grn']->edit == 1)
                                                        <a class="btn btn-primary btn-sm mb-2"
                                                            href="{{ route('grn.edit', $item->id) }}" role="button">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                    @if (isset($permissions['grn']) && $permissions['grn']->delete == 1)
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
                                "{{ URL::to('user/grn/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
