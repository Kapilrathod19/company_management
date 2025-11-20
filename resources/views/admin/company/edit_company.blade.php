@extends('admin.layout.main_layout')
@section('title', 'Admin | Edit Company')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Company</h5>
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
                            <form id="myform" action="{{ route('admin.update_company', $company->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Company Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $company->name) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $company->email) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                                            value="{{ old('phone_number', $company->phone_number) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Office Address</label>
                                        <textarea name="address" id="address" rows="1" class="form-control">{{ old('address', $company->address) }}</textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="alternate_address" class="form-label">Factory Address</label>
                                        <textarea name="alternate_address" id="alternate_address" rows="1" class="form-control">{{ old('alternate_address', $company->alternate_address) }}</textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <select name="state" id="state" class="form-control">
                                            <option value="{{ $company->state_id }}">
                                                {{ $company->state->name ?? 'Select State' }}</option>
                                            <option value="">Select State</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ old('state', $company->state_id) == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <select name="city" id="city" class="form-control">
                                            <option value="{{ $company->city_id }}">
                                                {{ $company->city->name ?? 'Select City' }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pincode" class="form-label">Pincode</label>
                                        <input type="text" name="pincode" id="pincode" class="form-control"
                                            value="{{ old('pincode', $company->pincode) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gst_no" class="form-label">GST Number</label>
                                        <input type="text" name="gst_no" class="form-control" id="gst_no"
                                            value="{{ old('gst_no', $company->gst_no) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="msme_no" class="form-label">MSME Number</label>
                                        <input type="text" name="msme_no" class="form-control" id="msme_no"
                                            value="{{ old('msme_no', $company->msme_no) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="state_code" class="form-label">State Code</label>
                                        <input type="text" name="state_code" class="form-control" id="state_code"
                                            value="{{ old('state_code', $company->state_code) }}">
                                        <span class="text-danger"></span>
                                    </div>
                                        

                                    <div class="col-12 mb-3">
                                        <p>Image:</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image"
                                                accept="image/*">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                    @if ($company->image)
                                        <div class="mt-2" id="imageContainer">
                                            <img src="{{ asset('company_images/' . $company->image) }}"
                                                class="img-thumbnail" style="max-width:250px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
                '#phone_number',
                '#address',
                '#state',
                '#city',
                '#pincode',
                '#gst_no',
                '#msme_no',
                '#state_code'
            ];

            // Allow only numeric input for phone and pincode
            $('#phone_number, #pincode').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // remove non-numeric chars
            });

            // Validate on input/change
            $('#myform').on('input change', fieldsToValidate.join(','), function() {
                validateField($(this));
            });

            // Preview Image
            $('#image').on('change', function() {
                var file = this.files[0];
                var imageContainer = $('#imageContainer');
                if (file && file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imageContainer.html('<img src="' + e.target.result +
                            '" class="img-thumbnail" style="max-width:250px;">');
                    }
                    reader.readAsDataURL(file);
                } else {
                    imageContainer.html('');
                }
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

                if (name === 'phone_number') {
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
                } else if (name === 'phone_number' && !/^[0-9]{10,15}$/.test(value)) {
                    message = "Please enter a valid phone number.";
                } else if (name === 'pincode' && !/^[0-9]{4,10}$/.test(value)) {
                    message = "Please enter a valid pincode.";
                }

                field.siblings('.text-danger').text(message);
                return isValid;
            }

        });

        function loadCities(stateId, selectedCity = null) {
            if (!stateId) {
                $('#city').html('<option value="">Select City</option>');
                return;
            }
            $('#city').html('<option value="">Loading...</option>');
            $.ajax({
                url: '/get-cities/' + stateId,
                type: 'GET',
                success: function(cities) {
                    var options = '<option value="">Select City</option>';
                    $.each(cities, function(index, city) {
                        options += '<option value="' + city.id + '" ' +
                            (city.id == selectedCity ? 'selected' : '') + '>' +
                            city.name + '</option>';
                    });
                    $('#city').html(options);
                },
                error: function() {
                    $('#city').html('<option value="">Error loading cities</option>');
                }
            });
        }

        $(document).ready(function() {
            var selectedState = "{{ old('state', $company->state_id) }}";
            var selectedCity = "{{ old('city', $company->city_id) }}";

            // Initial load
            loadCities(selectedState, selectedCity);

            // Change event
            $('#state').on('change', function() {
                loadCities($(this).val());
            });
        });
    </script>
@endsection
