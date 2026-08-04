<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'no_handphone' => 'nullable|string',
            'foto_profile' => 'nullable|image|max:5120',
        ];
    }
}
