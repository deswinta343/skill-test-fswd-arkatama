<?php

namespace App\Http\Requests\Master\Provinsi;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreProvinsi extends FormRequest
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
            'kode' => 'required|regex:/^[0-9-]+$/|unique:ref_provinsi,kode',
            'nama' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'kode.required' => 'Kode Provinsi harus diisi',
            'kode.unique' => 'Kode Provinsi sudah ada',
            'kode.regex' => 'Kode Provinsi hanya boleh berisi angka',
            'nama.required' => 'Nama Provinsi harus diisi',
            'nama.string' => 'Nama Provinsi harus berupa string',
        ];
    }
}
