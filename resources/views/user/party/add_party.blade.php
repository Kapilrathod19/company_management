@extends('user.layout.main_layout')
@section('title', 'User | Add Party')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Party</h5>
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
                            <form id="myform" action="{{ route('party.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
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
                                        <label for="mobile_number" class="form-label">Mobile Number</label>
                                        <input type="text" name="mobile_number" id="mobile_number" class="form-control"
                                            value="{{ old('mobile_number') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gst_number" class="form-label">GST Number</label>
                                        <input type="text" name="gst_number" id="gst_number" class="form-control"
                                            value="{{ old('gst_number') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" id="address" rows="2" class="form-control">{{ old('address') }}</textarea>
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
        $(document).ready(function() {

            $('#mobile_number').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            const fields = [{
                    id: '#category',
                    name: 'Category'
                },
                {
                    id: '#name',
                    name: 'Name'
                },
                {
                    id: '#email',
                    name: 'Email'
                },
                {
                    id: '#mobile_number',
                    name: 'Mobile Number'
                },
                {
                    id: '#gst_number',
                    name: 'GST Number'
                },
                {
                    id: '#address',
                    name: 'Address'
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

                if (name === 'Email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    message = 'Please enter a valid email address.';
                }

                if (name === 'Mobile Number' && value && !/^[0-9]{10,15}$/.test(value)) {
                    isValid = false;
                    message = 'Please enter a valid mobile number.';
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
        });
    </script>
@endsection
