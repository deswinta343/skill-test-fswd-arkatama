<?php

namespace App\Http\Requests\Akademik\Fakultas;

use Illuminate\Foundation\Http\FormRequest;

class StoreFakultasRequest extends FormRequest
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
            'kode' => 'required|alpha_num|alpha_num_spaces_with_alphabet|alpha_num_spaces_with_number|min:5|max:5|unique:ref_fakultas,kode,'.$this->fakultas?->id,
            'nama_fakultas' => 'required|min:3|max:100|alpha_spaces',
            'singkatan' => 'required|min:2|max:10|alpha_spaces|unique:ref_fakultas,singkatan,'.$this->fakultas?->id,
            'nama_inggris' => 'nullable|min:3|max:100|alpha_spaces',
            'alamat' => 'nullable|min:3|max:255',
            'kota' => 'nullable|min:3|max:100|alpha_spaces',
            'telepon' => 'nullable|starts_with:0,62|digits_between:7,20',
            'fax' => 'nullable|starts_with:0,62|digits_between:7,20',
            'email' => 'nullable|email|unique:ref_fakultas,email,'.$this->fakultas?->id,
            'website' => 'nullable|url',
            'description' => 'nullable|min:3|max:255',
        ];
    }

    public function messages()
    {
        return [
            'kode.required' => 'Kode Fakultas harus diisi',
            'kode.unique' => 'Kode Fakultas sudah terdaftar',
            'kode.alpha_num' => 'Kode Fakultas harus berupa huruf dan angka, tanpa spasi',
            'kode.alpha_num_spaces_with_alphabet' => 'Kode Fakultas tidak boleh hanya angka, harus mengandung huruf juga',
            'kode.alpha_num_spaces_with_number' => 'Kode Fakultas tidak boleh hanya huruf, harus mengandung angka juga',
            'kode.min' => 'Kode Fakultas minimal 5 karakter',
            'kode.max' => 'Kode Fakultas maksimal 5 karakter',
            'nama_fakultas.required' => 'Nama Fakultas harus diisi',
            'nama_fakultas.min' => 'Nama Fakultas minimal 3 karakter',
            'nama_fakultas.max' => 'Nama Fakultas maksimal 100 karakter',
            'nama_fakultas.alpha_spaces' => 'Nama Fakultas harus berupa huruf',
            'singkatan.required' => 'Singkatan Fakultas harus diisi',
            'singkatan.min' => 'Singkatan Fakultas minimal 2 karakter',
            'singkatan.max' => 'Singkatan Fakultas maksimal 10 karakter',
            'singkatan.alpha_spaces' => 'Singkatan Fakultas harus berupa huruf',
            'singkatan.unique' => 'Singkatan Fakultas sudah terdaftar',
            'nama_inggris.min' => 'Nama Fakultas (Inggris) minimal 3 karakter',
            'nama_inggris.max' => 'Nama Fakultas (Inggris) maksimal 100 karakter',
            'nama_inggris.alpha_spaces' => 'Nama Fakultas (Inggris) harus berupa huruf',
            'alamat.min' => 'Alamat minimal 3 karakter',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'kota.min' => 'Kota minimal 3 karakter',
            'kota.max' => 'Kota maksimal 100 karakter',
            'kota.alpha_spaces' => 'Kota harus berupa huruf',
            'telepon.digits_between' => 'Telepon minimal 7 dan maksimal 20 digit',
            'telepon.starts_with' => 'Telepon harus diawali dengan 0 atau 62',
            'fax.digits_between' => 'Fax minimal 7 dan maksimal 20 digit',
            'fax.starts_with' => 'Fax harus diawali dengan 0 atau 62',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'website.url' => 'Website tidak valid',
            'description.min' => 'Deskripsi minimal 3 karakter',
            'description.max' => 'Deskripsi maksimal 255 karakter',
        ];
    }
}
