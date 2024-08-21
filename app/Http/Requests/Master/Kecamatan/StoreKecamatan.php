<?php

namespace App\Http\Requests\Master\Kecamatan;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatan extends FormRequest
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
            'kabupaten_kota_id' => 'required|numeric',
            'kode' => 'required|regex:/^[0-9-.]+$/|unique:ref_kecamatan,kode',
            'nama' => 'required|string|alpha_spaces',
        ];
    }

    public function messages()
    {
        return [
            'kabupaten_kota_id.required' => 'Kabupaten/Kota harus diisi',
            'kabupaten_kota_id.numeric' => 'Kabupaten/Kota harus berupa angka',
            'kode.required' => 'Kode Kecamatan harus diisi',
            'kode.unique' => 'Kode Kecamatan sudah ada',
            'kode.regex' => 'Kode Kecamatan hanya boleh berisi angka dan titik',
            'nama.required' => 'Nama Kecamatan harus diisi',
            'nama.string' => 'Nama Kecamatan harus berupa string',
            'nama.alpha_spaces' => 'Nama Kecamatan hanya boleh berisi huruf dan spasi',
        ];
    }
}
