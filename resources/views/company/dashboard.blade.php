@extends('company.layout.main_layout')
@section('title', 'Company | Dashboard')
@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-white rounded shadow-sm p-4 position-relative overflow-hidden">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="header-title">
                                        <h2 class="card-title">
                                            Welcome Back,
                                            <span class="text-primary">
                                                {{ Auth::user()->name ?? 'Guest' }}
                                            </span>
                                        </h2>
                                    </div>
                                </div>
                                <p class="mt-3">
                                    You are logged in as
                                    <strong>{{ ucfirst(Auth::user()->role ?? '') }}</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
