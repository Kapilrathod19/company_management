@extends('user.layout.main_layout')
@section('title', ' User | Add Item')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Add Item</h5>
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
                            <form id="myform" action="{{ route('item.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="Finished Item">Finished Item</option>
                                            <option value="Raw Material">Raw Material</option>
                                            <option value="Bought Out">Bought Out</option>
                                            <option value="Consumable">Consumable</option>
                                            <option value="Tools">Tools</option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="part_number" class="form-label">Part Number</label>
                                        <input type="text" name="part_number" id="part_number" class="form-control"
                                            value="{{ old('part_number') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="unit" class="form-label">unit</label>
                                        <input type="text" name="unit" class="form-control" id="unit"
                                            value="{{ old('unit') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="text" name="quantity" class="form-control" id="quantity"
                                            value="{{ old('quantity') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="text" name="weight" class="form-control" id="weight"
                                            value="{{ old('weight') }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" rows="2" class="form-control">{{ old('description') }}</textarea>
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
            // Define fields to validate
            const fields = [{
                    id: '#category',
                    name: 'Category'
                },
                {
                    id: '#part_number',
                    name: 'Part Number'
                },
                {
                    id: '#unit',
                    name: 'Unit'
                },
                {
                    id: '#quantity',
                    name: 'Quantity'
                },
                {
                    id: '#weight',
                    name: 'Weight'
                },
                {
                    id: '#description',
                    name: 'Description'
                }
            ];

            // Function to validate a single field
            function validateField(field, fieldName) {
                const value = $(field).val().trim();
                const isValid = value !== '';

                if (!isValid) {
                    $(field).addClass('is-invalid').removeClass('is-valid');
                    $(field).siblings('.text-danger').text(fieldName + ' is required.');
                } else {
                    $(field).addClass('is-valid').removeClass('is-invalid');
                    $(field).siblings('.text-danger').text('');
                }

                return isValid;
            }

            // Live validation on input/change
            fields.forEach(f => {
                $(f.id).on('input change', function() {
                    validateField(this, f.name);
                });
            });

            // Form submit validation
            $('#myform').on('submit', function(e) {
                let isFormValid = true;

                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) {
                        isFormValid = false;
                    }
                });

                if (!isFormValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
