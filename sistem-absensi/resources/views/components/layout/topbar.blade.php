@php
    $topbarUser = Auth::user();
    $topbarRole = $topbarUser?->role ?? 'Admin';
    $topbarRoleLabel = strtolower($topbarRole) === 'admin' ? 'Admin' : ucfirst($topbarRole);
    $topbarName = $topbarUser?->pegawai?->nama_pegawai ?? $topbarUser?->username ?? 'Admin';
    $topbarEmail = $topbarUser?->email ?? 'admin@selada.id';
@endphp

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
                <p class="text-sm font-semibold text-slate-700">Hai, {{ $topbarRoleLabel }}</p>
                <p class="text-xs text-slate-500">Selamat datang kembali</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <div class="relative" x-data="{ profileOpen: false }">
                <button
                    @click="profileOpen = !profileOpen"
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div
                    x-show="profileOpen"
                    @click.outside="profileOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-72 rounded-[2rem] bg-white p-6 shadow-xl border border-slate-100 z-50">
                    
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-primary text-xl font-serif text-white">
                            {{ strtoupper(substr($topbarName, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="truncate text-lg font-bold text-slate-900">{{ $topbarName }}</p>
                            <p class="truncate text-sm text-slate-900">{{ $topbarEmail }}</p>
                        </div>
                    </div>

                    <hr class="mb-5 border-t border-slate-900">

                    <a href="{{ route('admin.manajemen-akun') }}" class="flex items-center gap-4 text-slate-900 transition hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-lg font-medium">Ganti Password</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
