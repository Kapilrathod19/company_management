@extends('company.layout.main_layout')
@section('title', 'Company | Add User')

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
                            <form id="myform" action="{{ route('company.store_user') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

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

                                    <div class="col-md-12 mb-3">
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

            // All required fields
            var fieldsToValidate = [
                '#name',
                '#email',
                '#password',
                '#mobile',
                '#department'
            ];

            // Allow only numeric input for phone and pincode
            $('#mobile, #pincode').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // remove non-numeric chars
            });

            // Validate on input/change
            $('#myform').on('input change', fieldsToValidate.join(','), function() {
                validateField($(this));
            });


            // Form Submit Handler
            $('#myform').submit(function(e) {
                e.preventDefault();

                var valid = fieldsToValidate.every(function(selector) {
                    return validateField($(selector));
                });

                if (valid) {
                    this.submit();
                }
            });

            // Validation Function
            function validateField(field) {
                var value = field.val().trim();
                var isValid = value !== "";
                var name = field.attr('name');

                // Extra checks
                if (name === 'email') {
                    if (value !== "") {
                        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    } else {
                        isValid = false; // empty email is still invalid
                    }
                }

                if (name === 'mobile') {
                    isValid = /^[0-9]{10,15}$/.test(value);
                }

                if (name === 'pincode') {
                    isValid = /^[0-9]{4,10}$/.test(value);
                }

                // Styling and messages
                field.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);

                let message = "";
                if (value === "") {
                    message = `${name.replace('_', ' ')} is required.`;
                } else if (name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = "Please enter a valid email address.";
                } else if (name === 'mobile' && !/^[0-9]{10,15}$/.test(value)) {
                    message = "Please enter a valid phone number.";
                } else if (name === 'pincode' && !/^[0-9]{4,10}$/.test(value)) {
                    message = "Please enter a valid pincode.";
                }

                field.siblings('.text-danger').text(message);
                return isValid;
            }

        });
    </script>
@endsection
