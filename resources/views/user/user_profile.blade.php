@extends('user.layout.main_layout')
@section('title', 'User | Profile')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">User Profile</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('user_profile.store') }}" method="POST" id="myForm"
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
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $user->mobile) }}"
                                class="form-control">
                            <span class="text-danger"></span>

                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control"
                                value="{{ old('department', $user->department) }}">
                            <span class="text-danger"></span>
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
                            <form action="{{ route('user_profile.change_password') }}" method="POST">
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
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                '#mobile',
                '#department'
            ];

            $('#mobile').on('input', function() {
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
