<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\logHelpers;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Services\EmployeeManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeManagementController extends Controller
{
    public function __construct(protected EmployeeManagementService $service)
    {
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $employees = $this->service->listEmployees($search);
        $filters = $this->service->getFilterOptions();

        return view('admin.employee-management.index', compact('employees', 'filters', 'search'));
    }

    public function create()
    {
        $filters = $this->service->getFilterOptions();

        return view('admin.employee-management.create', compact('filters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'nama_pegawai' => 'required|string|max:255',
            'nfc_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:pegawai,email',
            'no_handphone' => 'nullable|string|regex:/^[0-9]+$/|max:20',
            'foto_profile' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'divisi_id' => 'nullable|integer|exists:master_divisi,divisi_id',
            'jabatan_id' => 'nullable|integer|exists:master_jabatan,jabatan_id',
            'username' => ['nullable', 'string', 'max:100', 'unique:akun,username'],
            'password' => 'required|string|min:6|confirmed',
            'status' => 'nullable|string|max:50',
        ], [
            'nama_pegawai.required' => 'Nama lengkap wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pegawai lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto_profile.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // Minimal salah satu Email atau Username wajib diisi agar pegawai bisa login.
        if (empty(trim($data['email'] ?? '')) && empty(trim($data['username'] ?? ''))) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email atau Username wajib diisi (minimal salah satu) agar pegawai dapat login.',
                ]);
        }

        // Include uploaded file instance for the service
        $data['foto_profile_file'] = $request->file('foto_profile');

        // Simpan data pegawai
        $result = $this->service->saveEmployee($data);

        // Catat aktivitas setelah proses berhasil
        $user = Auth::user();

        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                "Menambahkan data pegawai: {$result['pegawai']->nama_pegawai}"
            );
        }

        return redirect()
            ->route('admin.employee-management.index')
            ->with('success', 'Akun karyawan berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $employee = $this->service->getEmployee($pegawai->pegawai_id);
        $filters = $this->service->getFilterOptions();

        return view('admin.employee-management.edit', compact('employee', 'filters'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'nip' => 'nullable|string|max:50|unique:pegawai,nip,' . $pegawai->pegawai_id . ',pegawai_id',
            'nama_pegawai' => 'required|string|max:255',
            'nfc_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:pegawai,email,' . $pegawai->pegawai_id . ',pegawai_id',
            'no_handphone' => 'nullable|string|regex:/^[0-9]+$/|max:20',
            'foto_profile' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'divisi_id' => 'nullable|integer|exists:master_divisi,divisi_id',
            'jabatan_id' => 'nullable|integer|exists:master_jabatan,jabatan_id',
            'username' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('akun', 'username')
                    ->ignore($pegawai->akun->akun_id ?? null, 'akun_id')
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'nullable|string|max:50',
        ]);

        $data['foto_profile_file'] = $request->file('foto_profile');

        // Update data pegawai
        $result = $this->service->updateEmployee($pegawai, $data);

        // Catat aktivitas setelah proses berhasil
        $user = Auth::user();

        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                "Memperbarui data pegawai: {$result['pegawai']->nama_pegawai}"
            );
        }

        return redirect()
            ->route('admin.employee-management.index')
            ->with('success', 'Akun karyawan berhasil diperbarui.');
    }

    public function storeDivision(Request $request)
    {
        $data = $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:master_divisi,nama_divisi',
        ]);

        // Simpan divisi
        $division = $this->service->createDivision($data['nama_divisi']);

        // Catat aktivitas setelah proses berhasil
        $user = Auth::user();

        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                "Menambahkan divisi baru: {$division->nama_divisi}"
            );
        }

        return response()->json([
            'message' => 'Divisi berhasil ditambahkan.',
            'division' => $division,
        ], 201);
    }

    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:master_jabatan,nama_jabatan',
        ]);

        // Simpan jabatan
        $role = $this->service->createRole($data['nama_jabatan']);

        // Catat aktivitas setelah proses berhasil
        $user = Auth::user();

        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                "Menambahkan jabatan baru: {$role->nama_jabatan}"
            );
        }

        return response()->json([
            'message' => 'Jabatan berhasil ditambahkan.',
            'role' => $role,
        ], 201);
    }
}