<?php

namespace App\Http\Requests\Camaba\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentRequest extends FormRequest
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
            'bukti_pembayaran' => $this->file('bukti_pembayaran') ? 'required|file|mimes:jpeg,jpg,png,pdf|max:2048' : 'required',
            'keterangan' => 'nullable|alpha_num_spaces_with_alphabet_and_symbol|max:255',
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
            'bukti_pembayaran.required' => 'Bukti Pembayaran wajib diisi',
            'bukti_pembayaran.file' => 'Bukti Pembayaran harus berupa file',
            'bukti_pembayaran.mimes' => 'Bukti Pembayaran harus berupa file dengan format: jpeg, jpg, png, pdf',
            'bukti_pembayaran.max' => 'Bukti Pembayaran maksimal 2MB',
            'keterangan.alpha_num_spaces_with_alphabet_and_symbol' => 'Keterangan tidak boleh hanya angka atau simbol, minimal harus ada satu huruf',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
        ];
    }
}
