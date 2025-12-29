@extends('user.layout.main_layout')
@section('title', 'User | Dashboard')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            {{-- Welcome Section --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded shadow-sm p-4">
                        <h2 class="mb-0">
                            Welcome Back,
                            <span class="text-primary">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </span>
                        </h2>
                    </div>
                </div>
            </div>

            {{-- Search Section --}}
            <div class="row">
                <div class="col-12">
                    <div class="bg-white rounded shadow-sm p-4">
                        <h5 class="mb-4 fw-bold text-dark">Search Component Details</h5>

                        <div class="row g-3">
                            {{-- Component Number --}}
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="fw-semibold mb-1">
                                    Component Number
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

                            {{-- Unit Number --}}
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="fw-semibold mb-1">
                                    Unit Number
                                </label>
                                <select id="unitNumberDropdown" class="form-control select2">
                                    <option value="">Select Unit Number</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Result Table --}}
                <div class="col-12">
                    <div class="bg-white rounded shadow-sm p-4">
                        <h5 class="mb-3 fw-bold">Sales Order Details</h5>
                        <table id="datatable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Unit No</th>
                                    <th>PO No</th>
                                    <th class="text-nowrap">PO Date</th>
                                    <th>Party Challan No</th>
                                    <th class="text-nowrap">Party Challan Date</th>
                                    <th class="text-nowrap">Last Production Date</th>
                                    <th>Last Process</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            let table = $('#datatable').DataTable({
                searching: false,
                paging: true,
                info: true,
                data: [],
                columns: [{
                        data: 'unit_no'
                    },
                    {
                        data: 'po_no'
                    },
                    {
                        data: 'po_date',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'party_challan_no'
                    },
                    {
                        data: 'party_challan_date',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'production_date',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'process'
                    },
                ]
            });

            $('#unitNumberDropdown').on('change', function() {
                let unitNo = $(this).val();

                if (!unitNo) {
                    table.clear().draw();
                    return;
                }

                $.get('{{ route('get.sales.order') }}', {
                    unit_no: unitNo
                }, function(res) {

                    let rows = [];

                    $.each(res.sales_orders, function(i, order) {
                        rows.push({
                            unit_no: order.unit_no,
                            po_no: order.po_no,
                            po_date: order.po_date,
                            party_challan_no: res.grn ? res.grn.party_challan_no :
                                '-',
                            party_challan_date: res.grn ? res.grn
                                .party_challan_date : '-',
                            production_date: res.production ? res.production.date :
                                '-',
                            process: res.production ? res.production.process : '-',
                        });
                    });

                    table.clear().rows.add(rows).draw();
                });
            });

            // Load Unit Numbers
            $('#ComponentNumberSearch').on('change', function() {
                let itemId = $(this).val();
                let unitDropdown = $('#unitNumberDropdown');

                // Reset unit dropdown
                unitDropdown
                    .empty()
                    .append('<option value="">Select Unit Number</option>')
                    .trigger('change');

                if (!itemId) return;

                $.ajax({
                    url: '{{ route('get.unit.numbers', ':item') }}'.replace(':item', itemId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, value) {
                            unitDropdown.append(
                                `<option value="${value}">${value}</option>`
                            );
                        });
                    },
                    error: function() {
                        console.error('Failed to load unit numbers');
                    }
                });
            });

        });
    </script>
@endsection
