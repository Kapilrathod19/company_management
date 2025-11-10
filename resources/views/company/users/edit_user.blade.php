@extends('company.layout.main_layout')
@section('title', 'Company | Edit User')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit User</h5>
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
                            <form id="myform" action="{{ route('company.update_user', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $user->name) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $user->email) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password (leave blank to keep old)</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control"
                                            value="{{ old('mobile', $user->mobile) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" name="department" id="department" class="form-control"
                                            value="{{ old('department', $user->department) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" id="submitButton" class="btn btn-primary">Update</button>
                                    </div>
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

            var fieldsToValidate = [
                '#name',
                '#email',
                '#mobile',
                '#department'
            ];

            $('#mobile').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#myform').on('input change', fieldsToValidate.join(','), function() {
                validateField($(this));
            });

            $('#myform').submit(function(e) {
                e.preventDefault();

                var valid = fieldsToValidate.every(function(selector) {
                    return validateField($(selector));
                });

                if (valid) {
                    this.submit();
                }
            });

            function validateField(field) {
                var value = field.val().trim();
                var isValid = value !== "";
                var name = field.attr('name');

                if (name === 'email') {
                    if (value !== "") {
                        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    } else {
                        isValid = false;
                    }
                }

                if (name === 'mobile') {
                    isValid = /^[0-9]{10,15}$/.test(value);
                }

                field.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);

                let message = "";
                if (value === "") {
                    message = `${name.replace('_', ' ')} is required.`;
                } else if (name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = "Please enter a valid email address.";
                } else if (name === 'mobile' && !/^[0-9]{10,15}$/.test(value)) {
                    message = "Please enter a valid mobile number.";
                }

                field.siblings('.text-danger').text(message);
                return isValid;
            }
        });
    </script>
@endsection
