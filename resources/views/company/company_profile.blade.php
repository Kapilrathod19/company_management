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
                            <label class="form-label">Address</label>
                            <input type="text" name="address" id="address"
                                value="{{ old('address', $company->address) }}" class="form-control">
                            <span class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alternate Address</label>
                            <input type="text" name="alternate_address" id="alternate_address"
                                value="{{ old('alternate_address', $company->alternate_address) }}" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $company->country_id == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"></span>

                            </div>
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" id="pincode"
                                value="{{ old('pincode', $company->pincode) }}" class="form-control">
                            <span class="text-danger"></span>

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
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#passwordModal">
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

            // All required fields
            var fieldsToValidate = [
                '#name',
                '#email',
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
