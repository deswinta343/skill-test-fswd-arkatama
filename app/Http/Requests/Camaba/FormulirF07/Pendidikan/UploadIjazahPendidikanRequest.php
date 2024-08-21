<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pendidikan;

use Illuminate\Foundation\Http\FormRequest;

class UploadIjazahPendidikanRequest extends FormRequest
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
            'bukti_ijazah' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_ijazah.required' => 'Bukti Ijazah harus diisi',
            'bukti_ijazah.file' => 'Bukti Ijazah harus berupa file',
            'bukti_ijazah.mimes' => 'Format file tidak valid',
            'bukti_ijazah.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
