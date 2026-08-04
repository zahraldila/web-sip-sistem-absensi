<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_approval' => 'required|in:Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string',
        ];
    }
}
