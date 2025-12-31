@extends('user.layout.main_layout')
@section('title', 'User | Dashboard')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            {{-- Welcome Section --}}
            {{-- <div class="row mb-4">
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
            </div> --}}

            {{-- Search Section --}}
            <div class="row">
                <div class="col-12">
                    <div class="bg-white rounded shadow-sm p-4">
                        <div class="row g-3">
                            {{-- Component Number --}}
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

                    </div>
                </div>

                {{-- Result Table --}}
                <div class="col-12">
                    <div class="bg-white rounded shadow-sm p-4">
                        <table id="tabledata" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Unit No</th>
                                    <th>PO No</th>
                                    <th class="text-nowrap">PO Date</th>
                                    <th class="text-nowrap">SupplierPO Date</th>
                                    <th>ChNo</th>
                                    <th class="text-nowrap">Ch Date</th>
                                    <th class="text-nowrap">Prod Date</th>
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
                            <td>${row.unit_no ?? '-'}</td>
                            <td>${row.po_no ?? '-'}</td>
                            <td class="text-nowrap">${row.po_date ?? '-'}</td>
                            <td class="text-nowrap">${row.supplier_po_date ?? '-'}</td>
                            <td>${row.party_challan_no ?? '-'}</td>
                            <td class="text-nowrap">${row.party_challan_date ?? '-'}</td>
                            <td class="text-nowrap">${row.production_date ?? '-'}</td>
                            <td>${row.process ?? '-'}</td>
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
    </script>
@endsection
