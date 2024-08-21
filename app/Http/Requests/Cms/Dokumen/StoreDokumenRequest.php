<?php

namespace App\Http\Requests\CMS\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenRequest extends FormRequest
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
        $allowedFormat = getSetting('document_allowed_file_types', 'pdf,doc,docx');
        $maxFileSize = getSetting('document_max_file_size', 10000);
        if ($this->method() == 'PUT') {
            return [
                'nama' => 'required|min:3|max:255|alpha_num_spaces_with_alphabet_and_symbol',
                'keterangan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
                'file' => $this->file('file') ? ['required', 'file', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize] : ['required'],
            ];
        } else {
            return [
                'nama' => 'required|min:3|max:255|alpha_num_spaces_with_alphabet_and_symbol',
                'keterangan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
                'file' => ['required', 'file', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize],
            ];
        }
    }

    public function messages()
    {
        return [
            'nama.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'keterangan.alpha_num_spaces_with_alphabet_and_symbol' => 'Keterangan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
        ];
    }
}
