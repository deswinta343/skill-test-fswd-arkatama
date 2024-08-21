<?php

namespace App\Http\Requests\Cms\FAQs;

use Illuminate\Foundation\Http\FormRequest;

class StoreFAQsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'question' => 'required|string|min:3|max:100|alpha_num_spaces_with_alphabet_and_symbol',
            'answer' => 'required|string|max:255|alpha_num_spaces_with_alphabet_and_symbol',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'Pertanyaan harus diisi',
            'question.string' => 'Pertanyaan harus berupa string',
            'question.min' => 'Pertanyaan minimal 3 karakter',
            'question.max' => 'Pertanyaan maksimal 100 karakter',
            'question.alpha_num_spaces_with_alphabet_and_symbol' => 'Pertanyaan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'answer.required' => 'Jawaban harus diisi',
            'answer.string' => 'Jawaban harus berupa string',
            'answer.max' => 'Jawaban maksimal 255 karakter',
            'answer.alpha_num_spaces_with_alphabet_and_symbol' => 'Jawaban tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'is_active.required' => 'Status harus diisi',
            'is_active.in' => 'Status harus 0 atau 1',
        ];
    }
}
