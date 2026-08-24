<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Selada Indonesia Produktif - Live Attendance Dashboard</title>

    <!-- FontAwesome 6 CDN for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
        }
        /* Custom scrollbar style */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #CBD5E1;
            border-radius: 10px;
        }
        /* Hide scrollbar for tab container */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-[#F4F6F9] min-h-screen text-slate-800 flex flex-col antialiased" x-data="tvDashboard('{{ $selectedDate }}', '{{ $selectedCabang }}')">

    {{-- ===================================================== --}}
    {{-- HEADER WITH DYNAMIC MULTI-BRANCH TAB SWITCHER --}}
    {{-- ===================================================== --}}
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
            
            <!-- Top / Left Info: Logo & Company -->
            <div class="flex items-center justify-between md:justify-start gap-3 sm:gap-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-13 lg:h-13 bg-white border border-slate-200 shadow-sm rounded-xl sm:rounded-2xl flex items-center justify-center p-1 overflow-hidden flex-shrink-0">
                        <img 
                            src="{{ company_logo_url() }}" 
                            alt="Logo SIP" 
                            class="h-full w-full object-contain"
                            onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=SIP';"
                        >
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
                <div class="text-right">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight" x-text="clockTime">
                        00:00:00
                    </div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5" x-text="clockDate">
                        ...
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- ===================================================== --}}
    {{-- MAIN CONTENT AREA (FULLY RESPONSIVE) --}}
    {{-- ===================================================== --}}
    <main class="max-w-[1800px] mx-auto w-full p-4 sm:p-6 lg:p-8 flex-1 grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 lg:gap-8">
        
        <!-- Left Side: Today's Summary (7 of 12 cols on desktop) -->
        <section class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4 sm:gap-6">
            
            <!-- Summary Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2 flex-wrap">
                        <span>Ringkasan Kehadiran</span>
                        <span class="text-xs sm:text-sm font-extrabold text-primary bg-primary/10 px-2.5 sm:px-3 py-0.5 rounded-full border border-primary/20" x-text="cabangTitle"></span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Status kehadiran pegawai secara real-time <span x-show="isDemo" class="ml-1.5 px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] sm:text-xs font-bold rounded-full">Mode Demo ({{ $selectedDate }})</span>
                    </p>
                </div>
                <div class="hidden md:flex items-center gap-2.5 sm:gap-3 text-[11px] sm:text-xs font-semibold text-slate-600 bg-white px-3 sm:px-4 py-2 rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm flex-shrink-0">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Check In</span>
                    <span class="h-3 w-px bg-slate-200"></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Check Out</span>
                    <span class="h-3 w-px bg-slate-200"></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Belum Hadir</span>
                </div>
            </div>

            <!-- Big Cards (Row 1) - Status Check In vs Belum Hadir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-5 lg:gap-6">
                
                <!-- Total Hadir Card with Breakdown -->
                <div class="bg-white rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 lg:p-7 border border-slate-200/70 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3.5 sm:gap-4">
                            <div class="bg-[#E6F4EA] w-14 h-14 sm:w-16 sm:h-16 lg:w-18 lg:h-18 rounded-xl sm:rounded-2xl flex items-center justify-center relative flex-shrink-0">
                                <i class="fa-solid fa-user-check text-2xl sm:text-3xl text-emerald-600"></i>
                                <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 sm:h-4 sm:w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 sm:h-4 sm:w-4 bg-[#12B76A]"></span>
                                </span>
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
                    
                    <!-- Sub Breakdown: Sedang Bekerja vs Sudah Pulang -->
                    <div class="mt-4 sm:mt-5 pt-3.5 sm:pt-4 border-t border-slate-100 grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div class="bg-emerald-50/70 rounded-xl p-2.5 sm:p-3 border border-emerald-100">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] sm:text-[11px] font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Sedang Bekerja
                                </span>
                            </div>
                            <div class="text-xl sm:text-2xl font-black text-emerald-700 mt-0.5 sm:mt-1" x-text="sedangBekerja">
                                {{ $sedangBekerja }}
                            </div>
                        </div>

                        <div class="bg-slate-100/70 rounded-xl p-2.5 sm:p-3 border border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    Sudah Check Out
                                </span>
                            </div>
                            <div class="text-xl sm:text-2xl font-black text-slate-700 mt-0.5 sm:mt-1" x-text="sudahCheckOut">
                                {{ $sudahCheckOut }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Belum Hadir Card -->
                <div class="bg-white rounded-2xl sm:rounded-[2rem] p-5 sm:p-6 lg:p-7 border border-slate-200/70 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center gap-3.5 sm:gap-4">
                        <div class="bg-[#FCE8E6] w-14 h-14 sm:w-16 sm:h-16 lg:w-18 lg:h-18 rounded-xl sm:rounded-2xl flex items-center justify-center relative flex-shrink-0">
                            <i class="fa-solid fa-user-xmark text-2xl sm:text-3xl text-rose-600"></i>
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

                    <div class="mt-4 sm:mt-5 pt-3.5 sm:pt-4 border-t border-slate-100">
                        <div class="bg-rose-50/50 rounded-xl p-2.5 sm:p-3 border border-rose-100 flex items-center justify-between">
                            <span class="text-[11px] sm:text-xs font-semibold text-rose-700">Total Karyawan Terdaftar</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-800" x-text="totalPegawai + ' Orang'">{{ $totalPegawai }} Orang</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Smaller Cards (Row 2) - Skema Kerja & Izin/Sakit -->
            <div class="grid grid-cols-3 gap-2.5 sm:gap-4 lg:gap-6">
                
                <!-- WFO Card -->
                <div class="bg-white rounded-xl sm:rounded-[2rem] p-3.5 sm:p-5 lg:p-6 border border-slate-200/60 shadow-sm flex items-center gap-2.5 sm:gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#E8F0FE] w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-building text-base sm:text-xl text-blue-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xl sm:text-2xl lg:text-4xl font-extrabold text-[#175CD3] leading-none" x-text="wfoCount">
                            {{ $wfoCount }}
                        </div>
                        <div class="text-slate-500 text-[10px] sm:text-xs lg:text-sm font-bold mt-1 truncate">
                            WFO Lokasi
                        </div>
                    </div>
                </div>

                <!-- WFH Card -->
                <div class="bg-white rounded-xl sm:rounded-[2rem] p-3.5 sm:p-5 lg:p-6 border border-slate-200/60 shadow-sm flex items-center gap-2.5 sm:gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#F3E8FF] w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-house-laptop text-base sm:text-xl text-violet-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xl sm:text-2xl lg:text-4xl font-extrabold text-[#7A5AF8] leading-none" x-text="wfhCount">
                            {{ $wfhCount }}
                        </div>
                        <div class="text-slate-500 text-[10px] sm:text-xs lg:text-sm font-bold mt-1 truncate">
                            WFH / WFC
                        </div>
                    </div>
                </div>

                <!-- Sakit Card -->
                <div class="bg-white rounded-xl sm:rounded-[2rem] p-3.5 sm:p-5 lg:p-6 border border-slate-200/60 shadow-sm flex items-center gap-2.5 sm:gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#FEF7E0] w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-notes-medical text-base sm:text-xl text-amber-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xl sm:text-2xl lg:text-4xl font-extrabold text-[#B54708] leading-none" x-text="sakitCount">
                            {{ $sakitCount }}
                        </div>
                        <div class="text-slate-500 text-[10px] sm:text-xs lg:text-sm font-bold mt-1 truncate">
                            Sakit / Izin
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- Right Side: Daftar Pegawai Hadir (5 of 12 cols / 1/3 width) -->
        <section class="lg:col-span-5 xl:col-span-4 bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-5 lg:p-6 border border-slate-200/60 shadow-sm flex flex-col h-[480px] lg:h-[calc(100vh-10rem)]">
            
            <!-- Panel Header -->
            <div class="flex items-center justify-between pb-3 sm:pb-3.5 border-b border-slate-100">
                <div>
                    <h3 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Daftar Pegawai Hadir</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-0.5 sm:mt-1 text-[10px] sm:text-[11px] font-semibold text-slate-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Check In</span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-400"></span> Check Out</span>
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
                                        @@error="item.foto_profile = null"
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

                                <!-- Dot Status -->
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
            class="bg-white rounded-2xl sm:rounded-[2.5rem] shadow-[0_25px_60px_-12px_rgba(0,0,0,0.3)] max-w-3xl lg:max-w-4xl w-full mx-3 sm:mx-4 p-6 sm:p-8 md:p-10 flex flex-col md:flex-row gap-6 sm:gap-8 lg:gap-10 relative overflow-y-auto max-h-[92vh] border border-slate-100"
        >
            <!-- Close Button -->
            <button @click="closeModalAndProceed()" class="absolute top-4 sm:top-6 right-4 sm:right-6 text-slate-400 hover:text-slate-600 transition-colors p-2 rounded-full hover:bg-slate-50" aria-label="Tutup alert">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Left Side -->
            <div class="flex-1 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 pb-5 md:pb-0 md:pr-6 lg:pr-8">
                
                <div :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-emerald-50 text-emerald-600 border-emerald-100'"
                    class="w-22 h-22 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-2xl sm:rounded-3xl flex items-center justify-center relative shadow-sm border">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <i class="fa-solid fa-check text-4xl sm:text-5xl animate-bounce"></i>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <i class="fa-solid fa-door-open text-4xl sm:text-5xl animate-pulse"></i>
                    </template>
                </div>
                
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 text-center mt-4 sm:mt-5" x-text="modalData.tipe === 'checkout' ? 'Check Out Berhasil' : 'Check In Berhasil'">
                </h3>
                <p class="text-slate-500 text-xs sm:text-sm text-center mt-1 font-medium leading-relaxed">
                    <span x-text="modalData.tipe === 'checkout' ? 'Terima kasih atas kerja kerasnya hari ini, ' : 'Selamat datang kembali, '"></span>
                    <span class="text-slate-800 font-extrabold" x-text="modalData.nama"></span>!
                </p>

                <!-- Status Badge -->
                <div class="mt-4 sm:mt-5 flex items-center gap-2 flex-wrap justify-center">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <span :class="modalData.status_kehadiran === 'Terlambat' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                            class="inline-flex items-center gap-1.5 font-extrabold px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm border shadow-xs" x-text="modalData.status_kehadiran">
                        </span>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 border border-slate-200 font-extrabold px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm shadow-xs">
                            <i class="fa-solid fa-circle-check text-slate-500"></i>
                            <span>Sudah Pulang</span>
                        </span>
                    </template>
                    <span class="bg-primary/10 text-primary border border-primary/20 font-bold px-3 py-1.5 sm:py-2 rounded-full text-xs" x-text="modalData.cabang_label"></span>
                </div>
            </div>

            <!-- Modal Right Side -->
            <div class="flex-[1.2] flex flex-col justify-center">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <template x-if="modalData.foto_profile">
                        <img 
                            :src="modalData.foto_profile" 
                            :alt="modalData.nama" 
                            @@error="modalData.foto_profile = null"
                            class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full object-cover border-2 border-slate-200 shadow-md flex-shrink-0"
                        >
                    </template>
                    <template x-if="!modalData.foto_profile">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full bg-primary text-white flex items-center justify-center font-black text-xl sm:text-2xl shadow-md border-2 border-slate-100 flex-shrink-0" x-text="modalData.nama ? modalData.nama.substring(0, 1).toUpperCase() : ''">
                        </div>
                    </template>
                    
                    <div class="min-w-0">
                        <h4 class="text-lg sm:text-xl lg:text-2xl font-black text-slate-900 tracking-tight truncate" x-text="modalData.nama"></h4>
                        <div class="flex items-center gap-2 mt-0.5 sm:mt-1">
                            <span :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                                class="text-[10px] sm:text-[11px] font-black px-2 sm:px-2.5 py-0.5 rounded-full border uppercase tracking-wider" x-text="modalData.tipe === 'checkout' ? 'Check Out' : 'Check In'">
                            </span>
                            <span class="text-xs font-semibold text-slate-500 truncate" x-text="modalData.skema_label"></span>
                        </div>
                    </div>
                </div>

                <!-- Table details -->
                <div class="mt-4 sm:mt-5 bg-slate-50/70 border border-slate-200/70 rounded-xl sm:rounded-2xl overflow-hidden divide-y divide-slate-100 text-xs sm:text-sm">
                    
                    <div class="flex items-center justify-between p-2.5 sm:p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-regular fa-clock text-slate-400"></i>
                            <span x-text="modalData.tipe === 'checkout' ? 'Waktu Check Out' : 'Waktu Check In'"></span>
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.waktu ? modalData.waktu + ' WIB' : '-'"></div>
                    </div>

                    <template x-if="modalData.tipe === 'checkout'">
                        <div class="flex items-center justify-between p-2.5 sm:p-3.5">
                            <div class="text-slate-500 font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-business-time text-slate-400"></i>
                                Total Durasi Kerja
                            </div>
                            <div class="text-slate-700 font-extrabold" x-text="modalData.durasi || '-'"></div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between p-2.5 sm:p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-400"></i>
                            Lokasi / Cabang
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.cabang_label || modalData.lokasi"></div>
                    </div>

                    <div class="flex items-center justify-between p-2.5 sm:p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-slate-400"></i>
                            Divisi / Jabatan
                        </div>
                        <div class="text-slate-900 font-extrabold truncate ml-2" x-text="modalData.divisi + ' &bull; ' + modalData.jabatan"></div>
                    </div>

                    <div class="flex items-center justify-between p-2.5 sm:p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-slate-400"></i>
                            Jadwal Jam Kerja
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.jam_kerja"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Polling & Clock Logic using Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tvDashboard', (selectedDate, selectedCabang = 'all') => ({
                date: selectedDate,
                cabang: selectedCabang || 'all',
                branches: @json($branches) || [],
                isDemo: selectedDate !== new Date().toISOString().split('T')[0],
                totalPegawai: {{ $totalPegawai }},
                totalHadir: {{ $totalHadir }},
                sedangBekerja: {{ $sedangBekerja }},
                sudahCheckOut: {{ $sudahCheckOut }},
                wfoCount: {{ $wfoCount }},
                wfhCount: {{ $wfhCount }},
                sakitCount: {{ $sakitCount }},
                belumHadir: {{ $belumHadir }},
                liveCheckIns: @json($liveCheckIns),
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
                    if (this.liveCheckIns && this.liveCheckIns.length > 0) {
                        this.liveCheckIns.forEach(item => {
                            this.knownActivityKeys.add(this.getActivityKey(item));
                        });
                    }
                    
                    // Auto-poll stats every 5 seconds
                    setInterval(() => this.fetchStats(), 5000);

                    // Start smooth auto-scroll for TV screen
                    this.initAutoScroll();
                },

                setCabang(newCabang) {
                    this.cabang = newCabang;
                    const url = new URL(window.location.href);
                    if (newCabang === 'all') {
                        url.searchParams.delete('cabang');
                    } else {
                        url.searchParams.set('cabang', newCabang);
                    }
                    window.history.replaceState({}, '', url.toString());

                    this.fetchStats();
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
                        const response = await fetch(`/api/tv-dashboard/stats?date=${this.date}&cabang=${this.cabang}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.totalPegawai = data.totalPegawai;
                            this.totalHadir = data.totalHadir;
                            this.sedangBekerja = data.sedangBekerja;
                            this.sudahCheckOut = data.sudahCheckOut;
                            this.wfoCount = data.wfoCount;
                            this.wfhCount = data.wfhCount;
                            this.sakitCount = data.sakitCount;
                            this.belumHadir = data.belumHadir;
                            if (data.branches) {
                                this.branches = data.branches;
                            }
                            
                            if (data.liveCheckIns && data.liveCheckIns.length > 0) {
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

                                this.liveCheckIns = data.liveCheckIns;
                            } else {
                                this.liveCheckIns = [];
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
