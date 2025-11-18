@extends('user.layout.main_layout')
@section('title', 'User | Add Machine')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Machine</h5>
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
                            <form id="myform" action="{{ route('machine.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">-- Select Category --</option>
                                            <option value="Manufacturing"
                                                {{ old('status') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing
                                            </option>
                                            <option value="Tools" {{ old('status') == 'Tools' ? 'selected' : '' }}>
                                                Tools</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="machine_no" class="form-label">Machine Number</label>
                                        <input type="text" name="machine_no" id="machine_no" class="form-control"
                                            value="{{ old('machine_no') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="machine_name" class="form-label">Machine Name</label>
                                        <input type="text" name="machine_name" id="machine_name" class="form-control"
                                            value="{{ old('machine_name') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="calibration_date" class="form-label">Calibration Date</label>
                                        <input type="date" name="calibration_date" id="calibration_date"
                                            class="form-control" value="{{ old('calibration_date') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="remark" class="form-label">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control" >{{ old('remark') }}</textarea>
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

            const fields = [{
                    id: '#category',
                    name: 'Category'
                },
                {
                    id: '#machine_no',
                    name: 'Machine Number'
                },
                {
                    id: '#machine_name',
                    name: 'Machine Name'
                },
                {
                    id: '#calibration_date',
                    name: 'Calibration Date'
                },
                {
                    id: '#remark',
                    name: 'Remark'
                }
            ];

            function validateField(id, name) {
                const field = $(id);
                const value = field.val().trim();
                let isValid = true;
                let message = '';

                if (!value) {
                    isValid = false;
                    message = `${name} is required.`;
                }

                field.toggleClass('is-invalid', !isValid);
                field.toggleClass('is-valid', isValid);
                field.siblings('.text-danger').text(message);

                return isValid;
            }

            fields.forEach(f => {
                $(f.id).on('input change', function() {
                    validateField(f.id, f.name);
                });
            });

            $('#myform').on('submit', function(e) {
                let valid = true;

                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) valid = false;
                });

                if (!valid) e.preventDefault();
            });

        });
    </script>
@endsection
