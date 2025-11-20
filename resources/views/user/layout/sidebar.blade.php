@php
    $setting = App\Models\Setting::first();
@endphp
<div class="iq-sidebar  sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
        <a href="{{ route('user.dashboard') }}" class="header-logo" style="display: block">
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
                    <a href="{{ route('user.dashboard') }}" class="svg-icon">
                        <i class="bi bi-house"></i>
                        <span class="ml-2">Dashboard</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/party')) active @endif sidebar-layout">
                    <a href="{{ route('party.index') }}" class="svg-icon">
                        <i class="bi bi-people"></i>
                        <span class="ml-2">Party Master</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/item')) active @endif sidebar-layout">
                    <a href="{{ route('item.index') }}" class="svg-icon">
                        <i class="bi bi-box-seam"></i>
                        <span class="ml-2">Item Master</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/process_master')) active @endif sidebar-layout">
                    <a href="{{ route('process_master.index') }}" class="svg-icon">
                        <i class="bi bi-diagram-3"></i>
                        <span class="ml-2">Process Master</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/process-item')) active @endif sidebar-layout">
                    <a href="{{ route('process.items') }}" class="svg-icon">
                        <i class="bi bi-diagram-3"></i>
                        <span class="ml-2">Item Processes</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/employee')) active @endif sidebar-layout">
                    <a href="{{ route('employee.index') }}" class="svg-icon">
                        <i class="bi bi-people"></i>
                        <span class="ml-2">Employee Master</span>
                    </a>
                </li>
                <li class="@if (str_contains(Request::url(), '/machine')) active @endif sidebar-layout">
                    <a href="{{ route('machine.index') }}" class="svg-icon">
                        <i class="bi bi-gear"></i>
                        <span class="ml-2">Machine Master</span>
                    </a>
                </li>

            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
        <div class="pt-5 pb-5"></div>
    </div>
</div>
