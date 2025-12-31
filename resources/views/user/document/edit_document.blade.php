@extends('user.layout.main_layout')
@section('title', 'User | Edit Document')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card mb-2">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Edit Document</h5>
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

                        {{-- Success --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            <form id="myform" action="{{ route('documents.update', $document->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                     <div class="col-md-12 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title', $document->title) }}">
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <p>Document</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="document"
                                                name="document">
                                            <label class="custom-file-label" for="document">Choose file</label>
                                        </div>
                                    </div>
                                    @if ($document->document)
                                        <div class="mt-2" id="certificate">
                                            <p>
                                                <a href="{{ asset('documents/' . $document->document) }}"
                                                    target="_blank" class="btn btn-info btn-sm">
                                                    View Current Document
                                                </a>
                                            </p>
                                        </div>
                                    @endif

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
                    id: '#title',
                    name: 'Title'
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
