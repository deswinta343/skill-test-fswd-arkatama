<?php

namespace App\Http\Requests\Camaba\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

class StoreHistoriPembayaranRequest extends FormRequest
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
            'register_id' => 'required|exists:registers,id',
            'nominal_pembayaran' => 'required',
            'keterangan' => 'nullable',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
