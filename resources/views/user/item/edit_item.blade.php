@extends('user.layout.main_layout')
@section('title', 'User | Edit Item')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Item</h5>
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
                            <form id="editForm" action="{{ route('item.update', $item->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="Finished Item"
                                                {{ $item->category == 'Finished Item' ? 'selected' : '' }}>Finished Item
                                            </option>
                                            <option value="Raw Material"
                                                {{ $item->category == 'Raw Material' ? 'selected' : '' }}>Raw Material
                                            </option>
                                            <option value="Bought Out"
                                                {{ $item->category == 'Bought Out' ? 'selected' : '' }}>Bought Out</option>
                                            <option value="Consumable"
                                                {{ $item->category == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                                            <option value="Tools" {{ $item->category == 'Tools' ? 'selected' : '' }}>Tools
                                            </option>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="part_number" class="form-label">Part Number</label>
                                        <input type="text" name="part_number" id="part_number" class="form-control"
                                            value="{{ old('part_number', $item->part_number) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $item->description) }}</textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" name="unit" id="unit" class="form-control"
                                            value="{{ old('unit', $item->unit) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="text" name="quantity" id="quantity" class="form-control"
                                            value="{{ old('quantity', $item->quantity) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="text" name="weight" id="weight" class="form-control"
                                            value="{{ old('weight', $item->weight) }}">
                                        <span class="text-danger"></span>
                                    </div>
                                    
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('item.index') }}" class="btn btn-secondary">Cancel</a>
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
                    id: '#part_number',
                    name: 'Part Number'
                },
                {
                    id: '#unit',
                    name: 'Unit'
                },
                {
                    id: '#description',
                    name: 'Description'
                }
            ];

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

            fields.forEach(f => {
                $(f.id).on('input change', function() {
                    validateField(this, f.name);
                });
            });

            $('#editForm').on('submit', function(e) {
                let valid = true;
                fields.forEach(f => {
                    if (!validateField(f.id, f.name)) {
                        valid = false;
                    }
                });
                if (!valid) e.preventDefault();
            });
        });
    </script>
@endsection
