@extends('admin.layout.main_layout')
@section('title', 'Admin | Site Setting')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Site Setting</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('site_setting.store') }}" method="POST" id="myForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id"
                                    @if (isset($setting)) value="{{ $setting->id }}" @endif>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Site Name</label>
                                    <input type="text" name="site_name" class="form-control" id="site_name"
                                        @if (isset($setting->site_name)) value="{{ $setting->site_name }}" @endif>
                                    <span class="text-danger"></span>
                                </div>
                                <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="site_logo" name="site_logo"
                                        accept="image/*">
                                    <label class="custom-file-label" for="site_logo">Choose Logo</label>
                                    <input type="hidden" name="old_site_logo"
                                        @if (isset($setting->site_logo)) value="{{ $setting->site_logo }}" @endif>
                                </div>
                                <div class="col-sm-3 mb-3" id="imageContainer">
                                    @if (isset($setting->site_logo))
                                        <img src="{{ asset('site_logo/' . $setting->site_logo) }}" alt
                                            class="h-auto rounded" />
                                    @endif
                                </div>

                                <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var imageInput = document.getElementById('site_logo');
                var imageContainer = document.getElementById('imageContainer');
                imageInput.addEventListener('change', function() {
                    var file = imageInput.files[0];

                    if (file && file.type.startsWith('image')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var imgElement = document.createElement('img');
                            imgElement.src = e.target.result;
                            imageContainer.innerHTML = '';
                            imageContainer.appendChild(imgElement);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imageContainer.innerHTML = '';
                    }
                });
            });

            $(document).ready(function() {
                $('#site_name').on('input', function() {
                    validateField($(this));
                });
                $('#myForm').submit(function(e) {
                    e.preventDefault();

                    var site_name = validateField($('#site_name'));

                    if (site_name) {
                        this.submit();
                    }
                });

                function validateField(field) {
                    var isValid = true;
                    var errorMessage = "";

                    if (field.val().trim() === "") {
                        errorMessage = `${field.attr('name')} is required.`;
                        isValid = false;
                    }
                    field.toggleClass('is-invalid', !isValid).toggleClass('is-valid', isValid);
                    field.siblings('.text-danger').text(errorMessage);

                    return isValid;
                }
            });
        </script>
    @endsection
