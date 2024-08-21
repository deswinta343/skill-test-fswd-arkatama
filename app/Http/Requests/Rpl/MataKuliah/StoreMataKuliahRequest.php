<?php

namespace App\Http\Requests\Matakuliah;

namespace App\Http\Requests\Rpl\MataKuliah;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatakuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'prodi_id' => 'required|exists:ref_prodi,id',
            'kode_mk' => 'required|string|max:10|unique:matakuliah,kode_mk,'.$this->id.',id',
            'nama_mk' => 'required|string|min:3|max:100|alpha_spaces|alpha_num_spaces_with_alphabet_and_symbol',
            'sks_tatap_muka' => 'required|integer',
            'sks_praktek' => 'required|integer',
            'sks_praktek_lapangan' => 'required|integer',
            'sks_simulasi' => 'required|integer',
            'sks_praktikum' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'kode_mk.required' => 'Kode Mata Kuliah harus diisi',
            'kode_mk.string' => 'Kode Mata Kuliah harus berupa string',
            'kode_mk.max' => 'Kode Mata Kuliah maksimal 10 karakter',
            'kode_mk.unique' => 'Kode Mata Kuliah sudah ada',
            'nama_mk.required' => 'Nama Mata Kuliah harus diisi',
            'nama_mk.string' => 'Nama Mata Kuliah harus berupa string',
            'nama_mk.min' => 'Nama Mata Kuliah minimal 3 karakter',
            'nama_mk.max' => 'Nama Mata Kuliah maksimal 100 karakter',
            'nama_mk.alpha_spaces' => 'Nama Mata Kuliah hanya boleh berisi huruf dan spasi',
            'nama_mk.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Mata Kuliah tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'sks_tatap_muka.required' => 'SKS Tatap Muka harus diisi',
            'sks_tatap_muka.integer' => 'SKS Tatap Muka harus berupa angka',
            'sks_praktek.required' => 'SKS Praktek harus diisi',
            'sks_praktek.integer' => 'SKS Praktek harus berupa angka',
            'sks_praktek_lapangan.required' => 'SKS Praktek Lapangan harus diisi',
            'sks_praktek_lapangan.integer' => 'SKS Praktek Lapangan harus berupa angka',
            'sks_simulasi.required' => 'SKS Simulasi harus diisi',
            'sks_simulasi.integer' => 'SKS Simulasi harus berupa angka',
            'sks_praktikum.required' => 'SKS Praktikum harus diisi',
            'sks_praktikum.integer' => 'SKS Praktikum harus berupa angka',
        ];
    }
}
