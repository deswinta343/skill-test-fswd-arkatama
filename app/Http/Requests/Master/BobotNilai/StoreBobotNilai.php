<?php

namespace App\Http\Requests\Master\BobotNilai;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreBobotNilai extends FormRequest
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
            'nilai_min' => 'required|numeric',
            'nilai_max' => 'required|numeric|gt:nilai_min',
            'nilai_huruf' => 'required|string|max:2|unique:ref_bobot_nilai,nilai_huruf',
            // 'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nilai_min.required' => 'Nilai Min harus diisi',
            'nilai_min.numeric' => 'Nilai Min harus berupa angka',
            'nilai_max.gt' => 'Nilai Max harus lebih besar dari Nilai Min',
            'nilai_max.required' => 'Nilai Max harus diisi',
            'nilai_max.numeric' => 'Nilai Max harus berupa angka',
            'nilai_huruf.required' => 'Nilai Huruf harus diisi',
            'nilai_huruf.string' => 'Nilai Huruf harus berupa huruf',
            'nilai_huruf.max' => 'Nilai Huruf maksimal 2 karakter',
            'nilai_huruf.unique' => 'Nilai Huruf sudah ada',
            // 'is_active.required' => 'Status harus diisi',
            // 'is_active.boolean' => 'Status harus berupa boolean',
        ];
    }
}
