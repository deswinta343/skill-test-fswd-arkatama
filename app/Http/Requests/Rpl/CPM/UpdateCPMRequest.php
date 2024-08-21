<?php

namespace App\Http\Requests\Rpl\CPM;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCPMRequest extends FormRequest
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
            'cpm' => 'required|min:3|max:255|alpha_num_spaces_with_alphabet_and_symbol',
            'keterangan' => 'required|alpha_num_spaces_with_alphabet_and_symbol',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'cpm.required' => 'CPM harus diisi',
            'cpm.min' => 'CPM minimal 3 karakter',
            'cpm.max' => 'CPM maksimal 255 karakter',
            'cpm.regex' => 'CPM hanya boleh huruf dan angka',
            'cpm.alpha_num_spaces_with_alphabet_and_symbol' => 'CPM tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.regex' => 'Keterangan hanya boleh huruf dan angka',
            'keterangan.alpha_num_spaces_with_alphabet_and_symbol' => 'Keterangan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'is_active.required' => 'Status harus diisi',
            'is_active.in' => 'Status harus berupa boolean',
        ];
    }
}
