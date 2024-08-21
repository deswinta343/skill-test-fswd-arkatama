<?php

namespace App\Http\Requests\Camaba\EvaluasiDiri;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
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
            'file_pendukung' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'file_pendukung.required' => 'File Pendukung harus diisi',
            'file_pendukung.file' => 'File Pendukung harus berupa file',
            'file_pendukung.mimes' => 'Format file tidak valid',
            'file_pendukung.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
