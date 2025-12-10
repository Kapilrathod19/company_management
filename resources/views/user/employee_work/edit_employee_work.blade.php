@extends('user.layout.main_layout')
@section('title', 'User | Edit Employee Work')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Employee Work</h5>
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

                        <div class="card-body">
                            <form id="myform" action="{{ route('employee_work.update', $item->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date', $item->date) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="employee_id" class="form-label">Employees</label>
                                        <select name="employee_id" id="employee_id" class="form-control">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}"
                                                    {{ old('employee_id', $item->employee_id) == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->emp_no }} - {{ $emp->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="work_done" class="form-label">Work Done</label>
                                        <input type="text" name="work_done" id="work_done" class="form-control"
                                            value="{{ old('work_done', $item->work_done) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="number" name="weight" id="weight" class="form-control"
                                            value="{{ old('weight', $item->weight) }}">
                                        <span class="text-danger"></span>
                                    </div>

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

            $('#contact_no').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            const fields = [{
                    id: '#date',
                    name: 'date'
                },
                {
                    id: '#employee_id',
                    name: 'Employee Name'
                },
                {
                    id: '#work_done',
                    name: 'Work Done'
                },
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
    </script>
@endsection
