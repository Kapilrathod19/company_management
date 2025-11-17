@php
    $setting = App\Models\Setting::first();
@endphp
<div class="iq-sidebar  sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="header-logo" style="display: block">
            @if (!empty($setting->site_logo))
                <img src="{{ asset('site_logo/' . $setting->site_logo) }}" class="img-fluid rounded-normal light-logo"
                    alt="logo">
            @endif
        </a>
        <div class="side-menu-bt-sidebar-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="side-menu">
                <li class="px-3 pt-3 pb-2 ">
                    <span class="text-uppercase small font-weight-bold">Home</span>
                </li>
                <li class="@if (str_contains(Request::url(), '/dashboard')) active @endif sidebar-layout">
                    <a href="{{ route('admin.dashboard') }}" class="svg-icon">
                        <i class="bi bi-house"></i>
                        <span class="ml-2">Dashboard</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/admin/site_setting')) active @endif sidebar-layout">
                    <a href="{{ route('admin.site_setting') }}" class="svg-icon">
                        <i class="bi bi-gear"></i>
                        <span class="ml-2">Site Setting</span>
                    </a>
                </li>
                <li class="sidebar-layout @if (Request::is('admin/company*')) active @endif">
                    <a href="{{ route('admin.company') }}" class="d-flex align-items-center">
                        <i class="bi bi-buildings me-2" style="font-size: 1.2rem;"></i>
                        <span>Company Master</span>
                    </a>
                </li>
                <li class="sidebar-layout @if (Request::is('admin/admin_users*')) active @endif">
                    <a href="{{ route('admin.users') }}" class="d-flex align-items-center">
                        <i class="bi bi-people me-2" style="font-size: 1.2rem;"></i>
                        <span>Users</span>
                    </a>
                </li>

            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
        <div class="pt-5 pb-5"></div>
    </div>
</div>
