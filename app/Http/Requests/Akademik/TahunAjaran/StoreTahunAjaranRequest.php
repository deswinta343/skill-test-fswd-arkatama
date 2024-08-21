<?php

namespace App\Http\Requests\Akademik\TahunAjaran;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahunAjaranRequest extends FormRequest
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
            'tahun_ajaran' => ['required', 'min:3', 'max:200'],
            'kode_tahun_ajaran' => ['required', 'digits:5', 'unique:ref_tahun_ajaran,kode_tahun_ajaran'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after:tanggal_mulai'],
        ];
    }

    public function messages()
    {
        return [
            'tahun_ajaran.required' => 'Tahun Ajaran harus diisi',
            'tahun_ajaran.min' => 'Tahun Ajaran minimal 3 karakter',
            'tahun_ajaran.max' => 'Tahun Ajaran maksimal 200 karakter',
            'kode_tahun_ajaran.required' => 'Kode Tahun Ajaran harus diisi',
            'kode_tahun_ajaran.digits' => 'Kode Tahun Ajaran harus 5 digit',
            'kode_tahun_ajaran.unique' => 'Kode Tahun Ajaran sudah terdaftar',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berupa tanggal',
            'tanggal_selesai.date' => 'Tanggal Selesai harus berupa tanggal',
            'tanggal_selesai.after' => 'Tanggal Selesai harus setelah Tanggal Mulai',
        ];
    }
}
