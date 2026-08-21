<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>@yield('title', 'Pegawai | Sistem Absensi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    {{-- Offline connectivity warning banner --}}
    @include('components.layout.offline-banner')
    <div class="min-h-screen flex flex-col">
        @include('components.layout.topbar')

        <main class="flex-1 px-4 py-4 sm:px-5 sm:py-5">
            @yield('content')
        </main>

        @include('components.layout.footer')
    </div>

    {{-- Auto-redirect to login when session expires --}}
    @include('components.layout.session-timeout')
</body>
</html>
