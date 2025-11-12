<footer class="iq-footer">
    <div class="container-fluid">
        <div class="row">
            @php
                $setting = App\Models\Setting::first();
            @endphp
       
            <div class="col-lg-12 text-right">
                <span class="mr-1">
                    Copyright {{ date('Y')}} <a href="#" class=""> @if (!empty($setting->site_name)) {{$setting->site_name}} @endif</a>
                    All Rights Reserved.
                </span>
            </div>

        </div>
    </div>
</footer>