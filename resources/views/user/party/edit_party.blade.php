@extends('user.layout.main_layout')
@section('title', 'User | Edit Party')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Party</h5>
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

                        <div class="card-body">
                            <form id="myform" action="{{ route('party.update', $party->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="Customer" {{ $party->category == 'Customer' ? 'selected' : '' }}>
                                                Customer</option>
                                            <option value="Supplier" {{ $party->category == 'Supplier' ? 'selected' : '' }}>
                                                Supplier</option>
                                            <option value="Jobwork" {{ $party->category == 'Jobwork' ? 'selected' : '' }}>
                                                Jobwork</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $party->name) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $party->email) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="mobile_number" class="form-label">Mobile Number</label>
                                        <input type="text" name="mobile_number" id="mobile_number" class="form-control"
                                            value="{{ old('mobile_number', $party->mobile_number) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gst_number" class="form-label">GST Number</label>
                                        <input type="text" name="gst_number" id="gst_number" class="form-control"
                                            value="{{ old('gst_number', $party->gst_number) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" id="address" rows="2" class="form-control">{{ old('address', $party->address) }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" id="submitButton" class="btn btn-primary">Update</button>
                                    <a href="{{ route('party.index') }}" class="btn btn-secondary">Cancel</a>
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
            const fieldsToValidate = [
                '#category',
                '#name',
                '#email',
                '#mobile_number',
                '#gst_number',
                '#address'
            ];

            $('#mobile_number').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#myform').on('input change', fieldsToValidate.join(','), function() {
                validateField($(this));
            });

            $('#myform').submit(function(e) {
                e.preventDefault();
                let valid = true;

                fieldsToValidate.forEach(selector => {
                    if (!validateField($(selector))) valid = false;
                });

                if (valid) this.submit();
            });

            function validateField(field) {
                const name = field.attr('name');
                const value = field.val().trim();
                let isValid = value !== "";
                let message = "";

                if (value === "") {
                    message = `${formatLabel(name)} is required.`;
                    isValid = false;
                } else if (name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = "Please enter a valid email address.";
                    isValid = false;
                } else if (name === 'mobile_number' && !/^[0-9]{10,15}$/.test(value)) {
                    message = "Please enter a valid mobile number (10–15 digits).";
                    isValid = false;
                }

                field.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);
                field.siblings('.text-danger').text(message);
                return isValid;
            }

            function formatLabel(name) {
                return name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            }
        });
    </script>
@endsection
