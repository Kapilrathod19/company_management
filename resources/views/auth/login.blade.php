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
        @endif Login
    </title>

    @if (!empty($setting->site_logo))
        <link rel="shortcut icon" href="{{ asset('site_logo/' . $setting->site_logo) }}" />
    @endif

    <link rel="stylesheet" href="{{asset('admin/assets/css/backend-plugin.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/backende209.css?v=1.0.0')}}">
</head>

<body class=" ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <section class="login-content">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-md-5">
                        <div class="card p-3">
                            <div class="card-body">
                                @php
                                    $setting = App\Models\Setting::first();
                                @endphp
                                <div class="auth-logo">
                                    @if (!empty($setting->site_logo))
                                        <img src="{{ asset('site_logo/' . $setting->site_logo) }}" class="img-fluid  rounded-normal  darkmode-logo" alt="logo">
                                        <img src="{{ asset('site_logo/' . $setting->site_logo) }}" class="img-fluid rounded-normal light-logo" alt="logo">
                                    @endif
                                </div>
                                <h3 class="mb-3 font-weight-bold text-center">Sign In</h3>
                                <p class="text-center text-secondary mb-4">Log in to your account to continue</p>
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                                @endif
                                <form action="{{route('check.login')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="text-secondary">Email</label>
                                                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="text-secondary">Password</label>
                                                </div>
                                                <input class="form-control" type="password" name="password" placeholder="Enter Password">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mt-2">Log In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Backend Bundle JavaScript -->
    <script src="{{asset('admin/assets/js/backend-bundle.min.js')}}"></script>
    <!-- Chart Custom JavaScript -->
    <script src="{{asset('admin/assets/js/customizer.js')}}"></script>

    <script src="{{asset('admin/assets/js/sidebar.js')}}"></script>

    <!-- Flextree Javascript-->
    <script src="{{asset('admin/assets/js/flex-tree.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/tree.js')}}"></script>

    <!-- Table Treeview JavaScript -->
    <script src="{{asset('admin/assets/js/table-treeview.js')}}"></script>

    <!-- SweetAlert JavaScript -->
    <script src="{{asset('admin/assets/js/sweetalert.js')}}"></script>

    <!-- Vectoe Map JavaScript -->
    <script src="{{asset('admin/assets/js/vector-map-custom.js')}}"></script>

    <!-- Chart Custom JavaScript -->
    <script src="{{asset('admin/assets/js/chart-custom.js')}}"></script>
    <script src="{{asset('admin/assets/js/charts/01.js')}}"></script>
    <script src="{{asset('admin/assets/js/charts/02.js')}}"></script>

    <!-- slider JavaScript -->
    <script src="{{asset('admin/assets/js/slider.js')}}"></script>


    <!-- app JavaScript -->
    <script src="{{asset('admin/assets/js/app.js')}}"></script>

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
</body>

</html>