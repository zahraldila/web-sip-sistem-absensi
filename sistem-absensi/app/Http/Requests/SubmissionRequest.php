<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_pengajuan' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'keterangan' => 'required|string',
            'lampiran' => 'nullable|file|max:10240',
        ];
    }
}
