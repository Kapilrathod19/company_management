@extends('user.layout.main_layout')
@section('title', 'User | Add Production')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Production</h5>
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
                            <form id="myform" action="{{ route('production.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label for="sr_no" class="form-label">SrNo</label>
                                        <input type="text" name="sr_no" id="sr_no" class="form-control"
                                            value="{{ old('sr_no') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date', date('Y-m-d')) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="employee_id" class="form-label">Employees</label>
                                        <select name="employee_id" id="employee_id" class="form-control">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->emp_no }} - {{ $employee->employee_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="unit_no" class="form-label">Unit No</label>
                                        <select name="unit_no" id="unit_no" class="form-control">
                                            <option value="">Select Unit No</option>
                                            @foreach ($salesorders as $salesorder)
                                                <option value="{{ $salesorder->id }}"
                                                    {{ old('unit_no') == $salesorder->id ? 'selected' : '' }}>
                                                    {{ $salesorder->unit_no }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="component_no" class="form-label">Component No</label>
                                        <select name="component_no" id="component_no" class="form-control">
                                            <option value="">Select Component No</option>
                                        </select>

                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" rows="2" class="form-control">{{ old('description') }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="process" class="form-label">Process</label>
                                        <select name="process" id="process" class="form-control">
                                            <option value="">Select Process</option>
                                        </select>

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
                                        <textarea name="remark" id="remark" rows="1" class="form-control">{{ old('remark') }}</textarea>
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
                    id: '#sr_no',
                    name: 'SrNo'
                },
                {
                    id: '#date',
                    name: 'Date'
                },
                {
                    id: '#employee_id',
                    name: 'Employee Name'
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
                let isValid = true;
                let message = '';

                if (!value) {
                    isValid = false;
                    message = `${name} is required.`;
                }
                $(field).toggleClass('is-invalid', !isValid);
                $(field).toggleClass('is-valid', isValid);
                $(field).siblings('.text-danger').text(message);

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

                if (!valid) {
                    e.preventDefault();
                }
            });


            $('#unit_no').change(function() {
                let id = $(this).val();

                if (!id) {
                    $('#component_no').html('<option value="">Select Component No</option>');
                    return;
                }

                $.ajax({
                    url: "{{ route('get.components', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(data) {
                        let options = `<option value="">Select Component No</option>`;

                        data.components.forEach(function(c) {
                            options += `<option value="${c.component_no}" data-id="${c.id}">
                        ${c.component_no}
                    </option>`;
                        });

                        $('#component_no').html(options);
                    }

                });
            });

            $('#component_no').change(function() {
                let componentId = $(this).find(':selected').data('id');

                if (!componentId) return;

                $.ajax({
                    url: "{{ route('get.component.details', ':id') }}".replace(':id', componentId),
                    type: "GET",
                    success: function(data) {

                        $('#qty').val(data.details.qty);
                        $('#weight').val(data.details.weight);
                        $('#total_weight').val(data.details.total_weight);
                        $('#description').val(data.details.description);

                        $('#process').html('<option value="">Select Process</option>');

                        data.processes.forEach(function(p) {
                            $('#process').append(
                                `<option value="${p.id}">
                        ${p.process_number} - ${p.process_name}
                    </option>`
                            );
                        });
                    }
                });
            });


        });
    </script>
@endsection
