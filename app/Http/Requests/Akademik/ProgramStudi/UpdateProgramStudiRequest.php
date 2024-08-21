<?php

namespace App\Http\Requests\Akademik\ProgramStudi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramStudiRequest extends FormRequest
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
        $rules = [
            'jenjang_pendidikan_id' => 'required|exists:ref_jenjang_pendidikan,id',
            'kode_dikti' => [
                'nullable',
                'numeric',
                'max_digits:10',
                Rule::unique('ref_prodi', 'kode_dikti')->ignore($this->route('prodi')),
            ],
            'kode' => [
                'required',
                'numeric',
                'digits:5',
                Rule::unique('ref_prodi', 'kode')->ignore($this->route('prodi')),
            ],
            'nama_prodi' => 'required|min:3|max:100|alpha_spaces',
            'singkatan' => [
                'required',
                'min:1',
                'max:10',
                'alpha_spaces',
                Rule::unique('ref_prodi', 'singkatan')->ignore($this->route('prodi')),
            ],
        ];

        if (getRole() != 'admin-fakultas') {
            $rules['fakultas_id'] = 'required|exists:ref_fakultas,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'jenjang_pendidikan_id.required' => 'Jenjang Pendidikan harus diisi',
            'jenjang_pendidikan_id.exists' => 'Jenjang Pendidikan tidak valid',
            'fakultas_id.required' => 'Fakultas harus diisi',
            'fakultas_id.exists' => 'Fakultas tidak valid',
            'kode_dikti.numeric' => 'Kode Dikti harus berupa angka',
            'kode_dikti.max_digits' => 'Kode Dikti maksimal 10 digit',
            'kode_dikti.unique' => 'Kode Dikti sudah terdaftar',
            'kode.required' => 'Kode Prodi harus diisi',
            'kode.numeric' => 'Kode Prodi harus berupa angka',
            'kode.digits' => 'Kode Prodi harus 5 digit',
            'kode.unique' => 'Kode Prodi sudah terdaftar',
            'nama_prodi.required' => 'Nama Prodi harus diisi',
            'nama_prodi.min' => 'Nama Prodi minimal 3 karakter',
            'nama_prodi.max' => 'Nama Prodi maksimal 100 karakter',
            'nama_prodi.alpha_spaces' => 'Nama Prodi harus berupa huruf',
            'singkatan.required' => 'Singkatan Prodi harus diisi',
            'singkatan.min' => 'Singkatan Prodi minimal 1 karakter',
            'singkatan.max' => 'Singkatan Prodi maksimal 10 karakter',
            'singkatan.alpha_spaces' => 'Singkatan Prodi harus berupa huruf',
            'singkatan.unique' => 'Singkatan Prodi sudah terdaftar',
        ];
    }
}
