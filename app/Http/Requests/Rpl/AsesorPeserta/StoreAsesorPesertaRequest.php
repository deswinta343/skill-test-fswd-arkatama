<?php

namespace App\Http\Requests\Rpl\AsesorPeserta;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsesorPesertaRequest extends FormRequest
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
            // 'matkul_id' => 'required|exists:matakuliah,id',
            // 'formulir_id' => 'required|exists:formulirs,id',
            // 'matkul_asesor_akademisi_id' => 'nullable|exists:asesor,id|required_without_all:matkul_asesor_praktisi_id',
            // 'matkul_asesor_praktisi_id' => 'nullable|exists:asesor,id|required_without_all:matkul_asesor_akademisi_id',
            'matkul_asesor_id' => 'required|exists:matakuliah_asesor,id',
        ];
    }

    public function messages()
    {
        return [
            'matkul_id.required' => 'Mata Kuliah harus diisi',
            'matkul_id.exists' => 'Mata Kuliah tidak valid',
            'formulir_id.required' => 'Formulir harus diisi',
            'formulir_id.exists' => 'Formulir tidak valid',
            'matkul_asesor_akademisi_id.exists' => 'Asesor Akademisi tidak valid',
            'matkul_asesor_praktisi_id.exists' => 'Asesor Praktisi tidak valid',
            'matkul_asesor_akademisi_id.required_without_all' => 'Asesor Akademisi atau Asesor Praktisi harus diisi',
            'matkul_asesor_praktisi_id.required_without_all' => 'Asesor Praktisi atau Asesor Akademisi harus diisi',
        ];
    }
}
