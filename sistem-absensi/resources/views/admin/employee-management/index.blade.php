@extends('layouts.admin.app')

@section('content')
<div class="p-6" x-data="employeeModal()" x-init="init()">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Manajemen Akun Karyawan</h1>
            <p class="text-sm text-gray-600">Kelola akun dan data pegawai yang terhubung dengan sistem absensi.</p>
        </div>
        <button type="button" @click.prevent="openCreate()" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#123D91] px-4 py-3 text-sm font-medium text-white transition hover:bg-[#0F3277]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pegawai
        </button>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-cloak x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition class="mb-4 relative rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <div class="pr-6">{{ session('success') }}</div>
            <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="absolute right-2 top-2 text-green-700 hover:text-green-900">
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-cloak x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition class="mb-4 relative rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <div class="pr-6">{{ session('error') }}</div>
            <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="absolute right-2 top-2 text-red-700 hover:text-red-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.employee-management.index') }}">
            <div class="grid gap-4 lg:grid-cols-[1fr_auto_auto] lg:items-end">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Cari Karyawan</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 12.65z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Karyawan" class="w-full rounded-2xl border border-gray-300 bg-white py-3 pl-10 pr-4 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                        Filter
                    </button>
                    <button type="button" @click.prevent="openExport()" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                        Export
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Photo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Employee ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="flex items-center gap-3">
                                    @php $photoUrl = supabase_public_url($employee->foto_profile); @endphp
                                    @if ($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $employee->nama_pegawai }}" class="h-10 w-10 rounded-full object-cover" />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-semibold text-slate-700">
                                            {{ getInitials($employee->nama_pegawai) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $employee->nama_pegawai }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->nip ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->masterDivisi->nama_divisi ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->masterJabatan->nama_jabatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                    {{ $employee->status ?? 'Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button type="button" @click.prevent="openEdit($event)"
                                        data-employee="{{ json_encode([
                                            'pegawai_id' => $employee->pegawai_id,
                                            'nama_pegawai' => $employee->nama_pegawai,
                                            'nip' => $employee->nip,
                                            'email' => $employee->email,
                                            'divisi_id' => $employee->divisi_id,
                                            'jabatan_id' => $employee->jabatan_id,
                                            'status' => $employee->status ?? 'Aktif',
                                            'no_handphone' => $employee->no_handphone,
                                            'nfc_id' => $employee->nfc->nfc_serial_number ?? '',
                                            'foto_profile_path' => $employee->foto_profile ?? '',
                                            'foto_profile' => $employee->foto_profile ? supabase_public_url($employee->foto_profile) : '',
                                            'username' => $employee->akun->username ?? ''
                                        ], JSON_HEX_APOS | JSON_HEX_QUOT) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col gap-4 border-t border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            @php
                $from = $employees->firstItem() ?: 0;
                $to = $employees->lastItem() ?: 0;
                $total = $employees->total();
                $currentPage = $employees->currentPage();
                $lastPage = $employees->lastPage();
            @endphp

            <p class="text-sm text-slate-500">
                Menampilkan {{ $from }} - {{ $to }} dari {{ $total }} data
            </p>

            <nav class="inline-flex overflow-hidden rounded-[16px] border border-slate-200 bg-white shadow-sm" aria-label="Pagination">
                <a
                    class="inline-flex h-[46px] w-[46px] items-center justify-center border-r border-slate-200 text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-l-[16px] {{ $employees->onFirstPage() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
                    href="{{ $employees->onFirstPage() ? '#' : $employees->previousPageUrl() }}"
                    aria-disabled="{{ $employees->onFirstPage() ? 'true' : 'false' }}"
                >
                    ‹
                </a>

                @for ($page = 1; $page <= $lastPage; $page++)
                    <a
                        class="inline-flex h-[46px] min-w-[50px] items-center justify-center border-r border-slate-200 px-4 text-sm font-medium transition hover:bg-slate-50 {{ $page === $currentPage ? 'bg-slate-100 text-[#123D91]' : 'bg-white text-slate-700' }}"
                        href="{{ $employees->url($page) }}"
                    >
                        {{ $page }}
                    </a>
                @endfor

                <a
                    class="inline-flex h-[46px] w-[46px] items-center justify-center text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-r-[16px] {{ ! $employees->hasMorePages() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
                    href="{{ $employees->hasMorePages() ? $employees->nextPageUrl() : '#' }}"
                    aria-disabled="{{ ! $employees->hasMorePages() ? 'true' : 'false' }}"
                >
                    ›
                </a>
            </nav>
        </div>
    </div>

    @include('admin.employee-management._employee-form-modal')
    @include('admin.employee-management._export-modal')

    <div x-show="divisionModalOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeDivisionModal()"></div>
        <div class="relative w-full max-w-md overflow-hidden rounded-[24px] bg-white shadow-[0_35px_100px_rgba(15,23,42,0.16)] ring-1 ring-slate-200" @click.stop>
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Tambah Divisi</h2>
                        <p class="mt-1 text-sm text-slate-500">Tambahkan divisi baru tanpa meninggalkan halaman ini.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeDivisionModal()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5">
                <div class="space-y-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Divisi</label>
                        <input type="text" x-model="newDivisionName" placeholder="Contoh: HR"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        <template x-if="divisionError">
                            <p class="mt-1 text-xs text-red-600" x-text="divisionError"></p>
                        </template>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-white px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="closeDivisionModal()"
                    class="inline-flex items-center justify-center rounded-[10px] border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:bg-slate-200">
                    Batal
                </button>
                <button type="button" @click="saveDivision()"
                    class="inline-flex items-center justify-center rounded-[10px] bg-[#123D91] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0F3277]"
                    x-bind:disabled="isSavingDivision"
                    x-text="isSavingDivision ? 'Menyimpan...' : 'Simpan'"></button>
            </div>
        </div>
    </div>

    <div x-show="roleModalOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeRoleModal()"></div>
        <div class="relative w-full max-w-md overflow-hidden rounded-[24px] bg-white shadow-[0_35px_100px_rgba(15,23,42,0.16)] ring-1 ring-slate-200" @click.stop>
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Tambah Jabatan</h2>
                        <p class="mt-1 text-sm text-slate-500">Tambahkan jabatan baru tanpa meninggalkan halaman ini.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeRoleModal()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5">
                <div class="space-y-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Jabatan</label>
                        <input type="text" x-model="newJabatanName" placeholder="Contoh: HR Manager"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        <template x-if="roleError">
                            <p class="mt-1 text-xs text-red-600" x-text="roleError"></p>
                        </template>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-white px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="closeRoleModal()"
                    class="inline-flex items-center justify-center rounded-[10px] border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:bg-slate-200">
                    Batal
                </button>
                <button type="button" @click="saveRole()"
                    class="inline-flex items-center justify-center rounded-[10px] bg-[#123D91] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0F3277]"
                    x-bind:disabled="isSavingRole"
                    x-text="isSavingRole ? 'Menyimpan...' : 'Simpan'"></button>
            </div>
        </div>
    </div>

    <script>
        function employeeModal() {
            const oldForm = {!! json_encode([
                'pegawai_id' => old('pegawai_id', ''),
                'nama_pegawai' => old('nama_pegawai', ''),
                'nip' => old('nip', ''),
                'nfc_id' => old('nfc_id', ''),
                'divisi_id' => old('divisi_id', ''),
                'jabatan_id' => old('jabatan_id', ''),
                'email' => old('email', ''),
                'no_handphone' => old('no_handphone', ''),
                'status' => old('status', 'Aktif'),
                'password' => '',
                'password_confirmation' => '',
                'username' => old('username', ''),
                'photoPreview' => '',
                'foto_profile_existing' => old('foto_profile_existing', ''),
            ], JSON_HEX_APOS | JSON_HEX_QUOT) !!};

            return {
                modalOpen: false,
                mode: 'create',
                formAction: '{{ route('admin.employee-management.store') }}',
                modalTitle: 'Tambah Pegawai',
                submitLabel: 'Simpan',
                showPassword: false,
                csrfToken: document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '',
                filters: {
                    divisions: {!! json_encode($filters['divisions'] ?? []) !!},
                    roles: {!! json_encode($filters['roles'] ?? []) !!}
                },
                divisionModalOpen: false,
                roleModalOpen: false,
                newDivisionName: '',
                newJabatanName: '',
                divisionError: '',
                roleError: '',
                divisionSuccess: '',
                roleSuccess: '',
                isSavingDivision: false,
                isSavingRole: false,
                previewObjectUrl: null,
                form: {
                    pegawai_id: '',
                    nama_pegawai: '',
                    nip: '',
                    nfc_id: '',
                    foto_profile: '',
                    foto_profile_existing: '',
                    divisi_id: '',
                    jabatan_id: '',
                    email: '',
                    no_handphone: '',
                    status: 'Aktif',
                    password: '',
                    username: '',
                    photoPreview: ''
                },
                init() {
                    const previousMode = '{{ old('form_mode', '') }}';

                    if (previousMode === 'edit' || previousMode === 'create') {
                        this.mode = previousMode;
                        this.modalTitle = previousMode === 'edit' ? 'Edit Akun Karyawan' : 'Tambah Pegawai';
                        this.submitLabel = previousMode === 'edit' ? 'Perbarui' : 'Simpan';
                        this.form = oldForm;

                        if (previousMode === 'edit') {
                            this.formAction = '{{ url("/admin/employee-management") }}/' + this.form.pegawai_id;
                        }

                        this.modalOpen = true;
                        this.divisionSuccess = '';
                        this.roleSuccess = '';
                        this.closeDivisionModal();
                        this.closeRoleModal();
                    }
                },
                resetModalState() {
                    this.modalOpen = false;
                    this.divisionModalOpen = false;
                    this.roleModalOpen = false;
                    this.newDivisionName = '';
                    this.newJabatanName = '';
                    this.divisionError = '';
                    this.roleError = '';
                    this.divisionSuccess = '';
                    this.roleSuccess = '';
                    this.isSavingDivision = false;
                    this.isSavingRole = false;
                    this.showPassword = false;
                    if (this.previewObjectUrl) {
                        URL.revokeObjectURL(this.previewObjectUrl);
                        this.previewObjectUrl = null;
                    }
                    const fileInput = document.getElementById('photoInput');
                    if (fileInput) {
                        fileInput.value = null;
                    }
                    this.form = {
                        pegawai_id: '',
                        nama_pegawai: '',
                        nip: '',
                        nfc_id: '',
                        foto_profile: '',
                        foto_profile_existing: '',
                        divisi_id: '',
                        jabatan_id: '',
                        email: '',
                        no_handphone: '',
                        status: 'Aktif',
                        password: '',
                        password_confirmation: '',
                        username: '',
                        photoPreview: ''
                    };
                },
                openCreate() {
                    this.resetModalState();
                    this.mode = 'create';
                    this.formAction = '{{ route('admin.employee-management.store') }}';
                    this.modalTitle = 'Tambah Pegawai';
                    this.submitLabel = 'Simpan';
                    this.modalOpen = true;
                },
                openEdit(event) {
                    this.resetModalState();
                    const employee = JSON.parse(event.currentTarget.dataset.employee || '{}');
                    this.mode = 'edit';
                    this.formAction = '{{ url('/admin/employee-management') }}/' + employee.pegawai_id;
                    this.modalTitle = 'Edit Akun Karyawan';
                    this.submitLabel = 'Perbarui';
                    this.showPassword = false;
                    this.form = {
                        pegawai_id: employee.pegawai_id || '',
                        nama_pegawai: employee.nama_pegawai || '',
                        nip: employee.nip || '',
                        nfc_id: employee.nfc_id || '',
                        foto_profile: '',
                        foto_profile_existing: employee.foto_profile_path || '',
                        divisi_id: employee.divisi_id || '',
                        jabatan_id: employee.jabatan_id || '',
                        email: employee.email || '',
                        no_handphone: employee.no_handphone || '',
                        status: employee.status || 'Aktif',
                        password: '',
                        username: employee.username || '',
                        photoPreview: ''
                    };
                    this.modalOpen = true;
                },
                closeModal() {
                    this.resetModalState();
                },
                previewPhoto(event) {
                    const file = event.target.files[0];
                    if (!file) {
                        if (this.previewObjectUrl) {
                            URL.revokeObjectURL(this.previewObjectUrl);
                            this.previewObjectUrl = null;
                        }
                        this.form.photoPreview = '';
                        return;
                    }

                    if (this.previewObjectUrl) {
                        URL.revokeObjectURL(this.previewObjectUrl);
                    }

                    this.previewObjectUrl = URL.createObjectURL(file);
                    this.form.photoPreview = this.previewObjectUrl;
                },
                openDivisionModal() {
                    this.divisionModalOpen = true;
                    this.newDivisionName = '';
                    this.divisionError = '';
                    this.divisionSuccess = '';
                },
                closeDivisionModal() {
                    this.divisionModalOpen = false;
                    this.newDivisionName = '';
                    this.divisionError = '';
                },
                openRoleModal() {
                    this.roleModalOpen = true;
                    this.newJabatanName = '';
                    this.roleError = '';
                    this.roleSuccess = '';
                },
                closeRoleModal() {
                    this.roleModalOpen = false;
                    this.newJabatanName = '';
                    this.roleError = '';
                },
                exportModalOpen: false,
                exportIsLoading: false,
                openExport() {
                    this.exportModalOpen = true;
                },
                closeExport() {
                    this.exportModalOpen = false;
                },
                submitExport(event) {
                    if (this.exportIsLoading) {
                        return;
                    }

                    this.exportIsLoading = true;
                    event.target.submit();
                },
                async saveDivision() {
                    this.divisionError = '';
                    this.divisionSuccess = '';
                    const namaDivisi = (this.newDivisionName || '').trim();

                    if (!namaDivisi) {
                        this.divisionError = 'Nama divisi wajib diisi.';
                        return;
                    }

                    this.isSavingDivision = true;

                    try {
                        const response = await fetch('{{ route('admin.employee-management.storeDivision') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ nama_divisi: namaDivisi }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            if (payload.errors?.nama_divisi?.[0]) {
                                this.divisionError = payload.errors.nama_divisi[0];
                            } else {
                                this.divisionError = payload.message || 'Gagal menambahkan divisi.';
                            }
                            return;
                        }

                        const select = document.querySelector('select[name="divisi_id"]');
                        if (select) {
                            const option = document.createElement('option');
                            option.value = payload.division.divisi_id;
                            option.textContent = payload.division.nama_divisi;
                            select.appendChild(option);
                        }

                        this.form.divisi_id = payload.division.divisi_id;
                        this.divisionSuccess = payload.message || 'Divisi berhasil ditambahkan.';
                        this.closeDivisionModal();
                    } catch (error) {
                        this.divisionError = 'Terjadi kesalahan saat menyimpan divisi.';
                    } finally {
                        this.isSavingDivision = false;
                    }
                },
                async saveRole() {
                    this.roleError = '';
                    this.roleSuccess = '';
                    const namaJabatan = (this.newJabatanName || '').trim();

                    if (!namaJabatan) {
                        this.roleError = 'Nama jabatan wajib diisi.';
                        return;
                    }

                    this.isSavingRole = true;

                    try {
                        const response = await fetch('{{ route('admin.employee-management.storeRole') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ nama_jabatan: namaJabatan }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            if (payload.errors?.nama_jabatan?.[0]) {
                                this.roleError = payload.errors.nama_jabatan[0];
                            } else {
                                this.roleError = payload.message || 'Gagal menambahkan jabatan.';
                            }
                            return;
                        }

                        const select = document.querySelector('select[name="jabatan_id"]');
                        if (select) {
                            const option = document.createElement('option');
                            option.value = payload.role.jabatan_id;
                            option.textContent = payload.role.nama_jabatan;
                            select.appendChild(option);
                        }

                        this.form.jabatan_id = payload.role.jabatan_id;
                        this.roleSuccess = payload.message || 'Jabatan berhasil ditambahkan.';
                        this.closeRoleModal();
                    } catch (error) {
                        this.roleError = 'Terjadi kesalahan saat menyimpan jabatan.';
                    } finally {
                        this.isSavingRole = false;
                    }
                }
            };
        }
    </script>
</div>
@endsection
