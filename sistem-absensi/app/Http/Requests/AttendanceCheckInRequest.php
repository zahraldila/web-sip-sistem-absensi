<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skema_kerja' => 'required|in:WFO,WFH,WFC',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_selfie' => 'nullable|file|image|max:5120',
            'nfc_serial' => 'nullable|string',
        ];
    }
}
