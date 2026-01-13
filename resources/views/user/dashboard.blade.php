@extends('user.layout.main_layout')
@section('title', 'User | Dashboard')

@section('content')
    <style>
        /* Compact table */
        .production-table-wrapper table {
            font-size: 10px;
            table-layout: auto;
        }

        .production-table-wrapper th,
        .production-table-wrapper td {
            padding: 4px 6px !important;
            /* reduce cell space */
            line-height: 1.2;
            vertical-align: middle;
        }

        .production-table-wrapper th {
            background: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }

        .production-table-wrapper td {
            white-space: nowrap;
            /* keeps data in single line */
        }

        /* Reduce button size inside table */
        .production-table-wrapper .btn {
            padding: 2px 6px;
            font-size: 10px;
            line-height: 1;
        }

        .row-completed {
            background-color: #d4edda !important;
            /* green */
        }

        .row-partial {
            background-color: #fff3cd !important;
            /* yellow */
        }

        .row-not-started {
            background-color: #f8d7da !important;
            /* red */
        }

        #tableSummary span {
            border-radius: 4px;
            color: #000;
            font-weight: 600;
        }
    </style>


    <div class="content-page">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card text-dark shadow-sm border-0" style="background: #e3e3ff;">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0">{{ $totalUnits }}</h3>
                                <small class="text-muted">Total Units</small>
                            </div>
                            <div>
                                <i class="fas fa-layer-group fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card text-dark shadow-sm border-0" style="background: #d4edda;">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0">{{ $completedUnits }}</h3>
                                <small class="text-success">Completed Units</small>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card text-dark shadow-sm border-0" style="background: #fff3cd;">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0">{{ $partialUnits }}</h3>
                                <small class="text-warning">Partial Units</small>
                            </div>
                            <div>
                                <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card text-dark shadow-sm border-0" style="background: #f8d7da;">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0">{{ $pendingUnits }}</h3>
                                <small class="text-danger">Pending Units</small>
                            </div>
                            <div>
                                <i class="fas fa-times-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">

                    <div class="bg-white rounded shadow-sm p-4">

                        <div class="row g-3 mb-4">

                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label class="fw-semibold mb-1">From Date</label>
                                <input type="date" id="fromDate" class="form-control">
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label class="fw-semibold mb-1">To Date</label>
                                <input type="date" id="toDate" class="form-control">
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label class="fw-semibold mb-1">Customer</label>
                                <select id="CustomerSearch" class="form-control select2" data-placeholder="Select Customer">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label class="fw-semibold mb-1">Component No</label>
                                <select id="ComponentNumberSearch" class="form-control select2"
                                    data-placeholder="Select Component Number">
                                    <option value="">Select Component Number</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}" data-part_number="{{ $item->part_number }}">
                                            {{ $item->part_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div id="tableSummary" class="fw-semibold">
                                    <!-- Dynamic counts will appear here -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="table-responsive production-table-wrapper">
                                    <table id="tabledata" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>Unit No</th>
                                                <th>Customer</th>
                                                <th>PO No</th>
                                                <th class="text-nowrap">PO Date</th>
                                                <th>SupPO No</th>
                                                <th class="text-nowrap">SupPO Date</th>
                                                <th>ChNo</th>
                                                <th class="text-nowrap">Ch Date</th>
                                                <th class="text-nowrap">Prod Date</th>
                                                <th>Last Process</th>
                                                <th>All Process</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="allProcessModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="allProcessTitle"></h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Assigned Employee</th>
                                <th>Actual Employee</th>
                                <th>Qty</th>
                                <th>Weight</th>
                                <th>Assigned Date</th>
                                <th>Process Date</th>
                                <th>Process</th>
                                <th>Difference (Days)</th>
                            </tr>
                        </thead>
                        <tbody id="allProcessBody"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                allowClear: true,
                width: '100%'
            });

            $('#ComponentNumberSearch').on('change', loadSalesOrders);
            $('#CustomerSearch').on('change', loadSalesOrders);

            $('#fromDate, #toDate').on('change', loadSalesOrders);
        });

        function loadSalesOrders() {

            let itemId = $('#ComponentNumberSearch').val();
            let partNumber = $('#ComponentNumberSearch option:selected').data('part_number');
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            let tbody = $('#tabledata tbody');
            let customerId = $('#CustomerSearch').val();
            let summaryDiv = $('#tableSummary');

            tbody.empty();
            summaryDiv.empty();

            if (!itemId && !customerId) return;

            $.ajax({
                url: '{{ route('get.sales.orders.by.item', ':id') }}'.replace(':id', itemId),
                type: 'GET',
                data: {
                    partNumber: partNumber,
                    customer_id: customerId,
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function(data) {

                    if (data.length === 0) {
                        tbody.append(`
                    <tr>
                        <td colspan="11" class="text-center text-muted">
                            No records found
                        </td>
                    </tr>
                `);
                        return;
                    }

                    // Counters
                    let redCount = 0,
                        yellowCount = 0,
                        greenCount = 0;

                    $.each(data, function(index, row) {

                        let rowClass = '';

                        if (row.process_status === 'completed') {
                            rowClass = 'row-completed';
                            greenCount++;
                        } else if (row.process_status === 'partial') {
                            rowClass = 'row-partial';
                            yellowCount++;
                        } else if (row.process_status === 'not_started') {
                            rowClass = 'row-not-started';
                            redCount++;
                        }

                        tbody.append(`
                            <tr class="${rowClass}">
                                <td>${row.unit_no}</td>
                                <td>${row.customer_name}</td>
                                <td>${row.po_no}</td>
                                <td>${row.po_date}</td>
                                <td>${row.supplier_po_no}</td>
                                <td>${row.supplier_po_date}</td>
                                <td>${row.party_challan_no}</td>
                                <td>${row.party_challan_date}</td>
                                <td>${row.production_date}</td>
                                <td>${row.process}</td>
                                <td>
                                    ${row.has_process
                                        ? `<button class="btn btn-sm btn-primary"
                                                        onclick="viewAllProcesses(${row.sales_order_id},'${row.part_no}', '${row.unit_no}')">
                                                        View All
                                                    </button>`
                                        : '-'}
                                </td>
                            </tr>
                        `);
                    });

                    // Update summary below table
                    summaryDiv.html(`
                <span class="row-not-started px-2 py-1 me-2">Red: ${redCount} Not Started</span>
                <span class="row-partial px-2 py-1 me-2">Yellow: ${yellowCount} Partial</span>
                <span class="row-completed px-2 py-1">Green: ${greenCount} Completed</span>
            `);
                },
                error: function() {
                    console.error('Failed to load sales orders');
                }
            });
        }


        function viewAllProcesses(salesOrderId, partNo, unitNo) {

            $('#allProcessTitle').html(
                `All Processes for Unit: <strong>${unitNo}</strong>`
            );

            $('#allProcessBody').html(`
                <tr><td colspan="9" class="text-center">Loading...</td></tr>
            `);

            $('#allProcessModal').modal('show');

            $.get(
                '{{ route('production.all.processes', ':id') }}'.replace(':id', salesOrderId), {
                    component_no: partNo,
                    unit_no: unitNo
                },
                function(data) {

                    let rows = '';

                    if (data.length === 0) {
                        rows = `<tr><td colspan="9" class="text-center">No process found</td></tr>`;
                    } else {
                        $.each(data, function(i, row) {
                            rows += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${row.assigned_employee}</td>
                            <td>${row.actual_employee}</td>
                            <td>${row.qty}</td>
                            <td>${row.weight}</td>
                            <td>${row.assigned_date}</td>
                            <td>${row.process_date}</td>
                            <td>${row.process}</td>
                            <td>${row.difference}</td>
                        </tr>
                    `;
                        });
                    }

                    $('#allProcessBody').html(rows);
                }
            );
        }
    </script>
@endsection
