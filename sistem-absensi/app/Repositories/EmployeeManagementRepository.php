<?php

namespace App\Repositories;

use App\Models\Akun;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\Nfc;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EmployeeManagementRepository
{
    public function query(): Builder
    {
        return Pegawai::query()->with(['akun', 'masterDivisi', 'masterJabatan', 'nfc']);
    }

    public function findByPegawaiId(int $pegawaiId): ?Pegawai
    {
        return Pegawai::with(['akun', 'masterDivisi', 'masterJabatan', 'nfc'])
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

    public function findNfcByPegawaiId(int $pegawaiId): ?Nfc
    {
        return Nfc::where('pegawai_id', $pegawaiId)->first();
    }

    public function createNfc(array $data): Nfc
    {
        return Nfc::create($data);
    }

    public function updateNfc(Nfc $nfc, array $data): Nfc
    {
        $nfc->update($data);

        return $nfc;
    }

    public function deleteNfcByPegawaiId(int $pegawaiId): void
    {
        Nfc::where('pegawai_id', $pegawaiId)->delete();
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

    public function getAccountsForExport(array $filters = []): Collection
    {
        $query = Pegawai::with(['akun', 'masterDivisi', 'masterJabatan'])
            ->when(!empty($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['divisi_id']), function ($q) use ($filters) {
                $q->where('divisi_id', $filters['divisi_id']);
            })
            ->when(!empty($filters['pegawai_id']), function ($q) use ($filters) {
                $q->where('pegawai_id', $filters['pegawai_id']);
            })
            ->when(!empty($filters['jabatan_id']), function ($q) use ($filters) {
                $q->where('jabatan_id', $filters['jabatan_id']);
            })
            ->orderBy('nama_pegawai');

        return $query->get();
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
