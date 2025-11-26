@extends('user.layout.main_layout')
@section('title', 'User | Supplier List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Supplier List</h5>
                            </div>
                            <div class="header-action">
                                <a class="btn btn-primary" href="{{ route('supplier.create') }}" role="button"><i
                                        class="bi bi-plus"></i> Add Supplier</a>
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
                                            <th>Supplier Name</th>
                                            <th>PO No</th>
                                            <th>PO Date</th>
                                            <th>Customer Name</th>
                                            <th>Sales Po No</th>
                                            <th>Unit No</th>
                                            <th>Part No</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total Weight</th>
                                            <th>Remark</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->party->name ?? '' }}</td>
                                                <td>{{ $item->po_no ?? '' }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->po_date)) }}</td>
                                                <td>{{ $item->sales_order->name ?? '' }}</td>
                                                <td>{{ $item->sales_po_no ?? '' }}</td>
                                                <td>{{ $item->unit_no ?? '' }}</td>
                                                <td>{{ $item->item->part_number ?? '' }}</td>
                                                <td>{{ $item->description ?? '' }}</td>
                                                <td>{{ $item->qty ?? '' }}</td>
                                                <td>{{ $item->weight ?? '' }}</td>
                                                <td>{{ $item->total_weight ?? '' }}</td>
                                                <td>{{ $item->remark ?? '' }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm mb-2"
                                                        href="{{ route('supplier.edit', $item->id) }}" role="button">
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
                                "{{ URL::to('user/supplier/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
