<div class="flex h-full w-full flex-col bg-white overflow-y-auto">
    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="px-5 pt-5">

        <div class="flex items-start gap-3">

            {{-- Logo --}}
            <div class>

                <img
                    src="{{ asset('images/logo-sip.png') }}"
                    alt="Logo SIP"
                    class="h-10 w-10 object-contain">

            </div>

            {{-- Company --}}
            <div class="pt-1">

                <h2 class="text-[16px] font-semibold text-slate-900">
                    PT Selada Indonesia Produktif
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Admin
                </p>

            </div>

        </div>

    </div>


    {{-- Divider --}}
    <div class="mx-5 mt-5 border-b border-slate-200"></div>


    {{-- ========================= --}}
    {{-- MENU --}}
    {{-- ========================= --}}
    <nav class="mt-4 flex-1 px-3 pb-6">

        <p class="mb-3 px-3 text-sm font-medium text-slate-500">
            Menu
        </p>

        <div class="space-y-2">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="relative flex items-center gap-3 rounded-2xl {{ request()->routeIs('admin.dashboard', 'admin.dashboard.index') ? 'bg-blue-50 text-[#123D91]' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} px-4 py-3 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h7v7H3zM14 3h7v4h-7zM14 11h7v10h-7zM3 14h7v7H3z"/>

                </svg>

                <span class="text-sm font-semibold">
                    Dashboard
                </span>

                @if(request()->routeIs('admin.dashboard', 'admin.dashboard.index'))
                <span
                    class="absolute right-0 top-2 bottom-2 w-1 rounded-full bg-[#123D91]">
                </span>
                @endif

            </a>

            {{-- Laporan Kehadiran --}}
            <a href="{{ route('admin.laporan-kehadiran') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.laporan-kehadiran') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 19v-10M12 19V5M20 19v-14"/>

                </svg>

                <span class="text-sm font-medium">
                    Laporan Kehadiran
                </span>

            </a>

            {{-- Manajemen Akun --}}
            <a href="{{ route('admin.manajemen-akun') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.manajemen-akun') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 3.13a4 4 0 0 1 0 7.75"/>

                </svg>

                <span class="text-sm font-medium">
                    Manajemen Akun
                </span>

            </a>

            {{-- Persetujuan --}}
            <a href="{{ route('admin.persetujuan') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.persetujuan') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 0 0-8 0v1a4 4 0 0 0 4 4h1a2 2 0 0 1 2 2v2a3 3 0 0 1-3 3H8"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 15l2 2 4-4"/>

                </svg>

                <span class="text-sm font-medium">
                    Persetujuan
                </span>

            </a>

            {{-- Log Aktivitas --}}
            <a href="{{ route('admin.log-aktivitas') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.log-aktivitas') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 3h10v2H7z"/>

                </svg>

                <span class="text-sm font-medium">
                    Log Aktivitas
                </span>

            </a>

            {{-- Tampilan & Branding --}}
            <a href="{{ route('admin.tampilan-branding') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.tampilan-branding') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20.24 4.24a5 5 0 0 0-7.07 0L7 10.41 4.76 7.76a5 5 0 0 0-7.07 7.07l7.07 7.07 11.17-11.17a5 5 0 0 0 0-7.07z"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 10.41L13.59 4"/>

                </svg>

                <span class="text-sm font-medium">
                    Tampilan & Branding
                </span>

            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('admin.pengaturan') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.pengaturan') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83l-1.41 1.41a2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0L2.29 19.7a2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 2.68 15a1.65 1.65 0 0 0-1.51-1H1a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83L3.7 2.29a2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 8.29 2a1.65 1.65 0 0 0 1.82.33H10a1.65 1.65 0 0 0 1-1.51V1a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v.09c.03.44.23.86.57 1.16l.06.06a1.65 1.65 0 0 0 1.82.33h.01z"/>

                </svg>

                <span class="text-sm font-medium">
                    Pengaturan
                </span>

            </a>

            {{-- Bantuan --}}
            <a href="{{ route('admin.bantuan') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.bantuan') ? 'bg-slate-50 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-[#123D91]' }} transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9a3 3 0 1 0-3-3"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 17h.01"/>
                    <circle cx="12" cy="12" r="9"/>

                </svg>

                <span class="text-sm font-medium">
                    Bantuan
                </span>

            </a>

        </div>

    </nav>


    {{-- Divider --}}
    <div class="mx-5 border-t border-slate-200"></div>


    {{-- ========================= --}}
    {{-- LOGOUT --}}
    {{-- ========================= --}}
    <div class="p-5" x-data="{ logoutOpen: false }">

        {{-- Hidden logout form --}}
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>

        {{-- Trigger button --}}
        <button
            type="button"
            @click="logoutOpen = true"
            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#123D91] py-3 text-sm font-medium text-white transition hover:bg-[#0F3277]">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 17l5-5-5-5"/>
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 12H9"/>

            </svg>

            Logout

        </button>

        {{-- Confirmation Modal --}}
        <div
            x-show="logoutOpen"
            x-cloak
            class="fixed inset-0 z-[60] flex items-center justify-center"
            aria-modal="true"
            role="dialog">

            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-white/70 backdrop-blur-sm"
                @click="logoutOpen = false">
            </div>

            {{-- Dialog Card --}}
            <div
                x-show="logoutOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm rounded-3xl bg-white px-10 py-10 shadow-2xl text-center">
                {{-- Warning Icon --}}
                <div class="flex justify-center mb-6">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-12 w-12 text-white"
                            fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 5a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1zm0 10a1.25 1.25 0 1 1 0-2.5A1.25 1.25 0 0 1 12 17z"/>
                        </svg>
                    </div>
                </div>

                {{-- Message --}}
                <p class="mb-8 text-base font-medium text-slate-800">
                    Apakah anda ingin keluar?
                </p>

                {{-- Actions --}}
                <div class="flex items-center justify-center gap-4">

                    <button
                        type="button"
                        @click="logoutOpen = false"
                        class="rounded-full border border-red-300 bg-red-50 px-8 py-2.5 text-sm font-semibold text-red-500 transition hover:bg-red-100">
                        Tidak
                    </button>

                    <button
                        type="button"
                        @click="document.getElementById('logout-form').submit()"
                        class="rounded-full bg-red-500 px-8 py-2.5 text-sm font-semibold text-white transition hover:bg-red-600">
                        Ya
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>