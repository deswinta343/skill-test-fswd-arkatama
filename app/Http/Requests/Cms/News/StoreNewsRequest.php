<?php

namespace App\Http\Requests\Cms\News;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
        $allowedFormat = getSetting('document_allowed_file_types', 'jpg,jpeg,png');
        $maxFileSize = getSetting('document_max_file_size', 5000);
        if ($this->method() == 'PUT') {
            return [
                'title' => 'required|alpha_num_spaces_with_alphabet_and_symbol',
                'description' => 'required|alpha_num_spaces_with_alphabet_and_symbol_html',
                'news_kategori_id' => 'required',
                'thumbnail' => $this->file('thumbnail') ? ['required', 'image', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize] : ['required'],
            ];
        } else {
            return [
                'title' => 'required|alpha_num_spaces_with_alphabet_and_symbol',
                'description' => 'required|alpha_num_spaces_with_alphabet_and_symbol_html',
                'news_kategori_id' => 'required',
                'thumbnail' => ['required', 'image', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize],
            ];
        }

        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.alpha_num_spaces_with_alphabet_and_symbol' => 'Judul tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'description.required' => 'Deskripsi harus diisi',
            'description.alpha_num_spaces_with_alphabet_and_symbol_html' => 'Deskripsi tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'description.regex' => 'Deskripsi hanya boleh huruf dan angka',
            'news_kategori_id.required' => 'Kategori harus diisi',
            'thumbnail.required' => 'Gambar harus diisi',
            'thumbnail.image' => 'Gambar harus berupa gambar',
            'thumbnail.mimes' => 'Format gambar tidak valid',
            'thumbnail.max' => 'Ukuran gambar maksimal '.getSetting('document_max_file_size', 5000).' KB',
        ];
    }
}
