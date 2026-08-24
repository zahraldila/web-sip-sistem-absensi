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
    </style>
</head>
<body class="bg-[#F4F6F9] min-h-screen text-slate-800 flex flex-col antialiased" x-data="tvDashboard('{{ $selectedDate }}')">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200">
        <div class="max-w-[1800px] mx-auto px-6 sm:px-8 py-4 flex items-center justify-between">
            
            <!-- Left Info -->
            <div class="flex items-center gap-4">
                <div class="w-13 h-13 sm:w-14 sm:h-14 bg-white border border-slate-200 shadow-sm rounded-2xl flex items-center justify-center p-1.5 overflow-hidden">
                    <img 
                        src="{{ company_logo_url() }}" 
                        alt="Logo SIP" 
                        class="h-full w-full object-contain"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=SIP';"
                    >
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        PT Selada Indonesia Produktif
                    </h1>
                    <p class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                        Live Attendance Dashboard
                    </p>
                </div>
            </div>

            <!-- Right Info & Clock -->
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <div class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight" x-text="clockTime">
                        00:00:00
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-400 mt-0.5" x-text="clockDate">
                        ...
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- ===================================================== --}}
    {{-- MAIN CONTENT AREA --}}
    {{-- ===================================================== --}}
    <main class="max-w-[1800px] mx-auto w-full p-6 sm:p-8 flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Left Side: Today's Summary (2/3 width) -->
        <section class="lg:col-span-2 flex flex-col gap-6">
            
            <!-- Summary Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                        Ringkasan Kehadiran Hari Ini
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Status kehadiran pegawai secara real-time <span x-show="isDemo" class="ml-2 px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Mode Demo ({{ $selectedDate }})</span>
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-3 text-xs font-semibold text-slate-600 bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Check In (Aktif)</span>
                    <span class="h-3 w-px bg-slate-200"></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Check Out (Pulang)</span>
                    <span class="h-3 w-px bg-slate-200"></span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Belum Hadir</span>
                </div>
            </div>

            <!-- Big Cards (Row 1) - Status Check In vs Belum Hadir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                
                <!-- Total Hadir Card with Breakdown -->
                <div class="bg-white rounded-[2rem] p-6 sm:p-7 border border-slate-200/70 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-[#E6F4EA] w-16 h-16 sm:w-18 sm:h-18 rounded-2xl flex items-center justify-center relative flex-shrink-0">
                                <i class="fa-solid fa-user-check text-2xl sm:text-3xl text-emerald-600"></i>
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-[#12B76A]"></span>
                                </span>
                            </div>
                            <div>
                                <div class="text-4xl sm:text-5xl font-black text-[#12B76A] tracking-tight" x-text="totalHadir">
                                    {{ $totalHadir }}
                                </div>
                                <div class="text-slate-500 text-sm sm:text-base font-bold mt-0.5">
                                    Total Pegawai Hadir
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sub Breakdown: Sedang Bekerja vs Sudah Pulang -->
                    <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                        <div class="bg-emerald-50/70 rounded-xl p-3 border border-emerald-100">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Sedang Bekerja
                                </span>
                            </div>
                            <div class="text-2xl font-black text-emerald-700 mt-1" x-text="sedangBekerja">
                                {{ $sedangBekerja }}
                            </div>
                        </div>

                        <div class="bg-slate-100/70 rounded-xl p-3 border border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    Sudah Check Out
                                </span>
                            </div>
                            <div class="text-2xl font-black text-slate-700 mt-1" x-text="sudahCheckOut">
                                {{ $sudahCheckOut }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Belum Hadir Card -->
                <div class="bg-white rounded-[2rem] p-6 sm:p-7 border border-slate-200/70 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="bg-[#FCE8E6] w-16 h-16 sm:w-18 sm:h-18 rounded-2xl flex items-center justify-center relative flex-shrink-0">
                            <i class="fa-solid fa-user-xmark text-2xl sm:text-3xl text-rose-600"></i>
                        </div>
                        <div>
                            <div class="text-4xl sm:text-5xl font-black text-[#F04438] tracking-tight" x-text="belumHadir">
                                {{ $belumHadir }}
                            </div>
                            <div class="text-slate-500 text-sm sm:text-base font-bold mt-0.5">
                                Belum Hadir / Belum Check In
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <div class="bg-rose-50/50 rounded-xl p-3 border border-rose-100 flex items-center justify-between">
                            <span class="text-xs font-semibold text-rose-700">Total Karyawan Terdaftar</span>
                            <span class="text-sm font-bold text-slate-800" x-text="totalPegawai + ' Orang'">{{ $totalPegawai }} Orang</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Smaller Cards (Row 2) - Skema Kerja & Izin/Sakit -->
            <div class="grid grid-cols-3 gap-4 sm:gap-6">
                
                <!-- WFO Card -->
                <div class="bg-white rounded-[2rem] p-5 sm:p-6 border border-slate-200/60 shadow-sm flex items-center gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#E8F0FE] w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-building text-xl text-blue-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#175CD3]" x-text="wfoCount">
                            {{ $wfoCount }}
                        </div>
                        <div class="text-slate-500 text-xs sm:text-sm font-bold mt-0.5 truncate">
                            WFO (Kantor)
                        </div>
                    </div>
                </div>

                <!-- WFH Card -->
                <div class="bg-white rounded-[2rem] p-5 sm:p-6 border border-slate-200/60 shadow-sm flex items-center gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#F3E8FF] w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-house-laptop text-xl text-violet-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#7A5AF8]" x-text="wfhCount">
                            {{ $wfhCount }}
                        </div>
                        <div class="text-slate-500 text-xs sm:text-sm font-bold mt-0.5 truncate">
                            WFH / WFC
                        </div>
                    </div>
                </div>

                <!-- Sakit Card -->
                <div class="bg-white rounded-[2rem] p-5 sm:p-6 border border-slate-200/60 shadow-sm flex items-center gap-4 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#FEF7E0] w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-notes-medical text-xl text-amber-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#B54708]" x-text="sakitCount">
                            {{ $sakitCount }}
                        </div>
                        <div class="text-slate-500 text-xs sm:text-sm font-bold mt-0.5 truncate">
                            Sakit / Izin
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- Right Side: Daftar Pegawai Hadir (1/3 width - 1 Kolom Bersih + Auto-Scroll Cerdas) -->
        <section class="bg-white rounded-[2rem] p-5 sm:p-6 border border-slate-200/60 shadow-sm flex flex-col h-[calc(100vh-10rem)]">
            
            <!-- Panel Header -->
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Daftar Pegawai Hadir</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-1 text-[11px] font-semibold text-slate-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Check In</span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-400"></span> Check Out</span>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200" x-text="liveCheckIns.length + ' Pegawai'"></span>
            </div>

            <!-- List Body: 1 Column Sleek List with Auto-Scroll -->
            <div id="attendance-list-container" class="flex-1 overflow-y-auto pr-1 mt-2.5 custom-scrollbar divide-y divide-slate-100">
                
                <!-- Loop Pegawai Hadir -->
                <template x-for="item in liveCheckIns" :key="item.id">
                    <div class="py-2.5 px-1.5 flex items-center justify-between gap-3 transition hover:bg-slate-50/70 rounded-xl">
                        
                        <!-- Left: Foto Profil & Lingkaran Indikator -->
                        <div class="flex items-center gap-3 min-w-0">
                            
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

                            <!-- Nama & Divisi/Jabatan -->
                            <div class="min-w-0">
                                <h4 :class="item.has_checkout ? 'text-slate-600 font-semibold' : 'text-slate-900 font-bold'" class="text-xs sm:text-sm truncate" x-text="item.nama"></h4>
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 truncate mt-0.5">
                                    <span x-text="item.divisi"></span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span x-text="item.jabatan"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Label Mode Kerja -->
                        <div class="flex-shrink-0">
                            <span :class="item.has_checkout ? 'bg-slate-100 text-slate-500 border-slate-200' : (item.skema === 'WFO' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200')"
                                class="text-[10px] font-bold px-2 py-0.5 rounded-md border uppercase" x-text="item.skema">
                            </span>
                        </div>

                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="liveCheckIns.length === 0" class="flex flex-col items-center justify-center h-48 text-center px-4 py-8">
                    <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-2.5 text-slate-300">
                        <i class="fa-regular fa-clock text-xl"></i>
                    </div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-600">Belum ada pegawai hadir</h5>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pegawai yang sudah check-in akan otomatis tampil di sini.</p>
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
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
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
            @click.away="showModal = false"
            class="bg-white rounded-[2.5rem] shadow-[0_25px_60px_-12px_rgba(0,0,0,0.3)] max-w-4xl w-full mx-4 p-8 sm:p-10 flex flex-col md:flex-row gap-8 sm:gap-10 relative overflow-hidden border border-slate-100"
        >
            <!-- Close Button -->
            <button @click="showModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors p-2 rounded-full hover:bg-slate-50" aria-label="Tutup alert">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Left Side -->
            <div class="flex-1 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-8">
                
                <div :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-emerald-50 text-emerald-600 border-emerald-100'"
                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl flex items-center justify-center relative shadow-sm border">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <i class="fa-solid fa-check text-5xl animate-bounce"></i>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <i class="fa-solid fa-door-open text-5xl animate-pulse"></i>
                    </template>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 text-center mt-5" x-text="modalData.tipe === 'checkout' ? 'Check Out Berhasil' : 'Check In Berhasil'">
                </h3>
                <p class="text-slate-500 text-xs sm:text-sm text-center mt-1 font-medium leading-relaxed">
                    <span x-text="modalData.tipe === 'checkout' ? 'Terima kasih atas kerja kerasnya hari ini, ' : 'Selamat datang kembali, '"></span>
                    <span class="text-slate-800 font-extrabold" x-text="modalData.nama"></span>!
                </p>

                <!-- Status Badge -->
                <div class="mt-5">
                    <template x-if="modalData.tipe !== 'checkout'">
                        <span :class="modalData.status_kehadiran === 'Terlambat' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                            class="inline-flex items-center gap-2 font-extrabold px-5 py-2.5 rounded-full text-sm border shadow-xs" x-text="modalData.status_kehadiran">
                        </span>
                    </template>
                    <template x-if="modalData.tipe === 'checkout'">
                        <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 border border-slate-200 font-extrabold px-5 py-2.5 rounded-full text-sm shadow-xs">
                            <i class="fa-solid fa-circle-check text-slate-500"></i>
                            <span>Sudah Pulang</span>
                        </span>
                    </template>
                </div>
            </div>

            <!-- Modal Right Side -->
            <div class="flex-[1.2] flex flex-col justify-center">
                <div class="flex items-center gap-4">
                    <template x-if="modalData.foto_profile">
                        <img 
                            :src="modalData.foto_profile" 
                            :alt="modalData.nama" 
                            @@error="modalData.foto_profile = null"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-slate-200 shadow-md flex-shrink-0"
                        >
                    </template>
                    <template x-if="!modalData.foto_profile">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary text-white flex items-center justify-center font-black text-2xl shadow-md border-2 border-slate-100 flex-shrink-0" x-text="modalData.nama ? modalData.nama.substring(0, 1).toUpperCase() : ''">
                        </div>
                    </template>
                    
                    <div class="min-w-0">
                        <h4 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight truncate" x-text="modalData.nama"></h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span :class="modalData.tipe === 'checkout' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                                class="text-[11px] font-black px-2.5 py-0.5 rounded-full border uppercase tracking-wider" x-text="modalData.tipe === 'checkout' ? 'Check Out' : 'Check In'">
                            </span>
                            <span class="text-xs font-semibold text-slate-500" x-text="modalData.skema_label"></span>
                        </div>
                    </div>
                </div>

                <!-- Table details -->
                <div class="mt-5 bg-slate-50/70 border border-slate-200/70 rounded-2xl overflow-hidden divide-y divide-slate-100 text-xs sm:text-sm">
                    
                    <div class="flex items-center justify-between p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-regular fa-clock text-slate-400"></i>
                            <span x-text="modalData.tipe === 'checkout' ? 'Waktu Check Out' : 'Waktu Check In'"></span>
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.waktu ? modalData.waktu + ' WIB' : '-'"></div>
                    </div>

                    <template x-if="modalData.tipe === 'checkout'">
                        <div class="flex items-center justify-between p-3.5">
                            <div class="text-slate-500 font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-business-time text-slate-400"></i>
                                Total Durasi Kerja
                            </div>
                            <div class="text-slate-700 font-extrabold" x-text="modalData.durasi || '-'"></div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-400"></i>
                            Lokasi
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.lokasi"></div>
                    </div>

                    <div class="flex items-center justify-between p-3.5">
                        <div class="text-slate-500 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-slate-400"></i>
                            Divisi / Jabatan
                        </div>
                        <div class="text-slate-900 font-extrabold" x-text="modalData.divisi + ' &bull; ' + modalData.jabatan"></div>
                    </div>

                    <div class="flex items-center justify-between p-3.5">
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
            Alpine.data('tvDashboard', (selectedDate) => ({
                date: selectedDate,
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

                // Modal popup states
                showModal: false,
                modalData: {},
                lastActivityKey: null,
                modalTimer: null,
                autoScrollTimer: null,

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Initialize last activity key on load
                    if (this.liveCheckIns && this.liveCheckIns.length > 0) {
                        const first = this.liveCheckIns[0];
                        this.lastActivityKey = first.id + '_' + (first.has_checkout ? 'out_' + first.jam_checkout : 'in_' + first.jam_checkin);
                    }
                    
                    // Auto-poll stats every 5 seconds
                    setInterval(() => this.fetchStats(), 5000);

                    // Start smooth auto-scroll for TV screen
                    this.initAutoScroll();
                },

                initAutoScroll() {
                    const container = document.getElementById('attendance-list-container');
                    if (!container) return;

                    let scrollDirection = 1; // 1 = down, -1 = up
                    let isPaused = false;

                    setInterval(() => {
                        if (isPaused) return;

                        if (container.scrollHeight > container.clientHeight) {
                            if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
                                scrollDirection = -1;
                                isPaused = true;
                                setTimeout(() => { isPaused = false; }, 2000); // Pause 2 detik di ujung bawah
                            } else if (container.scrollTop <= 5 && scrollDirection === -1) {
                                scrollDirection = 1;
                                isPaused = true;
                                setTimeout(() => { isPaused = false; }, 2000); // Pause 2 detik di ujung atas
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
                        const response = await fetch(`/api/tv-dashboard/stats?date=${this.date}`);
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
                            
                            // Check if a new check-in or check-out has occurred
                            if (data.liveCheckIns && data.liveCheckIns.length > 0) {
                                const latest = data.liveCheckIns[0];
                                const currentKey = latest.id + '_' + (latest.has_checkout ? 'out_' + latest.jam_checkout : 'in_' + latest.jam_checkin);
                                
                                if (this.lastActivityKey === null) {
                                    this.lastActivityKey = currentKey;
                                } else if (currentKey !== this.lastActivityKey) {
                                    this.lastActivityKey = currentKey;
                                    this.triggerModal(latest);
                                }

                                this.liveCheckIns = data.liveCheckIns;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to fetch stats:', error);
                    }
                },

                triggerModal(item) {
                    this.modalData = item;
                    this.showModal = true;

                    if (this.modalTimer) {
                        clearTimeout(this.modalTimer);
                    }

                    this.modalTimer = setTimeout(() => {
                        this.showModal = false;
                    }, 6000);
                }
            }));
        });
    </script>
</body>
</html>