@extends('user.layout.main_layout')
@section('title', 'User | Add Calibration')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Calibration</h5>
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
                            <form id="myform" action="{{ route('calibration.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date', date('Y-m-d')) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="machine_name" class="form-label">Machine Name</label>
                                        <select name="machine_name" id="machine_name" class="form-control">
                                            <option value="">Select Machine Name</option>
                                            @if (isset($machines) && $machines->count() > 0)
                                                @foreach ($machines as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('machine_name') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->machine_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Machine No</label>
                                        <input type="text" name="machine_no" id="machine_no" class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Calibration Date</label>
                                        <input type="date" name="calibration_date" id="calibration_date"
                                            class="form-control">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Certificate:</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="certificate"
                                                name="certificate">
                                            <label class="custom-file-label" for="certificate">Choose file</label>
                                        </div>
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

            $('#contact_no').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            const fields = [{
                    id: '#date',
                    name: 'date'
                },
                {
                    id: '#machine_name',
                    name: 'Machine Name'
                },
                {
                    id: '#machine_no',
                    name: 'Machine No'
                },
                {
                    id: '#calibration_date',
                    name: 'Calibration Date'
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

            // Form submit validation
            $('#myform').on('submit', function(e) {
                let valid = true;

                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) valid = false;
                });

                if (!valid) e.preventDefault();
            });

        });

        $('#machine_name').change(function() {
            let id = $(this).val();

            if (id) {
                $.ajax({
                    url: "{{ route('machine.details', '') }}/" + id,
                    type: "GET",
                    success: function(res) {
                        if (res.status) {
                            $('#machine_no').val(res.machine_no);
                            $('#calibration_date').val(res.calibration_date);
                        } else {
                            $('#machine_no').val('');
                            $('#calibration_date').val('');
                        }
                    }
                });
            } else {
                $('#machine_no').val('');
                $('#calibration_date').val('');
            }
        });
    </script>
@endsection
