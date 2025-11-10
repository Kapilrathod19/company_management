@extends('company.layout.main_layout')
@section('title', 'Company | Profile')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Company Profile</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('company_profile.store') }}" method="POST" id="myForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="form-control">
                            <span class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="form-control">
                            <span class="text-danger"></span>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', $company->phone_number) }}" class="form-control">
                            <span class="text-danger"></span>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Office Address</label>
                            <input type="text" name="address" id="address"
                                value="{{ old('address', $company->address) }}" class="form-control">
                            <span class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Factory Address</label>
                            <input type="text" name="alternate_address" id="alternate_address"
                                value="{{ old('alternate_address', $company->alternate_address) }}" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <select name="state" id="state" class="form-control">
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ $company->state_id == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"></span>

                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <select name="city" id="city" class="form-control">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ $company->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" id="pincode"
                                    value="{{ old('pincode', $company->pincode) }}" class="form-control">
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="gst_no" class="form-label">GST Number</label>
                                <input type="text" name="gst_no" class="form-control" id="gst_no"
                                    value="{{ old('gst_no', $company->gst_no) }}">
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="msme_no" class="form-label">MSME Number</label>
                                <input type="text" name="msme_no" class="form-control" id="msme_no"
                                    value="{{ old('msme_no', $company->msme_no) }}">
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="state_code" class="form-label">State Code</label>
                                <input type="text" name="state_code" class="form-control" id="state_code"
                                    value="{{ old('state_code', $company->state_code) }}">
                                <span class="text-danger"></span>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image"
                                    accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            @if ($company->image)
                                <img src="{{ asset('company_images/' . $company->image) }}" alt="Profile"
                                    class="mt-2 rounded" style="height: 80px;">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#passwordModal">
                            Change Password
                        </button>
                    </form>

                    <!-- Password Modal -->
                    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog"
                        aria-labelledby="passwordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('company_profile.change_password') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Password</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" name="current_password" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" name="new_password" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" required>
                                        </div>
                                        <p class="mt-2 text-danger">Note: Changing your password will log you out.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
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
                '#phone_number',
                '#address',
                '#state',
                '#city',
                '#pincode'
            ];

            $('#phone_number, #pincode').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#myForm').on('input change', fieldsToValidate.join(','), function() {
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
            $('#myForm').submit(function(e) {
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

                if (name === 'email') {
                    if (value !== "") {
                        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    } else {
                        isValid = false;
                    }
                }

                if (name === 'phone_number') {
                    isValid = /^[0-9]{10,15}$/.test(value);
                }

                if (name === 'pincode') {
                    isValid = /^[0-9]{4,10}$/.test(value);
                }

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
