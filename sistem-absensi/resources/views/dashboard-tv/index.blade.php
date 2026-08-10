<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Selada Indonesia Produktif - Live Attendance Dashboard</title>

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
            width: 6px;
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
    <header class="bg-white sticky top-0 z-50">
        <div class="max-w-[1800px] mx-auto px-8 py-5 flex items-center justify-between">
            
            <!-- Left Info -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white border border-slate-200 shadow-sm rounded-2xl flex items-center justify-center p-1.5 overflow-hidden">
                    <img 
                        src="{{ asset('images/logo-sip.png') }}" 
                        alt="Logo SIP" 
                        class="h-full w-full object-contain"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=SIP';"
                    >
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                        PT Selada Indonesia Produktif
                    </h1>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                        Live Attendance Dashboard
                    </p>
                </div>
            </div>

            <!-- Right Info & Clock -->
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <div class="text-4xl font-extrabold text-slate-900 tracking-tight" x-text="clockTime">
                        00:00:00
                    </div>
                    <div class="text-sm font-semibold text-slate-400 mt-0.5" x-text="clockDate">
                        ...
                    </div>
                </div>
            </div>

        </div>
        <!-- horizontal border line below header extending full width -->
        <hr class="border-slate-200" />
    </header>

    {{-- ===================================================== --}}
    {{-- MAIN CONTENT AREA --}}
    {{-- ===================================================== --}}
    <main class="max-w-[1800px] mx-auto w-full p-8 flex-1 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Today's Summary (2/3 width) -->
        <section class="lg:col-span-2 flex flex-col gap-6">
            
            <!-- Summary Header -->
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Today's Summary
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Ringkasan kehadiran hari ini secara real time <span x-show="isDemo" class="ml-2 px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Mode Demo ({{ $selectedDate }})</span>
                </p>
            </div>

            <!-- Big Cards (Row 1) - Forced 2 columns on desktop via grid-cols-2 -->
            <div class="grid grid-cols-2 gap-6 mt-2">
                
                <!-- Total Hadir Card -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-200/60 shadow-sm flex items-center transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center gap-6">
                        <div class="bg-[#E6F4EA] w-20 h-20 rounded-full flex items-center justify-center relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="absolute bottom-4 right-4 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-[#12B76A] items-center justify-center text-[10px] text-white font-extrabold">✓</span>
                            </span>
                        </div>
                        <div>
                            <div class="text-6xl font-black text-[#12B76A] tracking-tight" x-text="totalHadir">
                                {{ $totalHadir }}
                            </div>
                            <div class="text-slate-500 text-lg font-bold mt-1">
                                Total Hadir
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Belum Hadir Card -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-200/60 shadow-sm flex items-center transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center gap-6">
                        <div class="bg-[#FCE8E6] w-20 h-20 rounded-full flex items-center justify-center relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="absolute bottom-4 right-4 flex h-4 w-4">
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-[#F04438] items-center justify-center text-[10px] text-white font-bold">✕</span>
                            </span>
                        </div>
                        <div>
                            <div class="text-6xl font-black text-[#F04438] tracking-tight" x-text="belumHadir">
                                {{ $belumHadir }}
                            </div>
                            <div class="text-slate-500 text-lg font-bold mt-1">
                                Belum Hadir
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Smaller Cards (Row 2) - Forced 3 columns on desktop via grid-cols-3 -->
            <div class="grid grid-cols-3 gap-6">
                
                <!-- WFO Card -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm flex items-center gap-5 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#E8F0FE] w-16 h-16 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-[#175CD3]" x-text="wfoCount">
                            {{ $wfoCount }}
                        </div>
                        <div class="text-slate-500 text-sm font-bold mt-0.5">
                            WFO
                        </div>
                    </div>
                </div>

                <!-- WFH Card -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm flex items-center gap-5 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#F3E8FF] w-16 h-16 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-[#7A5AF8]" x-text="wfhCount">
                            {{ $wfhCount }}
                        </div>
                        <div class="text-slate-500 text-sm font-bold mt-0.5">
                            WFH
                        </div>
                    </div>
                </div>

                <!-- Sakit Card -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm flex items-center gap-5 transition-all duration-300 hover:shadow-md">
                    <div class="bg-[#FEF7E0] w-16 h-16 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4M12 9v4m-2-2h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-[#B54708]" x-text="sakitCount">
                            {{ $sakitCount }}
                        </div>
                        <div class="text-slate-500 text-sm font-bold mt-0.5">
                            Sakit
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- Right Side: Live Check In (1/3 width) -->
        <section class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm flex flex-col h-[calc(100vh-11rem)]">
            
            <!-- Panel Header -->
            <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="text-[#12B76A] font-bold flex items-center gap-1.5 text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM9 9a1 1 0 000 2h2a1 1 0 000-2H9z" clip-rule="evenodd"/>
                        </svg>
                        Live Check In
                    </span>
                </div>
            </div>

            <!-- List Body -->
            <div class="flex-1 overflow-y-auto pr-1 mt-4 custom-scrollbar">
                
                <!-- Active check ins looping -->
                <template x-for="checkIn in liveCheckIns" :key="checkIn.id">
                    <div class="flex items-center justify-between py-4 border-b border-slate-100 last:border-b-0">
                        
                        <!-- Left Info (includes time, avatar, name & details) -->
                        <div class="flex items-center gap-4">
                            <!-- Time on left of avatar -->
                            <span class="text-sm font-bold text-slate-400 font-mono w-16" x-text="checkIn.waktu"></span>
                            
                            <!-- Avatar -->
                            <template x-if="checkIn.foto_profile">
                                <img 
                                    :src="checkIn.foto_profile" 
                                    :alt="checkIn.nama" 
                                    class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-sm"
                                >
                            </template>
                            <template x-if="!checkIn.foto_profile">
                                <div class="w-12 h-12 rounded-full bg-[#123D91] text-white flex items-center justify-center font-bold text-lg" x-text="checkIn.nama.substring(0, 1).toUpperCase()">
                                </div>
                            </template>

                            <div>
                                <h4 class="font-extrabold text-slate-800 text-sm" x-text="checkIn.nama"></h4>
                                <p class="text-xs text-slate-400 font-medium mt-0.5" x-text="checkIn.skema_label"></p>
                            </div>
                        </div>

                        <!-- Right Info (Badge) -->
                        <div class="text-right">
                            <span class="text-[10px] font-black text-emerald-600 bg-[#E6F4EA] px-2.5 py-1 rounded-full border border-emerald-100 uppercase tracking-widest">
                                Check In
                            </span>
                        </div>

                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="liveCheckIns.length === 0" class="flex flex-col items-center justify-center h-48 text-center px-4 py-8">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h5 class="text-sm font-bold text-slate-500">Belum ada aktivitas</h5>
                    <p class="text-xs text-slate-400 mt-1">Check-in pegawai hari ini akan muncul secara otomatis di sini</p>
                </div>

            </div>

        </section>

    </main>

    <!-- Polling & Clock Logic using Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tvDashboard', (selectedDate) => ({
                date: selectedDate,
                isDemo: selectedDate !== new Date().toISOString().split('T')[0],
                totalPegawai: {{ $totalPegawai }},
                totalHadir: {{ $totalHadir }},
                wfoCount: {{ $wfoCount }},
                wfhCount: {{ $wfhCount }},
                sakitCount: {{ $sakitCount }},
                belumHadir: {{ $belumHadir }},
                liveCheckIns: @json($liveCheckIns),
                clockTime: '00:00:00',
                clockDate: '...',

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                    
                    // Auto-poll stats every 5 seconds
                    setInterval(() => this.fetchStats(), 5000);
                },

                updateClock() {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const d = new Date();
                    
                    // Format time as HH:MM:SS
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    const seconds = String(d.getSeconds()).padStart(2, '0');
                    this.clockTime = `${hours}:${minutes}:${seconds}`;

                    // Format date as "Senin, 27 Juli 2026"
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
                            this.wfoCount = data.wfoCount;
                            this.wfhCount = data.wfhCount;
                            this.sakitCount = data.sakitCount;
                            this.belumHadir = data.belumHadir;
                            
                            // Simple array equality check to prevent visual flicker if no changes
                            if (JSON.stringify(this.liveCheckIns) !== JSON.stringify(data.liveCheckIns)) {
                                this.liveCheckIns = data.liveCheckIns;
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching dashboard stats:', error);
                    }
                }
            }));
        });
    </script>
</body>
</html>
