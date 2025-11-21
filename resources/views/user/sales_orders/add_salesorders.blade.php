@extends('user.layout.main_layout')
@section('title', 'User | Add Sales Order')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Sales Order</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        {{-- Validation & Alerts --}}
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

                        {{-- Form --}}
                        <div class="card-body">
                            <form id="myform" action="{{ route('sales_order.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label">Customer Name</label>
                                        <select name="customer_name" id="customer_name" class="form-control">
                                            <option value="">Select Customer Name</option>
                                            @foreach ($party_names as $party)
                                                <option value="{{ $party->id }}"
                                                    {{ old('customer_name') == $party->id ? 'selected' : '' }}>
                                                    {{ $party->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="po_no" class="form-label">Po Number</label>
                                        <input type="text" name="po_no" id="po_no" class="form-control"
                                            value="{{ old('po_no') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="po_date" class="form-label">Po Date</label>
                                        <input type="date" name="po_date" id="po_date" class="form-control"
                                            value="{{ old('po_date') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="part_no" class="form-label">Part Number</label>
                                        <select name="part_no" id="part_no" class="form-control">
                                            <option value="">Select Part Number</option>
                                            @foreach ($items as $part)
                                                <option value="{{ $part->id }}"
                                                    {{ old('part_no') == $part->id ? 'selected' : '' }}>
                                                    {{ $part->part_number }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" name="unit" id="unit" class="form-control"
                                            value="{{ old('unit') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="qty" class="form-label">Quantity</label>
                                        <input type="number" name="qty" id="qty" class="form-control"
                                            value="{{ old('qty') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="number" step="0.01" name="weight" id="weight"
                                            class="form-control" value="{{ old('weight') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="total_weight" class="form-label">Total Weight</label>
                                        <input type="number" step="0.01" name="total_weight" id="total_weight"
                                            class="form-control" value="{{ old('total_weight') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="delivery_date" class="form-label">Delivery Date</label>
                                        <input type="date" name="delivery_date" id="delivery_date"
                                            class="form-control" value="{{ old('delivery_date') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Drawing Attachment</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="drawing_attachment"
                                                name="drawing_attachment">
                                            <label class="custom-file-label" for="drawing_attachment">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                                </div>
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
        // Auto-calculate Total Weight
        function calculateTotalWeight() {
            let qty = parseFloat($('#qty').val()) || 0;
            let weight = parseFloat($('#weight').val()) || 0;

            let total = qty * weight;

            $('#total_weight').val(total.toFixed(2));
        }

        // Trigger on Qty or Weight input
        $('#qty, #weight').on('input', function() {
            calculateTotalWeight();
        });

        $(document).ready(function() {

            const fields = [{
                    id: '#customer_name',
                    name: 'Customer Name'
                },
                {
                    id: '#po_no',
                    name: 'Po Number'
                },
                {
                    id: '#po_date',
                    name: 'Po Date'
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
                    id: '#unit',
                    name: 'Unit'
                },
                {
                    id: '#qty',
                    name: 'Quantity'
                },
                {
                    id: '#weight',
                    name: 'Weight'
                },
                {
                    id: '#delivery_date',
                    name: 'Delivery Date'
                }
            ];

            function validateField(id, name) {
                const field = $(id);
                const value = field.val().trim();
                let isValid = true;
                let message = '';

                if (!value) {
                    isValid = false;
                    message = `${name} is required.`;
                }

                field.toggleClass('is-invalid', !isValid);
                field.toggleClass('is-valid', isValid);
                field.siblings('.text-danger').text(message);

                return isValid;
            }

            fields.forEach(f => {
                $(f.id).on('input change', function() {
                    validateField(f.id, f.name);
                });
            });

            $('#myform').on('submit', function(e) {
                let valid = true;

                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) valid = false;
                });

                if (!valid) e.preventDefault();
            });

        });
    </script>
@endsection
