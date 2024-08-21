<?php

namespace App\Http\Requests\Cms\Slideshow;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideshowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
            'description' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol',
            'is_active' => 'required|in:0,1',
        ];
    }

    //message in indonesia
    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 100 karakter',
            'name.regex' => 'Nama hanya boleh huruf dan angka',
            'name.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'description.required' => 'Deskripsi harus diisi',
            'description.string' => 'Deskripsi harus berupa string',
            'description.max' => 'Deskripsi maksimal 255 karakter',
            'description.regex' => 'Deskripsi hanya boleh huruf dan angka',
            'description.alpha_num_spaces_with_alphabet_and_symbol' => 'Deskripsi tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'is_active.required' => 'Status harus diisi',
            'is_active.in' => 'Status harus 0 atau 1',
        ];
    }
}
