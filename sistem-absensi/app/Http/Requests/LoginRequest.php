<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Pesan error custom (bahasa Indonesia).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Username atau Email wajib diisi.',
            'email.max' => 'Username atau Email tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}
