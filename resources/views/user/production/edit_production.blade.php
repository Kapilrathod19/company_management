@extends('user.layout.main_layout')
@section('title', 'User | Edit Production')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Production</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">

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
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Form --}}
                        <div class="card-body">
                            <form id="myform" action="{{ route('production.update', $production->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">SrNo</label>
                                        <input type="text" id="sr_no" name="sr_no"
                                            value="{{ old('sr_no', $production->sr_no) }}" class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" id="date" name="date"
                                            value="{{ old('date', $production->date) }}" class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Employees</label>
                                        <select id="employee_id" name="employee_id" class="form-control">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ $production->employee_name == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->emp_no }} - {{ $employee->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Unit No</label>
                                        <select id="unit_no" name="unit_no" class="form-control">
                                            <option value="">Select Unit No</option>
                                            @foreach ($salesorders as $order)
                                                <option value="{{ $order->id }}"
                                                    {{ $order->id == $production->unit_no ? 'selected' : '' }}>
                                                    {{ $order->unit_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Component No</label>
                                        <select id="component_no" name="component_no" class="form-control">
                                            <option value="">Select Component No</option>
                                        </select>

                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea id="description" name="description" class="form-control">{{ old('description', $production->description) }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Process</label>
                                        <select id="process" name="process" class="form-control">
                                            <option value="">Select Process</option>

                                            @foreach ($production->salesOrder->item->processes as $p)
                                                <option value="{{ $p->processMaster->id }}"
                                                    {{ $production->process == $p->processMaster->id ? 'selected' : '' }}>
                                                    {{ $p->processMaster->process_number }} -
                                                    {{ $p->processMaster->process_name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" id="qty" name="qty" class="form-control"
                                            value="{{ old('qty', $production->qty) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Weight</label>
                                        <input type="number" id="weight" step="0.01" name="weight"
                                            class="form-control" value="{{ old('weight', $production->weight) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Weight</label>
                                        <input type="number" id="total_weight" step="0.01" name="total_weight"
                                            class="form-control"
                                            value="{{ old('total_weight', $production->total_weight) }}" readonly>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Remark</label>
                                        <textarea id="remark" name="remark" class="form-control">{{ old('remark', $production->remark) }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Update</button>

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
$(document).ready(function () {

    let selectedUnit     = "{{ $production->unit_no }}";
    let selectedComponent= "{{ $production->salesOrder->id }}";
    let selectedProcess  = "{{ $production->process }}";

    function resetDependentFields() {
        $('#component_no').html('<option value="">Select Component No</option>');
        $('#process').html('<option value="">Select Process</option>');
        $('#qty, #weight, #total_weight').val('');
        $('#description').val('');
    }

    function calculateTotalWeight() {
        let qty = parseFloat($('#qty').val()) || 0;
        let weight = parseFloat($('#weight').val()) || 0;
        $('#total_weight').val((qty * weight).toFixed(2));
    }

    $('#qty, #weight').on('input', calculateTotalWeight);

    // =========================
    // LOAD COMPONENTS BY UNIT
    // =========================
    function loadComponents(unitId, selectedComp = null) {

        if (!unitId) {
            resetDependentFields();
            return;
        }

        $.ajax({
            url: "{{ route('get.components', ':id') }}".replace(':id', unitId),
            type: "GET",
            success: function (data) {

                let options = '<option value="">Select Component No</option>';

                data.components.forEach(function (c) {
                    let selected = selectedComp == c.id ? 'selected' : '';
                    options += `<option value="${c.id}" ${selected}>${c.component_no}</option>`;
                });

                $('#component_no').html(options);

                if (selectedComp) {
                    loadComponentDetails(selectedComp);
                }
            }
        });
    }

    // =========================
    // LOAD COMPONENT DETAILS
    // =========================
    function loadComponentDetails(componentId) {

        if (!componentId) return;

        $.ajax({
            url: "{{ route('get.component.details', ':id') }}".replace(':id', componentId),
            type: "GET",
            success: function (data) {

                $('#qty').val(data.details.qty);
                $('#weight').val(data.details.weight);
                $('#total_weight').val(data.details.total_weight);
                $('#description').val(data.details.description);

                let processOptions = '<option value="">Select Process</option>';

                data.processes.forEach(function (p) {
                    let selected = selectedProcess == p.id ? 'selected' : '';
                    processOptions += `
                        <option value="${p.id}" ${selected}>
                            ${p.process_number} - ${p.process_name}
                        </option>`;
                });

                $('#process').html(processOptions);
            }
        });
    }

    // =========================
    // EVENTS
    // =========================
    $('#unit_no').change(function () {
        resetDependentFields();
        loadComponents($(this).val());
    });

    $('#component_no').change(function () {
        loadComponentDetails($(this).val());
    });

    // =========================
    // INITIAL LOAD (EDIT MODE)
    // =========================
    if (selectedUnit) {
        loadComponents(selectedUnit, selectedComponent);
    }

});

        const fields = [{
                id: '#sr_no',
                name: 'Sr No'
            },
            {
                id: '#date',
                name: 'Date'
            },
            {
                id: '#employee_id',
                name: 'Employee'
            },
            {
                id: '#unit_no',
                name: 'Unit No'
            },
            {
                id: '#component_no',
                name: 'Component No'
            },
            {
                id: '#process',
                name: 'Process'
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
                id: '#total_weight',
                name: 'Total Weight'
            }
        ];

        function validateField(field, name) {
            const value = $(field).val().trim();
            let isValid = value !== '';

            $(field).toggleClass('is-invalid', !isValid);
            $(field).toggleClass('is-valid', isValid);
            $(field).siblings('.text-danger').text(isValid ? '' : `${name} is required.`);

            return isValid;
        }

        fields.forEach(f => {
            $(f.id).on('input change', function() {
                validateField(this, f.name);
            });
        });

        $('#myform').on('submit', function(e) {
            let valid = true;
            fields.forEach(f => {
                if (!validateField(f.id, f.name)) valid = false;
            });

            if (!valid) e.preventDefault();
        });
    </script>
@endsection
