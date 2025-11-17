@extends('admin.layout.main_layout')
@section('title', 'Admin | Add User')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add User</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">
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
                            <form id="myform" action="{{ route('admin.store_user') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="company_id" class="form-label">Company</label>
                                        <select name="company_id" id="company_id" class="form-control">
                                            <option value="">Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control"
                                            value="{{ old('mobile') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" name="department" id="department" class="form-control"
                                            value="{{ old('department') }}">
                                        <span class="text-danger"></span>
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

            // Required fields
            var fieldsToValidate = [
                '#company_id',
                '#name',
                '#email',
                '#password',
                '#mobile',
                '#department'
            ];

            // Custom field display names
            var fieldNames = {
                'company_id': 'Company',
                'name': 'Name',
                'email': 'Email',
                'password': 'Password',
                'mobile': 'Mobile',
                'department': 'Department'
            };

            // Allow only numeric input (mobile)
            $('#mobile').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Validate on input/change
            $('#myform').on('input change', fieldsToValidate.join(','), function() {
                validateField($(this));
            });

            // Form Submit Handler
            $('#myform').submit(function(e) {
                e.preventDefault();

                var valid = true;

                fieldsToValidate.forEach(function(selector) {
                    if (!validateField($(selector))) valid = false;
                });

                if (valid) {
                    this.submit();
                }
            });

            // Validation Function
            function validateField(field) {
                var value = field.val().trim();
                var name = field.attr('name');
                var displayName = fieldNames[name] ?? name.replace('_', ' ');

                var isValid = value !== "";

                // Email validation
                if (name === 'email') {
                    isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                }

                // Mobile validation
                if (name === 'mobile') {
                    isValid = /^[0-9]{10,15}$/.test(value);
                }

                // Error messages
                let message = "";

                if (value === "") {
                    message = `${displayName} is required.`;
                } else if (name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = "Please enter a valid email address.";
                } else if (name === 'mobile' && !/^[0-9]{10,15}$/.test(value)) {
                    message = "Please enter a valid phone number.";
                }

                field.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);

                field.siblings('.text-danger').text(message);

                return isValid;
            }

        });
    </script>

@endsection
