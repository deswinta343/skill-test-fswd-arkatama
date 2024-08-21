<?php

namespace App\Http\Requests\Rpl\SyaratPendaftaran;

use Illuminate\Foundation\Http\FormRequest;

class StoreSyaratPendaftaranRequest extends FormRequest
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
            'prodi_id' => 'required|exists:ref_prodi,id',
            // 'tahun_ajaran_id' => 'required|exists:ref_tahun_ajaran,id',
            'persyaratan' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
            'is_active' => 'nullable|boolean',
            'keterangan' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
            'dokumen_template' => request()->file('dokumen_template') ? 'file|mimes:pdf,doc,docx|max:2048' : 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'tahun_ajaran_id.required' => 'Tahun Ajaran harus diisi',
            'tahun_ajaran_id.exists' => 'Tahun Ajaran tidak valid',
            'persyaratan.required' => 'Persyaratan harus diisi',
            'persyaratan.string' => 'Persyaratan harus berupa string',
            'persyaratan.min' => 'Persyaratan minimal 3 karakter',
            'persyaratan.max' => 'Persyaratan maksimal 255 karakter',
            'persyaratan.regex' => 'Persyaratan hanya boleh huruf dan angka',
            'persyaratan.alpha_num_spaces_with_alphabet_and_symbol' => 'Persyaratan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'is_active.required' => 'Status harus diisi',
            'is_active.boolean' => 'Status harus berupa boolean',
            'keterangan.string' => 'Keterangan harus berupa string',
            'keterangan.regex' => 'Keterangan hanya boleh huruf dan angka',
            'keterangan.alpha_num_spaces_with_alphabet_and_symbol' => 'Keterangan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'dokumen_template.file' => 'Dokumen Template harus berupa file',
            'dokumen_template.mimes' => 'Format dokumen tidak valid',
            'dokumen_template.max' => 'Ukuran dokumen maksimal 2048 KB',
        ];
    }
}
