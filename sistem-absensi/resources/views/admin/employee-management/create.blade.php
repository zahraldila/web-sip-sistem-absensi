@extends('layouts.admin.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Tambah Akun Karyawan</h1>
        <p class="text-sm text-gray-600">Buat akun baru untuk pegawai yang akan menggunakan sistem.</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.employee-management.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" name="nip" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                @error('nip')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">NFC ID</label>
                <input type="text" name="nfc_id" value="{{ old('nfc_id') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                @error('nfc_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Username (opsional)</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                @error('username')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">No. Telepon</label>
                <input type="text" name="no_handphone" value="{{ old('no_handphone') }}" placeholder="Contoh : 081234567890" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                @error('no_handphone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Divisi</label>
                <select name="divisi_id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">Pilih Divisi</option>
                    @foreach ($filters['divisions'] ?? [] as $division)
                        <option value="{{ $division->divisi_id }}" {{ old('divisi_id') == $division->divisi_id ? 'selected' : '' }}>{{ $division->nama_divisi }}</option>
                    @endforeach
                </select>
                @error('divisi_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Jabatan</label>
                <select name="jabatan_id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">Pilih Jabatan</option>
                    @foreach ($filters['roles'] ?? [] as $role)
                        <option value="{{ $role->jabatan_id }}" {{ old('jabatan_id') == $role->jabatan_id ? 'selected' : '' }}>{{ $role->nama_jabatan }}</option>
                    @endforeach
                </select>
                @error('jabatan_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Foto Profil (opsional)</label>
                <input type="file" name="foto_profile" accept="image/*" class="w-full text-sm">
                @error('foto_profile')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('admin.employee-management.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
