<?php

namespace App\Http\Requests\Cms\Slideshow;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $allowedFormat = getSetting('document_allowed_file_types', 'jpg,jpeg,png');
        $maxFileSize = getSetting('document_max_file_size', 5000);
        if ($this->method() == 'PUT') {
            return [
                'title' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
                'caption' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
                'order' => 'required|integer',
                'image' => $this->file('image') ? ['required', 'image', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize] : ['required'],
            ];
        } else {
            return [
                'title' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
                'caption' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
                'order' => 'required|integer',
                'image' => ['required', 'image', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize],
            ];
        }
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.string' => 'Judul harus berupa string',
            'title.min' => 'Judul minimal 3 karakter',
            'title.max' => 'Judul maksimal 100 karakter',
            'title.regex' => 'Judul hanya boleh huruf dan angka',
            'title.alpha_num_spaces_with_alphabet_and_symbol' => 'Judul tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'caption.required' => 'Caption harus diisi',
            'caption.string' => 'Caption harus berupa string',
            'caption.max' => 'Caption maksimal 255 karakter',
            'caption.regex' => 'Caption hanya boleh huruf dan angka',
            'caption.alpha_num_spaces_with_alphabet_and_symbol' => 'Caption tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'order.required' => 'Urutan harus diisi',
            'order.integer' => 'Urutan harus berupa angka',
            'image.required' => 'Gambar harus diisi',
            'image.image' => 'Gambar harus berupa gambar',
            'image.mimes' => 'Format gambar tidak valid',
            'image.max' => 'Ukuran gambar maksimal '.getSetting('document_max_file_size', 5000).' KB',
        ];
    }
}
