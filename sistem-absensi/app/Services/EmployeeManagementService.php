<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\Pegawai;
use App\Repositories\EmployeeManagementRepository;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class EmployeeManagementService
{
    public function __construct(protected EmployeeManagementRepository $repository)
    {
    }

    public function listEmployees(?string $search = null): EloquentCollection
    {
        $query = $this->repository->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pegawai', 'ilike', '%' . $search . '%')
                    ->orWhere('nip', 'ilike', '%' . $search . '%')
                    ->orWhere('email', 'ilike', '%' . $search . '%');
            });
        }

        return $query->orderBy('nama_pegawai')->get();
    }

    public function getEmployee(int $pegawaiId): ?Pegawai
    {
        return $this->repository->findByPegawaiId($pegawaiId);
    }

    public function getFilterOptions(): array
    {
        return [
            'divisions' => $this->repository->getDivisions(),
            'roles' => $this->repository->getRoles(),
        ];
    }

    public function createDivision(string $name)
    {
        return $this->repository->createDivision([
            'nama_divisi' => $name,
        ]);
    }

    public function createRole(string $name)
    {
        return $this->repository->createRole([
            'nama_jabatan' => $name,
        ]);
    }

    public function saveEmployee(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $pegawai = $this->repository->create([
                'nip' => $data['nip'] ?? null,
                'nama_pegawai' => $data['nama_pegawai'] ?? null,
                'email' => $data['email'] ?? null,
                'no_handphone' => $data['no_handphone'] ?? null,
                'divisi_id' => $data['divisi_id'] ?? null,
                'jabatan_id' => $data['jabatan_id'] ?? null,
                'status' => $data['status'] ?? 'Aktif',
            ]);

            // handle profile photo upload (optional)
            if (!empty($data['foto_profile_file'])) {
                $file = $data['foto_profile_file'];

                $bucket = config('supabase.bucket', 'profile-images');
                $fileName = 'pegawai_' . $pegawai->pegawai_id . '_' . time() . '_' . Str::random(6) . '.' . $file->extension();
                $remotePath = trim($fileName, '/');

                $baseUrl = supabase_url() ?: config('supabase.url');
                if (! $baseUrl) {
                    throw new \RuntimeException('Supabase URL belum dikonfigurasi. Pastikan SUPABASE_URL di .env berisi URL Supabase seperti https://<project-ref>.supabase.co');
                }

                $baseUrl = rtrim($baseUrl, '/');
                if (! preg_match('/^https?:\/\//i', $baseUrl)) {
                    $baseUrl = 'https://' . ltrim($baseUrl, '/');
                }

                $uploadUrl = $baseUrl . '/storage/v1/object/' . rawurlencode($bucket) . '/' . rawurlencode($remotePath);

                $apiKey = supabase_key() ?: config('supabase.key');
                if (! $apiKey) {
                    throw new \RuntimeException('Supabase key belum dikonfigurasi. Pastikan SUPABASE_KEY di .env berisi kunci API Supabase.');
                }

                $resp = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'apikey' => $apiKey,
                    'Content-Type' => $file->getClientMimeType(),
                ])->withBody(file_get_contents($file->getRealPath()), $file->getClientMimeType())->put($uploadUrl);

                if (! $resp->successful()) {
                    throw new \RuntimeException('Gagal mengunggah foto profil: ' . $resp->body());
                }

                // save path like 'profile-images/pegawai_2_xxx.jpg'
                $fotoPath = rtrim($bucket, '/') . '/' . $remotePath;
                $this->repository->update($pegawai, ['foto_profile' => $fotoPath]);
            }

            $username = $this->buildUsername($pegawai);

            $akunData = [
                'pegawai_id' => $pegawai->pegawai_id,
                'email' => $data['email'] ?? null,
                'username' => $username,
                'password' => Hash::make($data['password'] ?? 'password123'),
                'role' => 'Karyawan',
                'status' => 'Aktif',
            ];

            $akun = $this->repository->createAccount($akunData);

            return ['pegawai' => $pegawai, 'akun' => $akun];
        });
    }

    protected function buildUsername(Pegawai $pegawai): string
    {
        $username = trim((string) $pegawai->nip);

        if ($username === '') {
            $username = 'user-' . Str::random(8);
        }

        return $this->ensureUniqueUsername($username);
    }

    protected function ensureUniqueUsername(string $username): string
    {
        $original = $username;
        $suffix = 0;

        while ($this->repository->usernameExists($username)) {
            $suffix++;
            $username = $original . $suffix;
        }

        return $username;
    }

    public function updateEmployee(Pegawai $pegawai, array $data): array
    {
        $this->repository->update($pegawai, [
            'nip' => $data['nip'] ?? $pegawai->nip,
            'nama_pegawai' => $data['nama_pegawai'] ?? $pegawai->nama_pegawai,
            'email' => $data['email'] ?? $pegawai->email,
            'divisi_id' => $data['divisi_id'] ?? $pegawai->divisi_id,
            'jabatan_id' => $data['jabatan_id'] ?? $pegawai->jabatan_id,
            'status' => $data['status'] ?? $pegawai->status,
        ]);

        if ($pegawai->akun) {
            $this->repository->updateAccount($pegawai->akun, [
                'email' => $data['email'] ?? $pegawai->akun->email,
                'username' => $data['username'] ?? $pegawai->akun->username,
                'status' => $data['status'] ?? $pegawai->akun->status,
            ]);
        }

        return ['pegawai' => $pegawai, 'akun' => $pegawai->akun];
    }
}
