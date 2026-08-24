@php
    $pageTitle = 'Live TV Dashboard - PT Selada Indonesia Produktif';
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles & Scripts (Tailwind & Alpine via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        /* Custom subtle scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #CBD5E1;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 flex flex-col justify-between overflow-x-hidden selection:bg-primary selection:text-white"
    x-data="tvDashboard('{{ $selectedDate }}', '{{ $selectedCabang }}')"
    x-cloak
>

    <!-- Background Decoration Grid -->
    <div class="fixed inset-0 pointer-events-none opacity-40 z-0 bg-[radial-gradient(#CBD5E1_1px,transparent_1px)] [background-size:24px_24px]"></div>

    <!-- Header Section -->
    <header class="relative z-10 w-full px-4 sm:px-6 lg:px-10 py-3 sm:py-4 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs flex-shrink-0">
        <div class="max-w-[1920px] mx-auto flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-4">
            
            <!-- Left: Logo & Company Name -->
            <div class="flex items-center justify-between w-full md:w-auto gap-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center p-1.5 overflow-hidden flex-shrink-0">
                        <img src="{{ company_logo_url() }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-base sm:text-lg lg:text-xl font-black text-slate-900 tracking-tight leading-tight">
                            PT Selada Indonesia Produktif
                        </h1>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                            Live Attendance Dashboard
                        </p>
                    </div>
                </div>

                <!-- Clock for Mobile Screen only -->
                <div class="text-right md:hidden">
                    <div class="text-xl font-extrabold text-slate-900 tracking-tight leading-none" x-text="clockTime">
                        00:00:00
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400 mt-0.5" x-text="clockDate">
                        ...
                    </div>
                </div>
            </div>

            <!-- Center: Dynamic Multi-Cabang Interactive Tab Switcher (From Database) -->
            <div class="flex items-center bg-slate-100/90 p-1 sm:p-1.5 rounded-xl sm:rounded-2xl border border-slate-200 shadow-inner overflow-x-auto no-scrollbar max-w-full">
                <!-- Tab: Semua Cabang -->
                <button type="button" 
                    @click="setCabang('all')" 
                    :class="cabang === 'all' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 font-semibold hover:text-slate-800'"
                    class="px-3 sm:px-3.5 py-1.5 rounded-lg sm:rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-layer-group text-xs text-primary"></i>
                    <span>Semua Cabang</span>
                </button>

                <!-- Dynamic Branch Tabs from Database -->
                <template x-for="b in branches" :key="b.lokasi_id">
                    <button type="button" 
                        @click="setCabang(b.lokasi_id.toString())" 
                        :class="cabang === b.lokasi_id.toString() || cabang.toLowerCase() === b.nama_kantor.toLowerCase() ? 'bg-white text-primary shadow-sm font-black' : 'text-slate-500 font-semibold hover:text-slate-800'"
                        class="px-3 sm:px-3.5 py-1.5 rounded-lg sm:rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5 whitespace-nowrap flex-shrink-0">
                        <i class="fa-solid fa-building text-xs text-primary"></i>
                        <span x-text="b.nama_kantor"></span>
                    </button>
                </template>
            </div>

            <!-- Right Info & Clock (Desktop / Tablet view) -->
            <div class="hidden md:flex items-center gap-6">
                <!-- Live Pulsing Indicator -->
                <div class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200/60 shadow-xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Live System</span>
                </div>

                <!-- Clock -->
                <div class="text-right border-l border-slate-200/80 pl-6">
                    <div class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-none" x-text="clockTime">
                        00:00:00
                    </div>
                    <div class="text-xs font-semibold text-slate-400 mt-1" x-text="clockDate">
                        ...
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 max-w-[1920px] w-full mx-auto p-4 sm:p-6 lg:p-10 flex flex-col justify-between">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-8 items-start">
            
            <!-- Left Side: Summary Metrics (7 of 12 cols / ~60% width) -->
            <section class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4 sm:gap-6">
                
                <!-- Section Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                                Ringkasan Kehadiran
                            </h2>
                            <span class="bg-primary/10 text-primary border border-primary/20 text-xs font-bold px-2.5 py-0.5 rounded-full" x-text="cabangTitle"></span>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-400 mt-0.5">
                            Status kehadiran pegawai secara real-time
                        </p>
                    </div>
                    
                    <!-- Color Legend -->
                    <div class="hidden sm:flex items-center gap-3 text-xs font-bold text-slate-500 bg-white px-3.5 py-2 rounded-xl border border-slate-200/70 shadow-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Check In</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                            <span>Check Out</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span>Belum Hadir</span>
                        </div>
                    </div>
                </div>

                <!-- Top Two Hero Cards (Pegawai Hadir vs Belum Hadir) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    
                    <!-- Hero Card 1: Pegawai Hadir -->
                    <div class="bg-white rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 lg:p-7 border border-emerald-100 shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[190px] sm:min-h-[220px]">
                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-50 rounded-full opacity-60 pointer-events-none"></div>
                        
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-[#12B76A] flex items-center justify-center text-xl sm:text-2xl shadow-xs border border-emerald-100/80 relative">
                                    <i class="fa-solid fa-user-check"></i>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"></span>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#12B76A] tracking-tight leading-none" x-text="totalHadir">
                                        {{ $totalHadir }}
                                    </div>
                                    <div class="text-slate-500 text-xs sm:text-sm lg:text-base font-bold mt-1">
                                        Pegawai Hadir (<span x-text="cabangShortTitle"></span>)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-breakdown: Sedang Bekerja vs Sudah Pulang -->
                        <div class="grid grid-cols-2 gap-2.5 sm:gap-3 mt-4 pt-4 border-t border-slate-100 relative z-10">
                            <div class="bg-emerald-50/60 rounded-xl sm:rounded-2xl p-2.5 sm:p-3.5 border border-emerald-100/60">
                                <div class="flex items-center gap-1.5 text-[10px] sm:text-xs font-bold text-emerald-800 uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Sedang Bekerja</span>
                                </div>
                                <div class="text-xl sm:text-2xl font-black text-emerald-700 mt-0.5 sm:mt-1" x-text="sedangBekerja">
                                    {{ $sedangBekerja }}
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-2.5 sm:p-3.5 border border-slate-100">
                                <div class="flex items-center gap-1.5 text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                    <span>Sudah Check Out</span>
                                </div>
                                <div class="text-xl sm:text-2xl font-black text-slate-700 mt-0.5 sm:mt-1" x-text="sudahCheckOut">
                                    {{ $sudahCheckOut }}
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Hero Card 2: Belum Hadir -->
                    <div class="bg-white rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 lg:p-7 border border-rose-100 shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[190px] sm:min-h-[220px]">
                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-rose-50 rounded-full opacity-60 pointer-events-none"></div>

                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-rose-50 text-[#F04438] flex items-center justify-center text-xl sm:text-2xl shadow-xs border border-rose-100/80">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                                <div>
                                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#F04438] tracking-tight leading-none" x-text="belumHadir">
                                        {{ $belumHadir }}
                                    </div>
                                    <div class="text-slate-500 text-xs sm:text-sm lg:text-base font-bold mt-1">
                                        Belum Hadir
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress / Summary Note -->
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs sm:text-sm font-bold text-slate-500 relative z-10">
                            <span class="text-rose-600 font-semibold text-[11px] sm:text-xs">Total Karyawan Terdaftar</span>
                            <span class="font-extrabold text-slate-800 text-sm sm:text-base" x-text="totalPegawai + ' Orang'">{{ $totalPegawai }} Orang</span>
                        </div>

                    </div>

                </div>

                <!-- Bottom 3 Pill Cards: WFO vs WFH/WFC vs Sakit/Izin -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                    
                    <!-- 1. WFO Lokasi Kantor -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg sm:text-xl border border-blue-100 flex-shrink-0">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-none" x-text="wfoCount">
                                {{ $wfoCount }}
                            </div>
                            <div class="text-[11px] sm:text-xs font-bold text-slate-500 mt-1">
                                WFO Lokasi
                            </div>
                        </div>
                    </div>

                    <!-- 2. WFH / WFC -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg sm:text-xl border border-purple-100 flex-shrink-0">
                            <i class="fa-solid fa-house-laptop"></i>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-none" x-text="wfhCount">
                                {{ $wfhCount }}
                            </div>
                            <div class="text-[11px] sm:text-xs font-bold text-slate-500 mt-1">
                                WFH / WFC
                            </div>
                        </div>
                    </div>

                    <!-- 3. Sakit / Izin -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg sm:text-xl border border-amber-100 flex-shrink-0">
                            <i class="fa-solid fa-notes-medical"></i>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-none" x-text="sakitCount">
                                {{ $sakitCount }}
                            </div>
                            <div class="text-[11px] sm:text-xs font-bold text-slate-500 mt-1">
                                Sakit / Izin
                            </div>
                        </div>
                    </div>

                </div>

            </section>

            <!-- Right Side: Daftar Pegawai Hadir (5 of 12 cols / 1/3 width) -->
            <section class="lg:col-span-5 xl:col-span-4 bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-5 lg:p-6 border border-slate-200/60 shadow-sm flex flex-col h-[480px] lg:h-[calc(100vh-10rem)]">
                
                <!-- Header Box -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-shrink-0">
                    <div>
                        <h3 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Daftar Pegawai Hadir</span>
                        </h3>
                        <div class="flex items-center gap-2 mt-0.5 sm:mt-1 text-[10px] sm:text-[11px] font-semibold text-slate-500">
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Check In</span>
                            <span>&bull;</span>
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Check Out</span>
                        </div>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-700 bg-slate-100 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full border border-slate-200" x-text="liveCheckIns.length + ' Pegawai Hadir'"></span>
                </div>

                <!-- List Body: 1 Column Sleek List with Auto-Scroll -->
                <div id="attendance-list-container" class="flex-1 overflow-y-auto pr-1 mt-2.5 custom-scrollbar divide-y divide-slate-100">
                    
                    <!-- Loop Pegawai Hadir -->
                    <template x-for="item in liveCheckIns" :key="item.id">
                        <div class="py-2 sm:py-2.5 px-1.5 flex items-center justify-between gap-3 transition hover:bg-slate-50/70 rounded-xl">
                            
                            <!-- Left: Foto Profil & Lingkaran Indikator -->
                            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                
                                <div class="relative flex-shrink-0">
                                    <template x-if="item.foto_profile">
                                        <img 
                                            :src="item.foto_profile" 
                                            :alt="item.nama" 
                                            @error="item.foto_profile = null"
                                            :class="item.has_checkout ? 'ring-2 ring-slate-300 grayscale-[25%] opacity-75' : 'ring-2 ring-emerald-500 shadow-xs'"
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover p-0.5 bg-white transition"
                                        >
                                    </template>
                                    <template x-if="!item.foto_profile">
                                        <div 
                                            :class="item.has_checkout ? 'bg-slate-400 ring-2 ring-slate-300 opacity-75' : 'bg-primary ring-2 ring-emerald-500 shadow-xs'"
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full text-white flex items-center justify-center font-bold text-xs sm:text-sm transition" 
                                            x-text="item.nama.substring(0, 1).toUpperCase()">
                                        </div>
                                    </template>

                                    <!-- Status indicator dot (Green = Sedang Bekerja, Gray = Sudah Pulang) -->
                                    <span class="absolute -bottom-0.5 -right-0.5 flex h-3 w-3">
                                        <template x-if="!item.has_checkout">
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-white"></span>
                                        </template>
                                        <template x-if="item.has_checkout">
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-slate-400 border-2 border-white"></span>
                                        </template>
                                    </span>
                                </div>

                                <!-- Nama & Divisi/Jabatan & Badge Lokasi Cabang -->
                                <div class="min-w-0">
                                    <h4 :class="item.has_checkout ? 'text-slate-600 font-semibold' : 'text-slate-900 font-bold'" class="text-xs sm:text-sm truncate leading-tight" x-text="item.nama"></h4>
                                    <div class="flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-400 truncate mt-0.5">
                                        <span x-text="item.divisi"></span>
                                        <span class="text-slate-300">&bull;</span>
                                        <span x-text="item.jabatan"></span>
                                        <span x-show="cabang === 'all'" class="text-slate-300">&bull;</span>
                                        <span x-show="cabang === 'all'" class="text-[9px] sm:text-[10px] font-bold text-primary" x-text="item.cabang_label"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Label Mode Kerja -->
                            <div class="flex-shrink-0">
                                <span :class="item.has_checkout ? 'bg-slate-100 text-slate-500 border-slate-200' : (item.skema === 'WFO' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200')"
                                    class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md border uppercase" x-text="item.skema">
                                </span>
                            </div>

                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="liveCheckIns.length === 0" class="flex flex-col items-center justify-center h-48 text-center px-4 py-8">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-2.5 text-slate-300">
                            <i class="fa-regular fa-clock text-xl"></i>
                        </div>
                        <h5 class="text-xs sm:text-sm font-bold text-slate-600">Belum ada pegawai hadir</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5" x-text="'Belum ada aktivitas di ' + cabangTitle"></p>
                    </div>

                </div>

            </section>

        </div>

    </main>

    {{-- ===================================================== --}}
    {{-- POPUP MODAL (CHECK IN / CHECK OUT LIVE ALERT) --}}
    {{-- ===================================================== --}}
    <div 
        x-show="showModal" 
        x-transition.opacity.duration.300ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            @click.away="closeModalAndProceed()"
            class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-[0_25px_60px_-12px_rgba(0,0,0,0.3)] max-w-3xl lg:max-w-4xl w-full mx-3 sm:mx-4 p-6 sm:p-8 md:p-10 flex flex-col md:flex-row gap-6 sm:gap-8 lg:gap-10 relative overflow-y-auto max-h-[92vh] border border-slate-100"
        >
            <!-- Close Button -->
            <button @click="closeModalAndProceed()" class="absolute top-4 sm:top-6 right-4 sm:right-6 text-slate-400 hover:text-slate-600 transition-colors p-2 rounded-full hover:bg-slate-50" aria-label="Tutup alert">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Left Side -->
            <div class="flex-1 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 pb-5 md:pb-0 md:pr-6 lg:pr-8">
                
                <!-- Circular Icon -->
                <div :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700' : 'bg-[#EBFDF2] text-[#12B76A]'"
                    class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-full flex items-center justify-center relative shadow-xs">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <i class="fa-solid fa-check text-4xl sm:text-5xl"></i>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <i class="fa-solid fa-door-open text-4xl sm:text-5xl text-slate-600"></i>
                    </template>
                </div>
                
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 text-center mt-5 sm:mt-6" x-text="modalData.tipe === 'checkout' ? 'Check Out Berhasil' : 'Check In Berhasil'">
                </h3>
                <p class="text-slate-500 text-xs sm:text-sm text-center mt-1 font-medium leading-relaxed">
                    <span x-text="modalData.tipe === 'checkout' ? 'Terima kasih atas kerja kerasnya hari ini, ' : 'Selamat datang kembali, '"></span>
                    <span class="text-slate-800 font-extrabold" x-text="modalData.nama"></span>!
                </p>

                <!-- Status Badge with Emoticon -->
                <div class="mt-4 sm:mt-5 flex items-center gap-2 flex-wrap justify-center">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <div>
                            <template x-if="modalData.status_kehadiran === 'Terlambat'">
                                <span class="inline-flex items-center gap-1.5 bg-[#FFF4F2] text-[#B42318] border border-[#FECDCA] font-bold px-4 py-1.5 rounded-full text-xs shadow-xs">
                                    <span>Terlambat</span>
                                    <i class="fa-regular fa-face-frown text-sm"></i>
                                </span>
                            </template>
                            <template x-if="modalData.status_kehadiran !== 'Terlambat'">
                                <span class="inline-flex items-center gap-1.5 bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] font-bold px-4 py-1.5 rounded-full text-xs shadow-xs">
                                    <span>Tepat Waktu</span>
                                    <i class="fa-regular fa-face-smile text-sm"></i>
                                </span>
                            </template>
                        </div>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 border border-slate-200 font-bold px-4 py-1.5 rounded-full text-xs shadow-xs">
                            <i class="fa-solid fa-circle-check text-slate-500"></i>
                            <span>Sudah Pulang</span>
                        </span>
                    </template>
                </div>
            </div>

            <!-- Modal Right Side -->
            <div class="flex-[1.2] flex flex-col justify-center">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <template x-if="modalData.foto_profile">
                        <img 
                            :src="modalData.foto_profile" 
                            :alt="modalData.nama" 
                            @error="modalData.foto_profile = null"
                            class="w-14 h-14 sm:w-16 sm:h-16 lg:w-18 lg:h-18 rounded-full object-cover border-2 border-slate-200 shadow-sm flex-shrink-0"
                        >
                    </template>
                    <template x-if="!modalData.foto_profile">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-18 lg:h-18 rounded-full bg-primary text-white flex items-center justify-center font-black text-xl sm:text-2xl shadow-sm border-2 border-slate-100 flex-shrink-0" x-text="modalData.nama ? modalData.nama.substring(0, 1).toUpperCase() : ''">
                        </div>
                    </template>
                    
                    <div class="min-w-0">
                        <h4 class="text-lg sm:text-xl lg:text-2xl font-black text-slate-900 tracking-tight truncate" x-text="modalData.nama"></h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-[#ECFDF3] text-[#027A48] border-[#A6F4C5]'"
                                class="text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-md border uppercase tracking-wider" x-text="modalData.tipe === 'checkout' ? 'Check Out' : 'Check In'">
                            </span>
                            <span class="text-xs font-medium text-slate-400 truncate" x-text="modalData.skema_label"></span>
                        </div>
                    </div>
                </div>

                <!-- Table details -->
                <div class="mt-4 sm:mt-5 bg-slate-50/70 border border-slate-200/70 rounded-2xl overflow-hidden divide-y divide-slate-100 text-xs sm:text-sm">
                    
                    <div class="flex items-center justify-between p-3 sm:p-3.5">
                        <div class="text-slate-500 font-medium flex items-center gap-2">
                            <i class="fa-regular fa-clock text-slate-400"></i>
                            <span x-text="modalData.tipe === 'checkout' ? 'Waktu Check Out' : 'Waktu Check In'"></span>
                        </div>
                        <div class="text-slate-900 font-bold" x-text="modalData.waktu ? modalData.waktu + ' WIB' : '-'"></div>
                    </div>

                    <template x-if="modalData.tipe === 'checkout'">
                        <div class="flex items-center justify-between p-3 sm:p-3.5">
                            <div class="text-slate-500 font-medium flex items-center gap-2">
                                <i class="fa-solid fa-business-time text-slate-400"></i>
                                Total Durasi Kerja
                            </div>
                            <div class="text-slate-700 font-bold" x-text="modalData.durasi || '-'"></div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between p-3 sm:p-3.5">
                        <div class="text-slate-500 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-400"></i>
                            Lokasi
                        </div>
                        <div class="text-slate-900 font-bold" x-text="modalData.lokasi || '-'"></div>
                    </div>

                    <div class="flex items-center justify-between p-3 sm:p-3.5">
                        <div class="text-slate-500 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-building-user text-slate-400"></i>
                            Divisi
                        </div>
                        <div class="text-slate-900 font-bold" x-text="modalData.divisi || '-'"></div>
                    </div>

                    <div class="flex items-center justify-between p-3 sm:p-3.5">
                        <div class="text-slate-500 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-user-tag text-slate-400"></i>
                            Jabatan
                        </div>
                        <div class="text-slate-900 font-bold" x-text="modalData.jabatan || '-'"></div>
                    </div>

                    <div class="flex items-center justify-between p-3 sm:p-3.5">
                        <div class="text-slate-500 font-medium flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-slate-400"></i>
                            Jam Kerja
                        </div>
                        <div class="text-slate-900 font-bold" x-text="modalData.jam_kerja || '-'"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- SCRIPTS (ALPINE.JS LOGIC & REAL-TIME POLLING) --}}
    {{-- ===================================================== --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tvDashboard', (selectedDate, selectedCabang = 'all') => ({
                date: selectedDate,
                cabang: (selectedCabang || 'all').toString(),
                branches: @json($branches) || [],
                masterCheckIns: @json($liveCheckIns) || [],
                liveCheckIns: @json($liveCheckIns) || [],
                isDemo: selectedDate !== new Date().toISOString().split('T')[0],
                totalPegawai: {{ $totalPegawai }},
                totalHadir: {{ $totalHadir }},
                sedangBekerja: {{ $sedangBekerja }},
                sudahCheckOut: {{ $sudahCheckOut }},
                wfoCount: {{ $wfoCount }},
                wfhCount: {{ $wfhCount }},
                sakitCount: {{ $sakitCount }},
                belumHadir: {{ $belumHadir }},
                clockTime: '00:00:00',
                clockDate: '...',

                // Modal popup queue states
                showModal: false,
                modalData: {},
                modalQueue: [],
                isProcessingQueue: false,
                knownActivityKeys: new Set(),
                modalTimer: null,
                autoScrollTimer: null,

                get cabangTitle() {
                    if (this.cabang === 'all') return 'Semua Cabang';
                    const found = this.branches.find(b => b.lokasi_id.toString() === this.cabang.toString() || b.nama_kantor.toLowerCase() === this.cabang.toLowerCase());
                    return found ? found.nama_kantor : 'Kantor Cabang';
                },

                get cabangShortTitle() {
                    if (this.cabang === 'all') return 'Semua';
                    const found = this.branches.find(b => b.lokasi_id.toString() === this.cabang.toString() || b.nama_kantor.toLowerCase() === this.cabang.toLowerCase());
                    return found ? found.nama_kantor : 'Cabang';
                },

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Initialize known activity keys on initial load
                    if (this.masterCheckIns && this.masterCheckIns.length > 0) {
                        this.masterCheckIns.forEach(item => {
                            this.knownActivityKeys.add(this.getActivityKey(item));
                        });
                    }

                    // Apply initial branch filter if not 'all'
                    this.applyLocalFilter();
                    
                    // Auto-poll stats every 5 seconds
                    setInterval(() => this.fetchStats(), 5000);

                    // Start smooth auto-scroll for TV screen
                    this.initAutoScroll();
                },

                setCabang(newCabang) {
                    this.cabang = (newCabang || 'all').toString();
                    const url = new URL(window.location.href);
                    if (this.cabang === 'all') {
                        url.searchParams.delete('cabang');
                    } else {
                        url.searchParams.set('cabang', this.cabang);
                    }
                    window.history.replaceState({}, '', url.toString());

                    // Instant 0ms local UI update
                    this.applyLocalFilter();
                },

                applyLocalFilter() {
                    const currentCabang = this.cabang.toString().toLowerCase();
                    if (currentCabang === 'all') {
                        this.liveCheckIns = [...this.masterCheckIns];
                    } else {
                        this.liveCheckIns = this.masterCheckIns.filter(item => {
                            const itemCabangId = (item.cabang_id || '').toString().toLowerCase();
                            const itemCabangLabel = (item.cabang_label || '').toString().toLowerCase();
                            
                            // Match by location ID
                            if (itemCabangId === currentCabang) {
                                return true;
                            }
                            
                            // Match by branch name if slug or name is used
                            const targetBranch = this.branches.find(b => b.lokasi_id.toString() === currentCabang);
                            if (targetBranch && itemCabangLabel === targetBranch.nama_kantor.toLowerCase()) {
                                return true;
                            }

                            return itemCabangLabel.includes(currentCabang);
                        });
                    }

                    // Instant summary counts update
                    this.totalHadir = new Set(this.liveCheckIns.map(i => i.pegawai_id)).size;
                    this.sedangBekerja = new Set(this.liveCheckIns.filter(i => !i.has_checkout).map(i => i.pegawai_id)).size;
                    this.sudahCheckOut = new Set(this.liveCheckIns.filter(i => i.has_checkout).map(i => i.pegawai_id)).size;
                    this.wfoCount = new Set(this.liveCheckIns.filter(i => i.skema === 'WFO').map(i => i.pegawai_id)).size;
                    this.wfhCount = new Set(this.liveCheckIns.filter(i => i.skema === 'WFH' || i.skema === 'WFC').map(i => i.pegawai_id)).size;

                    if (this.cabang === 'all') {
                        this.belumHadir = Math.max(0, this.totalPegawai - this.totalHadir - this.sakitCount);
                    } else {
                        this.belumHadir = Math.max(0, this.totalPegawai - this.totalHadir);
                    }
                },

                getActivityKey(item) {
                    return item.id + '_' + (item.has_checkout ? 'out_' + item.jam_checkout : 'in_' + item.jam_checkin);
                },

                initAutoScroll() {
                    const container = document.getElementById('attendance-list-container');
                    if (!container) return;

                    let scrollDirection = 1;
                    let isPaused = false;

                    setInterval(() => {
                        if (isPaused) return;

                        if (container.scrollHeight > container.clientHeight) {
                            if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
                                scrollDirection = -1;
                                isPaused = true;
                                setTimeout(() => { isPaused = false; }, 2000);
                            } else if (container.scrollTop <= 5 && scrollDirection === -1) {
                                scrollDirection = 1;
                                isPaused = true;
                                setTimeout(() => { isPaused = false; }, 2000);
                            }

                            container.scrollBy({
                                top: scrollDirection * 55,
                                behavior: 'smooth'
                            });
                        }
                    }, 2000);
                },

                updateClock() {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const d = new Date();
                    
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    const seconds = String(d.getSeconds()).padStart(2, '0');
                    this.clockTime = `${hours}:${minutes}:${seconds}`;

                    const dayName = days[d.getDay()];
                    const dateNum = d.getDate();
                    const monthName = months[d.getMonth()];
                    const year = d.getFullYear();
                    this.clockDate = `${dayName}, ${dateNum} ${monthName} ${year}`;
                },

                async fetchStats() {
                    try {
                        const response = await fetch(`/api/tv-dashboard/stats?date=${this.date}&cabang=all`);
                        if (response.ok) {
                            const text = await response.text();
                            const cleanJson = text.replace(/^\uFEFF/, '').trim();
                            const data = JSON.parse(cleanJson);

                            this.totalPegawai = data.totalPegawai;
                            this.sakitCount = data.sakitCount;
                            if (data.branches) {
                                this.branches = data.branches;
                            }
                            
                            if (data.liveCheckIns && Array.isArray(data.liveCheckIns)) {
                                const newActivities = [];

                                data.liveCheckIns.forEach(item => {
                                    const key = this.getActivityKey(item);
                                    if (!this.knownActivityKeys.has(key)) {
                                        this.knownActivityKeys.add(key);
                                        newActivities.push(item);
                                    }
                                });

                                if (newActivities.length > 0) {
                                    newActivities.reverse().forEach(item => {
                                        this.modalQueue.push(item);
                                    });
                                    this.processModalQueue();
                                }

                                this.masterCheckIns = data.liveCheckIns;
                                this.applyLocalFilter();
                            }
                        }
                    } catch (error) {
                        console.error('Failed to fetch stats:', error);
                    }
                },

                processModalQueue() {
                    if (this.isProcessingQueue || this.modalQueue.length === 0) {
                        return;
                    }

                    this.isProcessingQueue = true;
                    const nextItem = this.modalQueue.shift();
                    this.modalData = nextItem;
                    this.showModal = true;

                    if (this.modalTimer) {
                        clearTimeout(this.modalTimer);
                    }

                    this.modalTimer = setTimeout(() => {
                        this.closeModalAndProceed();
                    }, 4000);
                },

                closeModalAndProceed() {
                    if (this.modalTimer) {
                        clearTimeout(this.modalTimer);
                        this.modalTimer = null;
                    }

                    this.showModal = false;

                    setTimeout(() => {
                        this.isProcessingQueue = false;
                        if (this.modalQueue.length > 0) {
                            this.processModalQueue();
                        }
                    }, 350);
                }
            }));
        });
    </script>
</body>
</html>