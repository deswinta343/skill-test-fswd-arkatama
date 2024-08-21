<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pekerjaan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePekerjaanRequest extends FormRequest
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
            'nama_perusahaan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
            'jabatan' => 'required|min:3|max:255',
            'tanggal_masuk' => ['required', 'date', 'before_or_equal:tanggal_keluar'],
            'tanggal_keluar' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'alamat_perusahaan' => ['required', 'min:3', 'max:200'],
            'uraian_pekerjaan' => ['required', 'min:3', 'max:255'],
            'bukti_pekerjaan' => $this->file('bukti_pekerjaan') ? ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'] : 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_perusahaan.required' => 'Nama Perusahaan harus diisi',
            'nama_perusahaan.min' => 'Nama Perusahaan minimal 3 karakter',
            'nama_perusahaan.max' => 'Nama Perusahaan maksimal 255 karakter',
            'nama_perusahaan.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Perusahaan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'jabatan.required' => 'Jabatan harus diisi',
            'jabatan.min' => 'Jabatan minimal 3 karakter',
            'jabatan.max' => 'Jabatan maksimal 255 karakter',
            'tanggal_masuk.required' => 'Tanggal Masuk harus diisi',
            'tanggal_masuk.date' => 'Tanggal Masuk harus berupa tanggal',
            'tanggal_masuk.before_or_equal' => 'Tanggal Masuk harus sebelum atau sama dengan Tanggal Keluar',
            'tanggal_keluar.required' => 'Tanggal Keluar harus diisi',
            'tanggal_keluar.date' => 'Tanggal Keluar harus berupa tanggal',
            'tanggal_keluar.after_or_equal' => 'Tanggal Keluar harus setelah atau sama dengan Tanggal Masuk',
            'alamat_perusahaan.required' => 'Alamat Perusahaan harus diisi',
            'alamat_perusahaan.min' => 'Alamat Perusahaan minimal 3 karakter',
            'alamat_perusahaan.max' => 'Alamat Perusahaan maksimal 200 karakter',
            'uraian_pekerjaan.required' => 'Uraian Pekerjaan harus diisi',
            'uraian_pekerjaan.min' => 'Uraian Pekerjaan minimal 3 karakter',
            'uraian_pekerjaan.max' => 'Uraian Pekerjaan maksimal 255 karakter',
            'bukti_pekerjaan.required' => 'Bukti Pekerjaan harus diisi',
            'bukti_pekerjaan.file' => 'Bukti Pekerjaan harus berupa file',
            'bukti_pekerjaan.max' => 'Ukuran file maksimal 2048 KB',
            'bukti_pekerjaan.mimes' => 'Format file tidak valid',
        ];
    }
}
