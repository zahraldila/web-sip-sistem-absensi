<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-5 lg:px-6">
        <div class="flex items-center gap-4">
            <button
                type="button"
                @click="sidebarOpen = true"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-100 lg:hidden"
                aria-label="Buka sidebar">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div>
                <p class="text-sm font-semibold text-slate-700">Hai, Admin</p>
                <p class="text-xs text-slate-500">Selamat datang kembali</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-3xl bg-slate-100 px-4 py-2 text-sm text-slate-600 transition hover:bg-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                Notifikasi
            </button>

            <div class="flex items-center gap-3 rounded-3xl bg-slate-100 px-4 py-2">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-300 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 21v-2a4 4 0 0 0-8 0v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Farida Aryani</p>
                    <p class="text-xs text-slate-500">Admin</p>
                </div>
            </div>
        </div>
    </div>
</header>
