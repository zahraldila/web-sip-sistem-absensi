<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|string|unique:employees,nip',
            'nama_pegawai' => 'required|string',
            'email' => 'required|email|unique:employees,email',
        ];
    }
}
