<?php

namespace App\Http\Requests\Rpl\Pendaftar;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendaftarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['nullable', 'string', 'max:255', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'password.string' => 'Password harus berupa huruf',
            'password.max' => 'Password maksimal 255 karakter',
            'password.confirmed' => 'Password tidak cocok',
        ];
    }
}
