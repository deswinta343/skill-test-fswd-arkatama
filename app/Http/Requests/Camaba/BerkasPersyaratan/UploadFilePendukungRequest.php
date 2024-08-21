<?php

namespace App\Http\Requests\Camaba\BerkasPersyaratan;

use Illuminate\Foundation\Http\FormRequest;

class UploadFilePendukungRequest extends FormRequest
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
            'file_pendukung' => request()->file('file_pendukung') ? ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:2048'] : 'required',
            // 'keterangan' => 'required|string|max:255',
            'berkas_persyaratan_id' => 'nullable|exists:formulir_berkas_persyaratan,id',
            'persyaratan_id' => request()->query('berkas_persyaratan_id') ? 'required|exists:ref_persyaratan,id' : '',
        ];
    }

    public function messages()
    {
        return [
            'file_pendukung.required' => 'File Pendukung harus diisi',
            'file_pendukung.file' => 'File Pendukung harus berupa file',
            'file_pendukung.mimes' => 'File yang diterima hanya berformat pdf, png, jpg, dan jpeg',
            'file_pendukung.max' => 'Ukuran file maksimal 2048 KB',
            // 'keterangan.required' => 'Keterangan harus diisi',
            // 'keterangan.string' => 'Keterangan harus berupa string',
            // 'keterangan.max' => 'Keterangan maksimal 255 karakter'
        ];
    }
}
