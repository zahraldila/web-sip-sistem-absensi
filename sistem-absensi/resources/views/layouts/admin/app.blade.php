<!DOCTYPE html>
<html lang="id">
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
</head>

<body class="bg-[#F5F7FB] text-gray-800">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- ===================== --}}
        {{-- SIDEBAR DESKTOP --}}
        {{-- ===================== --}}
        <aside
            class="hidden lg:flex fixed left-0 top-0 h-screen w-[280px] bg-white border-r border-gray-200 shadow-sm z-40">

            @include('components.layout.sidebar')

        </aside>

        {{-- ===================== --}}
        {{-- SIDEBAR MOBILE --}}
        {{-- ===================== --}}
        <div x-show="sidebarOpen"
            x-cloak
            class="fixed inset-0 z-50 flex lg:hidden"
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
        {{-- MAIN CONTENT --}}
        {{-- ===================== --}}
        <div class="flex-1 lg:ml-[280px] min-h-screen flex flex-col">


            {{-- ===================== --}}
            {{-- TOPBAR --}}
            {{-- ===================== --}}
            <header
                class="sticky top-0
                       z-30
                       bg-[#F5F7FB]
                       border-b
                       border-transparent">

                @include('components.layout.topbar')

            </header>


            {{-- ===================== --}}
            {{-- PAGE CONTENT --}}
            {{-- ===================== --}}
            <main
                class="
                flex-1
                p-6
                lg:p-8">

                <div class="max-w-[1600px] mx-auto">

                    @yield('content')

                </div>

            </main>


            {{-- ===================== --}}
            {{-- FOOTER --}}
            {{-- ===================== --}}
            <footer>

                @include('components.layout.footer')

            </footer>

        </div>

    </div>


    {{-- ===================== --}}
    {{-- MOBILE SIDEBAR --}}
    {{-- Sprint selanjutnya --}}
    {{-- ===================== --}}

    @stack('modals')

    @stack('scripts')

</body>
</html>