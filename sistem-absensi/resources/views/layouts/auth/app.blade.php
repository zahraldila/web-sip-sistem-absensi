<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Auth | Sistem Absensi')</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    
    <style>
        :root {
            @php
                $primaryColor = \App\Models\Setting::get('primary_color', '#123D91');
            @endphp
            --color-primary: {{ $primaryColor }};
            --color-primary-hover: color-mix(in srgb, {{ $primaryColor }} 80%, black);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex items-center justify-center">
    <div class="w-full max-w-md px-4 py-8 sm:px-6 sm:py-10">
        @yield('content')
    </div>
</body>
</html>
