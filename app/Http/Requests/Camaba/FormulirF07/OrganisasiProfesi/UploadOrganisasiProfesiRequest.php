<?php

namespace App\Http\Requests\Camaba\FormulirF07\OrganisasiProfesi;

use Illuminate\Foundation\Http\FormRequest;

class UploadOrganisasiProfesiRequest extends FormRequest
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
            'bukti_organisasi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_organisasi.required' => 'Bukti Organisasi harus diisi',
            'bukti_organisasi.file' => 'Bukti Organisasi harus berupa file',
            'bukti_organisasi.mimes' => 'Format file tidak valid',
            'bukti_organisasi.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
