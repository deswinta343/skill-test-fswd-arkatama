<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pekerjaan;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiPekerjaanRequest extends FormRequest
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
            'bukti_pekerjaan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_pekerjaan.required' => 'Bukti Pekerjaan harus diisi',
            'bukti_pekerjaan.file' => 'Bukti Pekerjaan harus berupa file',
            'bukti_pekerjaan.mimes' => 'Format file tidak valid',
            'bukti_pekerjaan.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
