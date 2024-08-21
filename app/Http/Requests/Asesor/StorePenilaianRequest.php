<?php

namespace App\Http\Requests\Asesor;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nilai_angka' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'nilai_angka.required' => 'Nilai harus diisi',
            'nilai_angka.numeric' => 'Nilai harus berupa angka',
            'nilai_angka.min' => 'Nilai minimal 0',
            'nilai_angka.max' => 'Nilai maksimal 100',
        ];
    }
}
