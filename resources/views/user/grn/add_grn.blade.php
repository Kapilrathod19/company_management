@extends('user.layout.main_layout')
@section('title', 'User | Add GRN')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add GRN</h5>
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
                            <form id="myform" action="{{ route('grn.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="grn_date" class="form-label">GRN Date</label>
                                        <input type="date" name="grn_date" id="grn_date" class="form-control"
                                            value="{{ old('grn_date', date('Y-m-d')) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="Customer">Customer</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Jobwork">Jobwork</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="party_name" class="form-label">Party Name</label>
                                        <select name="party_name" id="party_name" class="form-control">
                                            <option value="">Select Party</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="po_no" class="form-label">Po Number</label>
                                        <select name="po_no" id="po_no" class="form-control">
                                            <option value="">Select PO Number</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="party_challan_no" class="form-label">Party Challan No</label>
                                        <input type="text" name="party_challan_no" id="party_challan_no"
                                            class="form-control" value="{{ old('party_challan_no') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="party_challan_date" class="form-label">Party Challan Date</label>
                                        <input type="date" name="party_challan_date" id="party_challan_date"
                                            class="form-control" value="{{ old('party_challan_date', date('Y-m-d')) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="part_no" class="form-label">Component No</label>
                                        <select name="part_no" id="part_no" class="form-control">
                                            <option value="">Select Component No</option>
                                        </select>

                                        <span class="text-danger"></span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_no" class="form-label">Unit No</label>
                                        <select name="unit_no" id="unit_no" class="form-control">
                                            <option value="">Select Unit No</option>
                                        </select>

                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="qty" class="form-label">Quantity</label>
                                        <input type="number" name="qty" id="qty" class="form-control"
                                            value="{{ old('qty') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="number" step="0.01" name="weight" id="weight"
                                            class="form-control" value="{{ old('weight') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="total_weight" class="form-label">Total Weight</label>
                                        <input type="number" step="0.01" name="total_weight" id="total_weight"
                                            class="form-control" value="{{ old('total_weight') }}" readonly>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="remark" class="form-label">Remark</label>
                                        <input type="text" name="remark" id="remark" class="form-control"
                                            value="{{ old('remark') }}">
                                        <span class="text-danger"></span>
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

        $('#qty, #weight').on('input', function() {
            calculateTotalWeight();
        });

        $(document).ready(function() {

            const fields = [{
                    id: '#grn_date',
                    name: 'GRN Date'
                },
                {
                    id: '#category',
                    name: 'Category'
                },
                {
                    id: '#party_name',
                    name: 'Party Name'
                },
                {
                    id: '#po_no',
                    name: 'Po Number'
                },
                {
                    id: '#party_challan_no',
                    name: 'Party Challan Number'
                },
                {
                    id: '#party_challan_date',
                    name: 'Party Challan Date'
                },
                {
                    id: '#part_no',
                    name: 'Component Number'
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

        function resetParty() {
            $('#party_name').empty().append('<option value="">Select Party</option>');
            resetPO();
            resetFields();
        }

        function resetPO() {
            $('#po_no').empty().append('<option value="">Select PO Number</option>');
            resetFields();
        }

        function resetFields() {
            $('#unit_no').val('');
            $('#part_no').val('');
            $('#description').val('');
            $('#qty').val('');
            $('#weight').val('');
            $('#total_weight').val('');
        }


        $('#category').on('change', function() {
            let category = $(this).val();
            resetParty();

            $('#party_name').empty().append('<option value="">Select Party</option>');

            if (category === '') return;

            $.ajax({
                url: "{{ route('get.parties.by.category') }}",
                type: "GET",
                data: {
                    category: category
                },
                success: function(res) {
                    if (res.status === true) {
                        $.each(res.data, function(key, party) {
                            $('#party_name').append('<option value="' + party.id + '">' + party
                                .name + '</option>');
                        });
                    }
                }
            });
        });

        $('#party_name').on('change', function() {

            let party_id = $(this).val();
            let category = $('#category').val();

            resetPO();

            $('#po_no').empty().append('<option value="">Select PO Number</option>');

            if (party_id === '' || category === '') return;

            $.ajax({
                url: "{{ route('get.po.by.party') }}",
                type: "GET",
                data: {
                    party_id: party_id,
                    category: category
                },
                success: function(res) {
                    if (res.status === true) {
                        $.each(res.data, function(key, po) {
                            $('#po_no').append('<option value="' + po + '">' + po +
                                '</option>');
                        });
                    }
                }
            });
        });

        $('#po_no').on('change', function() {
            $.ajax({
                url: "{{ route('grn.getPoItems') }}",
                data: {
                    category: $('#category').val(),
                    party_id: $('#party_name').val(),
                    po_no: $('#po_no').val()
                },
                success: function(res) {
                    $('#unit_no').html('<option value="">Select Unit</option>');
                    $('#part_no').html('<option value="">Select Component</option>');

                    res.unit_numbers.forEach(u => {
                        $('#unit_no').append(`<option value="${u}">${u}</option>`);
                    });

                    res.part_numbers.forEach(p => {
                        $('#part_no').append(`<option value="${p}">${p}</option>`);
                    });
                }
            });
        });

        $('#unit_no').on('change', function() {
            $.ajax({
                url: "{{ route('grn.getItemByUnit') }}",
                data: {
                    unit_no: $(this).val(),
                    category: $('#category').val(),
                    party_id: $('#party_name').val(),
                    po_no: $('#po_no').val()
                },
                success: function(res) {
                    if (res.status) {
                        $('#part_no').val(res.data.part_no);
                        $('#description').val(res.data.description);
                        $('#qty').val(res.data.qty);
                        $('#weight').val(res.data.weight);
                        $('#total_weight').val(res.data.total_weight);
                    }
                }
            });
        });

        $('#part_no').on('change', function() {
            $.ajax({
                url: "{{ route('grn.getItemByPart') }}",
                data: {
                    part_no: $(this).val(),
                    category: $('#category').val(),
                    party_id: $('#party_name').val(),
                    po_no: $('#po_no').val()
                },
                success: function(res) {
                    if (res.status) {
                        $('#unit_no').val(res.data.unit_no);
                        $('#description').val(res.data.description);
                        $('#qty').val(res.data.qty);
                        $('#weight').val(res.data.weight);
                        $('#total_weight').val(res.data.total_weight);
                    }
                }
            });
        });
    </script>
@endsection
