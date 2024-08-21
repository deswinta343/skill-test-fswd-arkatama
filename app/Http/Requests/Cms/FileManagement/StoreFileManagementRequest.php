<?php

namespace App\Http\Requests\Cms\FileManagement;

use App\Traits\JsonValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileManagementRequest extends FormRequest
{
    use JsonValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }

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
        $allowedFormat = getSetting('allowed_file_types', 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png');
        $maxFileSize = getSetting('max_file_size', 10000);

        return [
            'user_id' => 'required|exists:users,id',
            'keterangan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
            'file' => $this->file('file') ? ['required', 'file', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize] : ['required'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User harus diisi',
            'user_id.exists' => 'User tidak ditemukan',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.min' => 'Keterangan minimal 3 karakter',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'keterangan.alpha_num_spaces_with_alphabet_and_symbol' => 'Keterangan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'file.required' => 'File harus diisi',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'File harus berupa file dengan format pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png',
            'file.max' => 'File maksimal 10MB',
        ];
    }
}
