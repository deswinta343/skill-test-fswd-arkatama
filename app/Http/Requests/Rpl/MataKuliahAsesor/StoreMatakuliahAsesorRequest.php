<?php

namespace App\Http\Requests\Rpl\MataKuliahAsesor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMatakuliahAsesorRequest extends FormRequest
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
            'asesor_id' => [
                'required', 'exists:asesor,id',
                'exists:asesor,id', Rule::unique('matakuliah_asesor')->where(function ($query) {
                    return $query->where('asesor_id', $this->asesor_id)->where('matkul_id', $this->matkul_id);
                }),
            ],
            'matkul_id' => [
                'required',
                'exists:matakuliah,id', Rule::unique('matakuliah_asesor')->where(function ($query) {
                    return $query->where('asesor_id', $this->asesor_id)->where('matkul_id', $this->matkul_id);
                }),
            ],

        ];
    }

    public function messages()
    {
        return [
            'asesor_id.required' => 'Asesor harus diisi',
            'asesor_id.exists' => 'Asesor tidak valid',
            'matkul_id.required' => 'Mata Kuliah harus diisi',
            'matkul_id.exists' => 'Mata Kuliah tidak valid',
            'asesor_id.unique' => 'Asesor sudah memiliki mata kuliah ini',
            'matkul_id.unique' => 'Matkul sudah diambil oleh asesor ini',
        ];
    }
}
