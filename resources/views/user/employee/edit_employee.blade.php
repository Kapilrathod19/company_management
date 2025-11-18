@extends('user.layout.main_layout')
@section('title', 'User | Edit Employee')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">edit Employee</h5>
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

                        <div class="card-body">
                            <form id="myform" action="{{ route('employee.update', $item->id) }}) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="contractor_name" class="form-label">Contractor Name</label>
                                        <input type="text" name="contractor_name" id="contractor_name"
                                            class="form-control"
                                            value="{{ old('contractor_name', $item->contractor_name) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="emp_no" class="form-label">Employee Number</label>
                                        <input type="text" name="emp_no" id="emp_no" class="form-control"
                                            value="{{ old('emp_no', $item->emp_no) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="employee_name" class="form-label">Employee Name</label>
                                        <input type="text" name="employee_name" id="employee_name" class="form-control"
                                            value="{{ old('employee_name', $item->employee_name) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" name="designation" id="designation" class="form-control"
                                            value="{{ old('designation', $item->designation) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_no" class="form-label">Contact Number</label>
                                        <input type="text" name="contact_no" id="contact_no" class="form-control"
                                            value="{{ old('contact_no', $item->contact_no) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="Active"
                                                {{ old('status', $item->status) == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="Inactive"
                                                {{ old('status', $item->status) == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Certificate:</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="certificate"
                                                name="certificate">
                                            <label class="custom-file-label" for="certificate">Choose file</label>
                                        </div>
                                    </div>
                                    @if ($item->certificate)
                                        <div class="mt-2" id="certificate">
                                            <img src="{{ asset('certificate/' . $item->certificate) }}"
                                                class="img-thumbnail" style="max-width:250px;">
                                        </div>
                                    @endif

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
        $(document).ready(function() {

            $('#contact_no').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            const fields = [{
                    id: '#contractor_name',
                    name: 'Contractor Name'
                },
                {
                    id: '#emp_no',
                    name: 'Employee Number'
                },
                {
                    id: '#employee_name',
                    name: 'Employee Name'
                },
                {
                    id: '#designation',
                    name: 'Designation'
                },
                {
                    id: '#contact_no',
                    name: 'Contact Number'
                },
                {
                    id: '#status',
                    name: 'Status'
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

                if (id === '#contact_no' && value) {
                    if (!/^[0-9]{10,15}$/.test(value)) {
                        isValid = false;
                        message = 'Please enter a valid contact number (10–15 digits).';
                    }
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

            // Form submit validation
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
