@extends('user.layout.main_layout')
@section('title', 'User | Sales Order List')
@section('content')
    <style>
        .table-danger {
            background-color: #ffe5e5 !important;
        }
    </style>

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
                                @if (isset($permissions['sales_order']) && $permissions['sales_order']->add == 1)
                                    <a class="btn btn-primary" href="{{ route('sales_order.create') }}" role="button"><i
                                            class="bi bi-plus"></i> Add Sales Order</a>
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
                                            <th>Customer Name</th>
                                            <th>PO No</th>
                                            <th>PO Date</th>
                                            <th>Component No</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total Weight</th>
                                            <th>Remaining Qty</th>
                                            <th>Unit No</th>
                                            <th>Rev No</th>
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
                                                <td>{{ $item->remain_qty ?? '' }}</td>
                                                <td>{{ $item->unit_no ?? '' }}</td>
                                                <td>{{ $item->rev_no ?? '' }}</td>
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
                                                    @if (isset($permissions['sales_order']) && $permissions['sales_order']->edit == 1)
                                                        <a class="btn btn-primary btn-sm mb-2"
                                                            href="{{ route('sales_order.edit', $item->id) }}"
                                                            role="button">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                    @if (isset($permissions['sales_order']) && $permissions['sales_order']->delete == 1)
                                                        <a class="btn btn-danger btn-sm delete-confirm mb-2"
                                                            href="javascript:void(0)" data-id="{{ $item->id }}"
                                                            role="button">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-success btn-sm mb-2 show-process-btn"
                                                        data-id="{{ $item->id }}" data-part_no="{{ $item->part_no }}"
                                                        data-unit_no="{{ $item->unit_no }}" title="Planning Process"
                                                        data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-diagram-3"></i>
                                                    </button>
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

    <div class="modal fade" id="processModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">All Processes
                        <small class="text-muted d-block" id="processMeta"></small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Process Number</th>
                                <th>Process Name</th>
                                <th>Employee</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="processData"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="saveProcessBtn">
                        Save Process Assignment
                    </button>
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

        const employees = @json($employees);

        document.querySelectorAll('.show-process-btn').forEach(btn => {

            btn.addEventListener('click', function() {

                currentComponentNo = this.dataset.part_no;
                currentUnitNo = this.dataset.unit_no;

                document.getElementById('processMeta').innerHTML =
                    `Unit No: <strong>${currentUnitNo}</strong>`;

                document.getElementById('processData').innerHTML =
                    '<tr><td colspan="5">Loading...</td></tr>';

                let url = "{{ route('sales_order.process.get', ':id') }}"
                    .replace(':id', currentComponentNo);

                url += `?component_no=${currentComponentNo}&unit_no=${currentUnitNo}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {

                        let rows = '';

                        data.forEach((p, index) => {

                            let selectedEmployee = p.process_assignment?.employee_id ?? '';
                            let selectedDate = p.process_assignment?.process_date ?? '';

                            let empOptions = '<option value="">Select Employee</option>';
                            employees.forEach(emp => {
                                empOptions += `
                                <option value="${emp.id}"
                                    ${emp.id == selectedEmployee ? 'selected' : ''}>
                                    ${emp.emp_no} - ${emp.employee_name}
                                </option>`;
                            });

                            rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${p.process_master.process_number}</td>
                                <td>${p.process_master.process_name}</td>
                                <td>
                                    <select class="form-control"
                                        name="processes[${p.process_master.id}][employee_id]">
                                        ${empOptions}
                                    </select>
                                </td>
                                <td>
                                    <input type="date"
                                        class="form-control"
                                        value="${selectedDate ?? ''}"
                                        name="processes[${p.process_master.id}][date]">
                                </td>
                            </tr>`;
                        });

                        document.getElementById('processData').innerHTML = rows;
                    });

                new bootstrap.Modal(document.getElementById('processModal')).show();
            });
        });


        let currentComponentNo = '';
        let currentUnitNo = '';

        document.getElementById('saveProcessBtn').addEventListener('click', function() {

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('component_no', currentComponentNo);
            formData.append('unit_no', currentUnitNo);

            let hasError = false;

            document.querySelectorAll('#processData tr').forEach(row => {

                // reset previous error state
                row.classList.remove('table-danger');

                let select = row.querySelector('select');
                let date = row.querySelector('input[type="date"]');

                if (!select || !date) return;

                let employeeVal = select.value;
                let dateVal = date.value;

                if (
                    (employeeVal && !dateVal) ||
                    (!employeeVal && dateVal)
                ) {
                    hasError = true;
                    row.classList.add('table-danger');
                }

                if (employeeVal && dateVal) {
                    let id = select.name.match(/\d+/)[0];
                    formData.append(`processes[${id}][employee_id]`, employeeVal);
                    formData.append(`processes[${id}][date]`, dateVal);
                }
            });

            if (hasError) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Process Assignment',
                    text: 'Please select BOTH Employee and Date for highlighted rows.'
                });
                return;
            }

            fetch("{{ route('process.assign') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status) {
                        Swal.fire({
                            icon: "success",
                            text: "Process Assignment Saved!",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#processModal').modal('hide');
                    }
                });
        });
    </script>
@endsection
