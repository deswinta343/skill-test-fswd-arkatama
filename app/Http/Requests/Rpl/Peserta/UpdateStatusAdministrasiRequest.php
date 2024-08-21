<?php

namespace App\Http\Requests\Rpl\Peserta;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusAdministrasiRequest extends FormRequest
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
            'status_administrasi' => 'required|in:SUBMITTED,PROPOSED,REVISED,REJECTED,APPROVED|string',
            'keterangan' => $this->status_administrasi !== 'APPROVED' ? 'required' : '',
        ];
    }
}
