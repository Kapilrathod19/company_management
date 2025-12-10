@extends('user.layout.main_layout')
@section('title', 'User | Edit Calibration')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Calibration</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">

                        {{-- Validation --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form --}}
                        <div class="card-body">
                            <form id="myform" action="{{ route('calibration.update', $calibration->id) }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date', $calibration->date) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Machine Name</label>
                                        <select name="machine_name" id="machine_name" class="form-control">
                                            <option value="">Select Machine Name</option>
                                            @foreach ($machines as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $calibration->machine_name == $item->id ? 'selected' : '' }}>
                                                    {{ $item->machine_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Machine No</label>
                                        <input type="text" name="machine_no" id="machine_no" class="form-control"
                                            value="{{ old('machine_no', $calibration->machine_no) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Calibration Date</label>
                                        <input type="date" name="calibration_date" id="calibration_date"
                                            class="form-control"
                                            value="{{ old('calibration_date', $calibration->calibration_date) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Certificate:</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="certificate"
                                                name="certificate">
                                            <label class="custom-file-label" for="certificate">Choose file</label>
                                        </div>
                                        @if ($calibration->certificate)
                                            <p class="mt-2">
                                                <a href="{{ asset('certificate/' . $calibration->certificate) }}"
                                                    target="_blank" class="btn btn-sm btn-info">View Certificate</a>
                                            </p>
                                        @endif
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary mt-2">Update</button>

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
                    id: '#date',
                    name: 'Date'
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
                let field = $(id);
                let value = field.val().trim();
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

        // Load details when machine is changed
        $('#machine_name').change(function() {
            let id = $(this).val();

            if (!id) {
                $('#machine_no').val('');
                $('#calibration_date').val('');
                return;
            }

            $.ajax({
                url: "{{ route('machine.details', '') }}/" + id,
                type: "GET",
                success: function(res) {
                    if (res.status) {
                        $('#machine_no').val(res.machine_no);
                        $('#calibration_date').val(res.calibration_date);
                    }
                }
            });
        });
    </script>
@endsection
