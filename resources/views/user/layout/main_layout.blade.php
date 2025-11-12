<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $setting = App\Models\Setting::first();
    @endphp

    <title>
        @if (!empty($setting->site_name))
            {{ $setting->site_name }}
        @endif @yield('title')
    </title>

    @if (!empty($setting->site_logo))
        <link rel="shortcut icon" href="{{ asset('site_logo/' . $setting->site_logo) }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('admin/assets/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/backende209.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custome.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap-icons/bootstrap-icons.min.css') }}" />
    <style>
        header.header-nav.menu_style_home_one.style2 .ace-responsive-menu li a {
            font-size: 11px !important;
        }
    </style>

</head>

<body class="">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    @include('user.layout.header')
    @include('user.layout.sidebar')
    <!-- Wrapper Start -->
    <div class="wrapper">
        @yield('content')
    </div>
    <!-- Wrapper End-->
    @include('user.layout.footer')

    <script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/backend-bundle.min.js') }}"></script>
    <!-- Chart Custom JavaScript -->
    <script src="{{ asset('admin/assets/js/customizer.js') }}"></script>

    <script src="{{ asset('admin/assets/js/sidebar.js') }}"></script>

    <!-- Flextree Javascript-->
    <script src="{{ asset('admin/assets/js/flex-tree.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/tree.js') }}"></script>

    <!-- Table Treeview JavaScript -->
    <script src="{{ asset('admin/assets/js/table-treeview.js') }}"></script>

    <!-- SweetAlert JavaScript -->
    <script src="{{ asset('admin/assets/js/sweetalert.js') }}"></script>

    <!-- Vectoe Map JavaScript -->
    <script src="{{ asset('admin/assets/js/vector-map-custom.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script src="{{ asset('admin/assets/js/chart-custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/charts/01.js') }}"></script>
    <script src="{{ asset('admin/assets/js/charts/02.js') }}"></script>

    <!-- slider JavaScript -->
    <script src="{{ asset('admin/assets/js/slider.js') }}"></script>

    <!--toastr-->
    <script src="{{ asset('admin/assets/js/toastr.min.js') }}"></script>

    <!--ckeditor-->
    <script src="{{ asset('admin/assets/js/ckeditor/ckeditor.js') }}"></script>

    <!-- app JavaScript -->
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", 'Success')
        </script>
    @endif
    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", 'Error')
        </script>
    @endif
    @yield('scripts')
</body>

</html>
