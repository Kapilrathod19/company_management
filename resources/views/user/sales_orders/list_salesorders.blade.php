@extends('user.layout.main_layout')
@section('title', 'User | Sales Order List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Sales Order</h5>
                            </div>
                            <div class="header-action">
                                <a class="btn btn-primary" href="{{ route('sales_order.create') }}" role="button"><i
                                        class="bi bi-plus"></i> Add Sales Order</a>
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
                                            <th>Customer Name</th>
                                            <th>PO No</th>
                                            <th>PO Date</th>
                                            <th>Part No</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total Weight</th>
                                            <th>Delivery Date</th>
                                            <th>Drawing Attachment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesorders as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->party->name ?? '' }}</td>
                                                <td>{{ $item->po_no ?? '' }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->po_date)) }}</td>
                                                <td>{{ $item->item->part_number ?? '' }}</td>
                                                <td>{{ $item->description ?? '' }}</td>
                                                <td>{{ $item->unit ?? '' }}</td>
                                                <td>{{ $item->qty ?? '' }}</td>
                                                <td>{{ $item->weight ?? '' }}</td>
                                                <td>{{ $item->total_weight ?? '' }}</td>
                                                <td class="text-nowrap">
                                                    {{ date('d-m-Y', strtotime($item->delivery_date)) }}</td>
                                                <td>
                                                    @if ($item->drawing_attachment)
                                                        <a href="{{ asset('drawing_attachment/' . $item->drawing_attachment) }}"
                                                            class="btn btn-outline-primary btn-sm" target="_blank">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> View
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm mb-2"
                                                        href="{{ route('sales_order.edit', $item->id) }}" role="button">
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
                                "{{ URL::to('user/sales_order/destroy') }}/" + id;

                        }
                    });
                });
            });
        });
    </script>
@endsection
