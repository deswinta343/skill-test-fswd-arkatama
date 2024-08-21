<?php

namespace App\Http\Requests\Master\JenjangPendidikan;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreJenjangPendidikan extends FormRequest
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
            'kode' => 'required|string|unique:ref_jenjang_pendidikan,kode|regex:/^[1-4a-zA-Z\s]+$/',
            'nama' => 'required|string|regex:/^[1-4a-zA-Z\s]+$/',
        ];
    }

    public function messages()
    {
        return [
            'kode.required' => 'Kode Jenjang Pendidikan harus diisi',
            'kode.string' => 'Kode Jenjang Pendidikan harus berupa string',
            'kode.unique' => 'Kode Jenjang Pendidikan sudah ada',
            'kode.regex' => 'Kode Jenjang Pendidikan hanya boleh berisi huruf, angka, dan spasi',
            'nama.required' => 'Nama Jenjang Pendidikan harus diisi',
            'nama.string' => 'Nama Jenjang Pendidikan harus berupa string',
            'nama.regex' => 'Nama Jenjang Pendidikan hanya boleh berisi huruf, angka, dan spasi',
        ];
    }
}
