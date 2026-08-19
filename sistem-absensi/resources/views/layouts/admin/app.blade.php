<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Absensi')</title>

    <!-- FontAwesome 6 CDN for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            /* Using CSS color-mix to create a darker hover shade */
            --color-primary-hover: color-mix(in srgb, {{ $primaryColor }} 80%, black);
        }
    </style>
</head>

<body class="bg-[#F5F7FB] text-gray-800 min-h-full flex flex-col antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-1 w-full relative">

        {{-- ===================== --}}
        {{-- SIDEBAR DESKTOP (Layar Lebar >= 1280px / xl) --}}
        {{-- ===================== --}}
        <aside
            class="hidden xl:flex fixed left-0 top-0 h-screen w-[280px] bg-white border-r border-gray-200 shadow-sm z-40">

            @include('components.layout.sidebar')

        </aside>

        {{-- ===================== --}}
        {{-- SIDEBAR MOBILE & TABLET (< 1280px) --}}
        {{-- ===================== --}}
        <div x-show="sidebarOpen"
            x-cloak
            class="fixed inset-0 z-50 flex xl:hidden"
            aria-hidden="true">

            <div x-show="sidebarOpen"
                x-transition.opacity
                class="absolute inset-0 bg-slate-900/50"
                @click="sidebarOpen = false"></div>

            <aside x-show="sidebarOpen"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition duration-300 ease-in"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                @click.away="sidebarOpen = false"
                class="relative flex h-full w-[280px] flex-col overflow-y-auto bg-white shadow-xl">

                @include('components.layout.sidebar')

            </aside>

        </div>

        {{-- ===================== --}}
        {{-- MAIN CONTENT WRAPPER --}}
        {{-- ===================== --}}
        <div class="flex-1 xl:ml-[280px] min-h-screen flex flex-col w-full min-w-0">

            {{-- ===================== --}}
            {{-- TOPBAR --}}
            {{-- ===================== --}}
            <header class="sticky top-0 z-30 bg-[#F5F7FB] border-b border-transparent">
                @include('components.layout.topbar')
            </header>

            {{-- ===================== --}}
            {{-- PAGE CONTENT --}}
            {{-- ===================== --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1600px] mx-auto min-w-0">
                @yield('content')
            </main>

            {{-- ===================== --}}
            {{-- FOOTER --}}
            {{-- ===================== --}}
            <footer class="w-full mt-auto">
                @include('components.layout.footer')
            </footer>

        </div>

    </div>

    @stack('modals')

    @stack('scripts')

</body>
</html>