<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'required|in:bank_transfer,e_wallet,otc',
            'name' => 'required|string|min:3|alpha_num_spaces_with_alphabet_and_symbol',
            'kode' => 'required|alpha_dash',
            'fee_customer_flat' => 'nullable|numeric|required_if:fee_customer_percent,null',
            'fee_customer_percent' => 'nullable|numeric|required_if:fee_customer_flat,null',
            'minimum_fee' => 'required|numeric',
            'maximum_fee' => 'required|numeric',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'payment_type.required' => 'Tipe Pembayaran harus diisi',
            'payment_type.in' => 'Tipe Pembayaran tidak valid',
            'name.required' => 'Nama Pembayaran harus diisi',
            'name.string' => 'Nama Pembayaran harus berupa string',
            'name.min' => 'Nama Pembayaran minimal 3 karakter',
            'name.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Pembayaran tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'kode.required' => 'Kode Pembayaran harus diisi',
            'kode.alpha_dash' => 'Kode Pembayaran hanya boleh berupa huruf, angka, dan strip',
            'fee_customer_flat.numeric' => 'Biaya Flat untuk Customer harus berupa angka',
            'fee_customer_flat.required_if' => 'Biaya Flat untuk Customer harus diisi jika Biaya Persen untuk Customer kosong',
            'fee_customer_percent.numeric' => 'Biaya Persen untuk Customer harus berupa angka',
            'fee_customer_percent.required_if' => 'Biaya Persen untuk Customer harus diisi jika Biaya Flat untuk Customer kosong',
            'minimum_fee.required' => 'Minimal Biaya harus diisi',
            'minimum_fee.numeric' => 'Minimal Biaya harus berupa angka',
            'maximum_fee.required' => 'Maksimal Biaya harus diisi',
            'maximum_fee.numeric' => 'Maksimal Biaya harus berupa angka',
            'is_active.required' => 'Status harus diisi',
            'is_active.in' => 'Status tidak valid',
        ];
    }
}
