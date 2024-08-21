<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pelatihan;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiPelatihanRequest extends FormRequest
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
            'bukti_pelatihan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_pelatihan.required' => 'Bukti Pelatihan harus diisi',
            'bukti_pelatihan.file' => 'Bukti Pelatihan harus berupa file',
            'bukti_pelatihan.mimes' => 'Format file tidak valid',
            'bukti_pelatihan.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
