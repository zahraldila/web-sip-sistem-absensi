<div x-show="exportModalOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeExport()"></div>
    <div class="relative w-full max-w-lg overflow-hidden rounded-[24px] bg-white shadow-[0_35px_100px_rgba(15,23,42,0.16)] ring-1 ring-slate-200" @click.stop>
        <form method="POST" action="{{ route('admin.employee-management.export') }}" @submit.prevent="submitExport($event)">
            @csrf
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Export Data Akun</h2>
                        <p class="mt-1 text-sm text-slate-500">Pilih format dan filter untuk mengekspor data akun karyawan.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" @click="closeExport()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900">
                            <option value="">Semua</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Divisi</label>
                        <select name="divisi_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900">
                            <option value="">Semua</option>
                            @foreach($filters['divisions'] as $div)
                                <option value="{{ $div->divisi_id }}">{{ $div->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Role</label>
                        <select name="jabatan_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900">
                            <option value="">Semua</option>
                            @foreach($filters['roles'] as $r)
                                <option value="{{ $r->jabatan_id }}">{{ $r->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Karyawan (pegawai_id)</label>
                        <input type="text" name="pegawai_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900" placeholder="Kosong = semua" />
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Format</label>
                    <select name="format" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900">
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-200 bg-white px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="closeExport()" class="inline-flex items-center justify-center rounded-[10px] border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:bg-slate-200">Batal</button>
                <button type="submit" x-bind:disabled="exportIsLoading" class="inline-flex items-center justify-center rounded-[10px] bg-[#123D91] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0F3277] disabled:cursor-not-allowed disabled:bg-slate-400" x-text="exportIsLoading ? 'Menyiapkan...' : 'Export'"></button>
            </div>
        </form>
    </div>
</div>
