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
    </style>


    <div class="content-page">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">

                    <div class="bg-white rounded shadow-sm p-4">

                        <div class="row g-3 mb-4">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label class="fw-semibold mb-1">
                                    Component No
                                </label>
                                <select id="ComponentNumberSearch" class="form-control select2">
                                    <option value="">Select Component Number</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->part_number }}
                                        </option>
                                    @endforeach
                                </select>
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
                                <th>Employee Name</th>
                                <th>Qty</th>
                                <th>Weight</th>
                                <th>Process Date</th>
                                <th>Process</th>
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
                placeholder: "Select Component Number",
                allowClear: true,
                width: '100%'
            });

            $('#ComponentNumberSearch').on('change', function() {

                let itemId = $(this).val();
                let tbody = $('#tabledata tbody');

                tbody.empty(); // clear table

                if (!itemId) return;

                $.ajax({
                    url: '{{ route('get.sales.orders.by.item', ':id') }}'.replace(':id', itemId),
                    type: 'GET',
                    success: function(data) {

                        if (data.length === 0) {
                            tbody.append(`
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No records found
                            </td>
                        </tr>
                    `);
                            return;
                        }

                        $.each(data, function(index, row) {
                            tbody.append(`
                                <tr>
                                    <td>${row.unit_no}</td>
                                    <td>${row.customer_name}</td>
                                    <td>${row.po_no}</td>
                                    <td class="text-nowrap">${row.po_date}</td>
                                    <td>${row.supplier_po_no}</td>
                                    <td class="text-nowrap">${row.supplier_po_date}</td>
                                    <td>${row.party_challan_no}</td>
                                    <td class="text-nowrap">${row.party_challan_date}</td>
                                    <td class="text-nowrap">${row.production_date}</td>
                                    <td>${row.process}</td>
                                    <td>
                                        ${row.has_process ? `
                                                            <button class="btn btn-sm btn-primary"
                                                                onclick="viewAllProcesses(${row.sales_order_id}, '${row.unit_no}')">
                                                                View All
                                                            </button>` : '-'}
                                    </td>
                                </tr>
                            `);
                        });
                    },
                    error: function() {
                        console.error('Failed to load sales orders');
                    }
                });
            });

        });

        function viewAllProcesses(salesOrderId, unitNo) {

            $('#allProcessTitle').html(
                `All Processes for Unit: <strong>${unitNo}</strong>`
            );

            $('#allProcessBody').html(`
                <tr><td colspan="6" class="text-center">Loading...</td></tr>
            `);

            $('#allProcessModal').modal('show');

            $.get(
                '{{ route('production.all.processes', ':id') }}'.replace(':id', salesOrderId),
                function(data) {

                    let rows = '';

                    if (data.length === 0) {
                        rows = `<tr><td colspan="6" class="text-center">No process found</td></tr>`;
                    } else {
                        $.each(data, function(i, row) {
                            rows += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${row.employee}</td>
                        <td>${row.qty}</td>
                        <td>${row.weight}</td>
                        <td>${row.process_date}</td>
                        <td>${row.process}</td>
                    </tr>`;
                        });
                    }

                    $('#allProcessBody').html(rows);
                }
            );
        }
    </script>
@endsection
