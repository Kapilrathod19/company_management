@extends('admin.layout.main_layout')
@section('title', 'Admin | Profile')
@section('content')

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">
                        Admin Profile
                    </h5>
                    <form action="{{ route('profile.store') }}" method="POST" id="myForm" enctype="multipart/form-data">
                        @csrf
                        @if (isset($user->id))
                            <input type="hidden" name="id" value="{{ $user->id }}">
                        @endif
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                @if (isset($user->name)) value="{{ $user->name }}" @endif>
                            <span class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                @if (isset($user->email)) value="{{ $user->email }}" @endif>
                            <span class="text-danger"></span>
                        </div>
                        <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Change Password
                        </button>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('profile.change_password') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="property_type" class="form-label">Current Password</label>
                                                <input type="password" name="current_password" id="current_password"
                                                    class="form-control" aria-describedby="password" required />
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="property_type" class="form-label">New Password</label>
                                                <input type="password" name="new_password" id="new_password"
                                                    class="form-control" aria-describedby="password" required />
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="property_type" class="form-label">Confirm Password</label>
                                                <input type="password" name="confirm_password" id="confirm_password"
                                                    class="form-control" aria-describedby="password" required />
                                            </div>
                                            <div class="col-12 mb-3">
                                                Note: If you will change the psssword, you will be logouted.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
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
