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
            'email' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $val = trim((string) $value);
                    if ($val === '') {
                        return;
                    }

                    if (str_contains($val, '@')) {
                        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                            $fail('Format email tidak valid.');
                        }
                    } else {
                        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $val)) {
                            $fail('Format username tidak valid.');
                        }
                    }
                },
            ],
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
