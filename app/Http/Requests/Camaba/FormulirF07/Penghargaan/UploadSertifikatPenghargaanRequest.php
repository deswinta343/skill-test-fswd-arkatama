<?php

namespace App\Http\Requests\Camaba\FormulirF07\Penghargaan;

use Illuminate\Foundation\Http\FormRequest;

class UploadSertifikatPenghargaanRequest extends FormRequest
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
            'bukti_penghargaan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_penghargaan.required' => 'Bukti Penghargaan harus diisi',
            'bukti_penghargaan.file' => 'Bukti Penghargaan harus berupa file',
            'bukti_penghargaan.mimes' => 'Format file tidak valid',
            'bukti_penghargaan.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
