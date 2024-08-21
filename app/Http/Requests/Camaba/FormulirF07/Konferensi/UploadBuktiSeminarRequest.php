<?php

namespace App\Http\Requests\Camaba\FormulirF07\Konferensi;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiSeminarRequest extends FormRequest
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
            'bukti_seminar' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'bukti_seminar.required' => 'Bukti Seminar harus diisi',
            'bukti_seminar.file' => 'Bukti Seminar harus berupa file',
            'bukti_seminar.mimes' => 'Format file tidak valid',
            'bukti_seminar.max' => 'Ukuran file maksimal 2048 KB',
        ];
    }
}
