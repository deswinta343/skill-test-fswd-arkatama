<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'payment_gateway.required' => 'Payment Gateway harus diisi',
            'payment_gateway.exists' => 'Payment Gateway tidak valid',
            'is_active.required' => 'Status harus diisi',
            'is_active.in' => 'Status tidak valid',
        ];
    }
}
