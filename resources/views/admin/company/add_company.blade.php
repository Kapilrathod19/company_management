@extends('admin.layout.main_layout')
@section('title', 'Admin | Add Company')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Company</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">
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
                            <form id="myform" action="{{ route('admin.store_company') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Company Name</label>
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
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                                            value="{{ old('phone_number') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" id="address" rows="2" class="form-control">{{ old('address') }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="alternate_address" class="form-label">Alternate Address</label>
                                        <textarea name="alternate_address" id="alternate_address" rows="2" class="form-control">{{ old('alternate_address') }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <select name="state" id="state" class="form-control">
                                            <option value="">Select State</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <select name="city" id="city" class="form-control">
                                            <option value="">Select City</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="pincode" class="form-label">Pincode</label>
                                        <input type="text" name="pincode" id="pincode" class="form-control"
                                            value="{{ old('pincode') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Image:</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image"
                                                name="image" accept="image/*">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mb-3" id="imageContainer"></div>
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
                '#phone_number',
                '#address',
                '#country',
                '#state',
                '#city',
                '#pincode'
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

        // Dynamic State & City Dropdowns
        $('#country').on('change', function() {
            var countryId = $(this).val();
            $('#state').html('<option value="">Loading...</option>');
            $('#city').html('<option value="">Select City</option>');
            if (countryId) {
                $.ajax({
                    url: '/get-states/' + countryId,
                    type: 'GET',
                    success: function(states) {
                        var options = '<option value="">Select State</option>';
                        $.each(states, function(index, state) {
                            options += '<option value="' + state.id + '">' + state.name +
                                '</option>';
                        });
                        $('#state').html(options);
                    },
                    error: function() {
                        $('#state').html('<option value="">Error loading states</option>');
                    }
                });
            } else {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
            }
        });

        $('#state').on('change', function() {
            var stateId = $(this).val();
            $('#city').html('<option value="">Loading...</option>');
            if (stateId) {
                $.ajax({
                    url: '/get-cities/' + stateId,
                    type: 'GET',
                    success: function(cities) {
                        var options = '<option value="">Select City</option>';
                        $.each(cities, function(index, city) {
                            options += '<option value="' + city.id + '">' + city.name +
                                '</option>';
                        });
                        $('#city').html(options);
                    },
                    error: function() {
                        $('#city').html('<option value="">Error loading cities</option>');
                    }
                });
            } else {
                $('#city').html('<option value="">Select City</option>');
            }
        });
    </script>
@endsection
