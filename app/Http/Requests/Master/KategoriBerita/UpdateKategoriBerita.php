<?php

namespace App\Http\Requests\Master\KategoriBerita;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriBerita extends FormRequest
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
        $kategoriBerita = $this->route('kategori_berita');

        return [
            'name' => 'required|regex:/^[a-zA-Z0-9\s]+$/|alpha_num_spaces_with_alphabet_and_symbol|unique:kategori_news,name,'.$kategoriBerita,
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.alpha_spaces' => 'Nama hanya boleh huruf dan spasi',
            'name.unique' => 'Nama sudah ada',
            'description.string' => 'Deskripsi harus berupa string',
            'description.alpha_spaces' => 'Deskripsi hanya boleh huruf dan spasi',
            'name.regex' => 'Nama hanya boleh huruf dan angka',
            'name.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'description.regex' => 'Deskripsi hanya boleh huruf dan angka',
            'description.alpha_num_spaces_with_alphabet_and_symbol' => 'Deskripsi tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
        ];
    }
}
