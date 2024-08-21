<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'prodi_id' => 'required|exists:ref_prodi,id',
            'is_free_ukt' => 'required|in:0,1',
            'biaya_ukt' => 'required',
            'biaya_pendaftaran' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'is_free_ukt.required' => 'Status UKT harus diisi',
            'is_free_ukt.in' => 'Status UKT tidak valid',
            'biaya_ukt.required' => 'Biaya UKT harus diisi',
            'biaya_pendaftaran.required' => 'Biaya Pendaftaran harus diisi',
        ];
    }
}
