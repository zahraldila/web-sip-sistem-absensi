<div x-show="modalOpen"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        @click="closeModal()"></div>

    <div class="relative w-full overflow-hidden rounded-[24px] bg-white shadow-[0_35px_100px_rgba(15,23,42,0.16)] ring-1 ring-slate-200" style="width: min(90vw, 600px); max-height: 90vh;"
        @click.stop>

        <div class="flex max-h-[90vh] min-h-[320px] flex-col overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900" x-text="modalTitle"></h2>
                        <p class="mt-1 text-sm text-slate-500">Isi informasi pegawai untuk membuat akun baru</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeModal()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto px-6 py-3 pb-8" style="max-height: calc(90vh - 156px);">
                <form id="employeeForm" :action="formAction" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="form_mode" :value="mode">
                    <input type="hidden" name="pegawai_id" x-model="form.pegawai_id">
                    <input type="hidden" name="foto_profile_existing" x-model="form.foto_profile_existing">
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="rounded-[20px] border border-slate-200 bg-slate-50 p-4">
                        <label for="photoInput" class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-[20px] bg-white p-3 text-center">
                            <div class="flex h-[90px] w-full max-w-[140px] items-center justify-center rounded-[20px] bg-slate-100 text-slate-500 shadow-sm overflow-hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M8 12a4 4 0 118 0M12 8v8" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-slate-900">Klik untuk Upload Foto</p>
                                <p class="text-xs text-slate-500">PNG, JPG, HEIC, HEIF, WEBP maksimal 2MB</p>
                            </div>
                        </label>
                        <input id="photoInput" type="file" name="foto_profile" accept="image/png,image/jpeg,image/heic,image/heif,image/webp" @change="previewPhoto($event)" class="hidden">

                        <template x-if="form.photoPreview">
                            <img :src="form.photoPreview" alt="Preview foto baru" class="mx-auto mt-3 h-24 w-24 rounded-full object-cover" />
                        </template>
                        <template x-if="photoUploadError">
                            <p class="mt-1 text-xs text-red-600" x-text="photoUploadError"></p>
                        </template>
                        @error('foto_profile')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                            <input type="text" name="nama_pegawai" x-model="form.nama_pegawai" required placeholder="Masukkan Nama Lengkap Pegawai"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('nama_pegawai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Employee ID</label>
                            <input type="text" name="nip" x-model="form.nip" placeholder="Contoh : EMP-0101010"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('nip')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">NFC ID</label>
                            <input type="text" name="nfc_id" x-model="form.nfc_id" placeholder="Contoh : EMP-0101010"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('nfc_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Divisi</label>
                            <select name="divisi_id" x-model="form.divisi_id"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">Pilih Divisi</option>
                                @foreach ($filters['divisions'] ?? [] as $division)
                                    <option value="{{ $division['divisi_id'] }}">{{ $division['nama_divisi'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" @click.prevent="openDivisionModal()"
                                class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-[#123D91] hover:text-[#0F3277]">
                                <span class="text-xl">＋</span> Tambah Divisi
                            </button>
                            <template x-if="divisionSuccess">
                                <p class="mt-2 text-sm text-green-600" x-text="divisionSuccess"></p>
                            </template>
                            @error('divisi_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Role / Jabatan</label>
                            <select name="jabatan_id" x-model="form.jabatan_id"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">Pilih Role/Jabatan</option>
                                @foreach ($filters['roles'] ?? [] as $role)
                                    <option value="{{ $role['jabatan_id'] }}">{{ $role['nama_jabatan'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" @click.prevent="openRoleModal()"
                                class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-[#123D91] hover:text-[#0F3277]">
                                <span class="text-xl">＋</span> Tambah Jabatan
                            </button>
                            <template x-if="roleSuccess">
                                <p class="mt-2 text-sm text-green-600" x-text="roleSuccess"></p>
                            </template>
                            @error('jabatan_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" x-model="form.email" placeholder="Contoh : admin@spi.co.id"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Username (opsional)</label>
                            <input type="text" name="username" x-model="form.username" placeholder="Contoh : jdoe"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('username')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">No. Telepon</label>
                            <input type="text" name="no_handphone" x-model="form.no_handphone" placeholder="Contoh : 010101010"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            @error('no_handphone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status Akun</label>
                            <select name="status" x-model="form.status"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                            @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" x-model="form.password"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-slate-500 transition hover:text-slate-700">
                                    <template x-if="showPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.26.237-2.46.666-3.576M4.5 4.5L19.5 19.5" />
                                        </svg>
                                    </template>
                                    <template x-if="!showPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </template>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="form.password_confirmation"
                                placeholder="Ketik ulang password"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="border-t border-slate-200 bg-white px-6 pt-4 pb-6 flex-shrink-0">
                <div class="flex justify-end items-center gap-3">
                    <button type="button" @click="closeModal()"
                        class="inline-flex items-center justify-center rounded-[10px] border border-slate-300 bg-slate-100 text-sm font-semibold text-slate-800 transition hover:bg-slate-200"
                        style="min-width:80px; height:42px; padding:0 18px; font-size:14px;">
                        Batal
                    </button>
                    <button type="submit" form="employeeForm"
                        class="inline-flex items-center justify-center rounded-[10px] bg-[#123D91] text-sm font-semibold text-white transition hover:bg-[#0F3277]"
                        style="min-width:80px; height:42px; padding:0 18px; font-size:14px;"
                        x-text="submitLabel"></button>
                </div>
            </div>
        </div>
    </div>
</div>
