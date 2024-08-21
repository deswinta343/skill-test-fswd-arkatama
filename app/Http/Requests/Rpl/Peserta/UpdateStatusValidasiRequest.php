<?php

namespace App\Http\Requests\Rpl\Peserta;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusValidasiRequest extends FormRequest
{
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
        return [
            'keterangan' => $this->is_valid != '1' ? 'required' : '',
            'is_valid' => 'required|in:0,1,2|string',
        ];
    }

    public function messages()
    {
        return [
            'is_valid.required' => 'Status validasi harus diisi',
            'is_valid.in' => 'Status validasi tidak valid',
        ];
    }
}
