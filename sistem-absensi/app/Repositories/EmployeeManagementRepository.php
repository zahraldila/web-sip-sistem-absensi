<?php

namespace App\Repositories;

use App\Models\Akun;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EmployeeManagementRepository
{
    public function query(): Builder
    {
        return Pegawai::query()->with(['akun', 'masterDivisi', 'masterJabatan']);
    }

    public function findByPegawaiId(int $pegawaiId): ?Pegawai
    {
        return Pegawai::with(['akun', 'masterDivisi', 'masterJabatan'])
            ->where('pegawai_id', $pegawaiId)
            ->first();
    }

    public function create(array $data): Pegawai
    {
        return Pegawai::create($data);
    }

    public function update(Pegawai $pegawai, array $data): Pegawai
    {
        $pegawai->update($data);

        return $pegawai;
    }

    public function createAccount(array $data): Akun
    {
        if (isset($data['pegawai_id'])) {
            return Akun::firstOrCreate(
                ['pegawai_id' => $data['pegawai_id']],
                $data
            );
        }

        return Akun::create($data);
    }

    public function updateAccount(Akun $akun, array $data): Akun
    {
        $akun->update($data);

        return $akun;
    }

    public function usernameExists(string $username): bool
    {
        return Akun::where('username', $username)->exists();
    }

    public function getDivisions(): Collection
    {
        return MasterDivisi::query()
            ->orderBy('nama_divisi')
            ->get();
    }

    public function getRoles(): Collection
    {
        return MasterJabatan::query()
            ->orderBy('nama_jabatan')
            ->get();
    }

    public function createDivision(array $data): MasterDivisi
    {
        return MasterDivisi::create($data);
    }

    public function createRole(array $data): MasterJabatan
    {
        return MasterJabatan::create($data);
    }
}
