<?php

namespace App\Http\Requests\Master\Kabupaten;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreKabupaten extends FormRequest
{
    use JsonValidationResponse;

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
            'provinsi_id' => 'required|numeric',
            'kode' => 'required|unique:ref_kabupaten_kota,kode|regex:/^[0-9-.]+$/',
            'nama' => 'required|string|alpha_spaces',
        ];
    }

    public function messages(): array
    {
        return [
            'provinsi_id.required' => 'Provinsi harus diisi',
            'provinsi_id.numeric' => 'Provinsi harus berupa angka',
            'kode.required' => 'Kode Kabupaten/Kota harus diisi',
            'kode.unique' => 'Kode Kabupaten/Kota sudah ada',
            'kode.regex' => 'Kode Kabupaten/Kota hanya boleh berisi angka dan titik',
            'nama.required' => 'Nama Kabupaten/Kota harus diisi',
            'nama.string' => 'Nama Kabupaten/Kota harus berupa string',
            'nama.alpha_spaces' => 'Nama Kabupaten/Kota hanya boleh berisi huruf dan spasi',
        ];
    }
}
