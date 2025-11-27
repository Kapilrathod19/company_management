@extends('user.layout.main_layout')
@section('title', 'User | Edit Supplier')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Supplier</h5>
                            </div>
                        </div>

                        {{-- Alerts --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card-body">

                            <form id="myform" action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Supplier Name</label>
                                        <select name="supplier_name" id="supplier_name" class="form-control">
                                            <option value="">Select Supplier Name</option>
                                            @foreach ($supplier_name as $party)
                                                <option value="{{ $party->id }}"
                                                    {{ $supplier->supplier_name == $party->id ? 'selected' : '' }}>
                                                    {{ $party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PO Number</label>
                                        <input type="text" name="po_no" id="po_no" class="form-control"
                                            value="{{ $supplier->po_no }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PO Date</label>
                                        <input type="date" name="po_date" id="po_date" class="form-control"
                                            value="{{ $supplier->po_date }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <select name="customer_name" id="customer_name" class="form-control">
                                            <option value="">Select Customer</option>
                                            @foreach ($salesorders as $sales)
                                                <option value="{{ $sales->customer_name }}"
                                                    {{ $supplier->customer_name == $sales->customer_name ? 'selected' : '' }}>
                                                    {{ $sales->party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sales PO No</label>
                                        <input type="text" name="sales_po_no" id="sales_po_no" class="form-control"
                                            value="{{ $supplier->sales_po_no }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Unit No</label>
                                        <input type="text" name="unit_no" id="unit_no" class="form-control"
                                            value="{{ $supplier->unit_no }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Part Number</label>
                                        <select name="part_no" id="part_no" class="form-control">
                                            <option value="">Select Part Number</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $supplier->part_no == $item->id ? 'selected' : '' }}>
                                                    {{ $item->part_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ $supplier->description }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" step="0.01" name="qty" id="qty"
                                            class="form-control" value="{{ $supplier->qty }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Weight</label>
                                        <input type="number" step="0.01" name="weight" id="weight"
                                            class="form-control" value="{{ $supplier->weight }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Weight</label>
                                        <input type="text" name="total_weight" id="total_weight" class="form-control"
                                            value="{{ $supplier->total_weight }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Remark</label>
                                        <input type="text" name="remark" class="form-control"
                                            value="{{ $supplier->remark }}">
                                    </div>

                                </div>

                                <button class="btn btn-primary mt-3">Update</button>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto Total Weight Calculation
        function calculateTotalWeight() {
            let qty = parseFloat($('#qty').val()) || 0;
            let weight = parseFloat($('#weight').val()) || 0;
            $('#total_weight').val((qty * weight).toFixed(2));
        }

        $('#qty, #weight').on('input', calculateTotalWeight);

        // Validation
        $(document).ready(function() {

            const fields = [{
                    id: '#supplier_name',
                    name: 'Supplier Name'
                },
                {
                    id: '#po_no',
                    name: 'PO Number'
                },
                {
                    id: '#po_date',
                    name: 'PO Date'
                },
                {
                    id: '#customer_name',
                    name: 'Customer Name'
                },
                {
                    id: '#sales_po_no',
                    name: 'Sales PO Number'
                },
                {
                    id: '#unit_no',
                    name: 'Unit No'
                },
                {
                    id: '#part_no',
                    name: 'Part Number'
                },
                {
                    id: '#description',
                    name: 'Description'
                },
                {
                    id: '#qty',
                    name: 'Quantity'
                },
                {
                    id: '#weight',
                    name: 'Weight'
                },
            ];

            function validateField(id, name) {
                const field = $(id);
                const value = field.val()?.trim();
                let valid = true;
                let msg = '';

                if (!value) {
                    valid = false;
                    msg = `${name} is required.`;
                }

                field.removeClass('is-valid is-invalid');

                if (!valid) field.addClass('is-invalid');
                else field.addClass('is-valid');

                field.siblings('span.text-danger').text(msg);

                return valid;
            }

            fields.forEach(f => {
                $(f.id).on('input change', function() {
                    validateField(f.id, f.name);
                });
            });

            $('#myform').on('submit', function(e) {
                let allValid = true;

                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) allValid = false;
                });

                if (!allValid) e.preventDefault();
            });

        });
    </script>
@endsection
