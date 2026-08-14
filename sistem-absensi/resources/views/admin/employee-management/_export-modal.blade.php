<div x-show="exportModalOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
    @click.self="closeExport()">
    <div class="relative w-full max-w-2xl overflow-hidden rounded-[28px] bg-white shadow-2xl" @click.stop>
        <form method="POST" action="{{ route('admin.employee-management.export') }}" @submit.prevent="submitExport($event)">
            @csrf
            <div class="flex items-center justify-between border-b border-slate-200 px-8 py-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Export Data Akun</h2>
                    <p class="mt-1 text-sm text-slate-500">Pilih format dan filter untuk unduh data akun karyawan.</p>
                </div>
                <button type="button" class="flex items-center justify-center rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" @click="closeExport()" aria-label="Tutup modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6 px-8 py-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-slate-900">Format Export</p>
                        <div class="space-y-3">
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="xlsx" class="mt-1 h-4 w-4 text-[#123D91]" checked />
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">Excel (.xlsx)</p>
                                    <p class="text-xs text-slate-500">Unduh file Excel dengan data akun karyawan.</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="csv" class="mt-1 h-4 w-4 text-[#123D91]" />
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">CSV</p>
                                    <p class="text-xs text-slate-500">Unduh file CSV yang mudah diolah.</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="pdf" class="mt-1 h-4 w-4 text-[#123D91]" />
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">PDF</p>
                                    <p class="text-xs text-slate-500">Unduh file PDF yang siap dicetak.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-slate-900">Filter Export</p>
                        <div class="grid gap-4">
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
                    </div>
                </div>

                <div class="grid gap-4 border-t border-slate-200 pt-6 lg:grid-cols-2">
                    <div></div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                    <button type="button" @click="closeExport()" class="rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" x-bind:disabled="exportIsLoading" class="rounded-3xl bg-[#123D91] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#0F3277]" x-text="exportIsLoading ? 'Menyiapkan...' : 'Unduh'"></button>
                </div>
            </div>
        </form>
    </div>
</div>
