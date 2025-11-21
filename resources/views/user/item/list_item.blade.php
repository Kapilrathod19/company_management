@extends('user.layout.main_layout')
@section('title', 'User | Items List')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Items List</h5>
                            </div>
                            <div class="header-action">
                                <a class="btn btn-primary" href="{{ route('item.create') }}" role="button"><i
                                        class="bi bi-plus"></i> Add Item</a>
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
                                            <th>Category</th>
                                            <th>Part No</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Quntity</th>
                                            <th>Weight</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->category ?? '' }}</td>
                                                <td>{{ $item->part_number ?? '' }}</td>
                                                <td>{{ $item->description ?? '' }}</td>
                                                <td>{{ $item->unit ?? '' }}</td>
                                                <td>{{ $item->quantity ?? '' }}</td>
                                                <td>{{ $item->weight ?? '' }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm mb-2 show-process-btn"
                                                        data-id="{{ $item->id }}" title="View Processes" data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-diagram-3"></i>
                                                    </button>
                                                    <a class="btn btn-primary btn-sm mb-2"
                                                        href="{{ route('item.edit', $item->id) }}" role="button" title="Edit Item" data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete-confirm mb-2"
                                                        href="javascript:void(0)" data-id="{{ $item->id }}"
                                                        role="button" title="Delete Item" data-toggle="tooltip" data-placement="top">
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

    <!-- PROCESS MODAL -->

    <div class="modal fade" id="processModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Item Processes</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Process Number</th>
                                <th>Process Name</th>
                            </tr>
                        </thead>
                        <tbody id="processData">
                            <!-- Loaded by AJAX -->
                        </tbody>
                    </table>
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
                                "{{ URL::to('user/item/destroy') }}/" + id;

                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.show-process-btn').forEach(btn => {
                btn.addEventListener('click', function() {

                    let itemId = this.getAttribute('data-id');

                    document.getElementById('processData').innerHTML =
                        '<tr><td colspan="3">Loading...</td></tr>';

                    var fetchUrl = "{{ route('process.get', ['id' => ':id']) }}";
                    fetchUrl = fetchUrl.replace(':id', itemId);

                    fetch(fetchUrl)
                        .then(res => res.json())
                        .then(data => {
                            let rows = "";

                            if (data.length === 0) {
                                rows =
                                    '<tr><td colspan="3" class="text-center">No Processes Found</td></tr>';
                            } else {
                                data.forEach((p, index) => {
                                    rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${p.process_master.process_number}</td>
                                    <td>${p.process_master.process_name}</td>
                                </tr>
                            `;
                                });
                            }

                            document.getElementById('processData').innerHTML = rows;
                        });

                    var modal = new bootstrap.Modal(document.getElementById('processModal'));
                    modal.show();
                });
            });

        });
    </script>
@endsection
