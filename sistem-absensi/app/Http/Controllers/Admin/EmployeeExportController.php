<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmployeeManagementService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeExportController extends Controller
{
    protected EmployeeManagementService $service;

    public function __construct(EmployeeManagementService $service)
    {
        $this->service = $service;
    }

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:pdf,xlsx,csv',
            'status' => 'nullable|string',
            'divisi_id' => 'nullable|integer',
            'jabatan_id' => 'nullable|integer',
            'pegawai_id' => 'nullable|integer',
        ]);

        $filters = $request->only(['status','divisi_id','jabatan_id','pegawai_id']);
        $format = $request->input('format');

        $response = $this->service->exportAccounts($filters, $format);

        if ($response === null) {
            return redirect()->back()->with('error', 'Tidak ada data yang sesuai dengan filter.');
        }

        return $response;
    }
}
