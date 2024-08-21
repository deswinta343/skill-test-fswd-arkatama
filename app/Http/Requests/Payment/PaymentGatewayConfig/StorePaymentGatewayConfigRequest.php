<?php

namespace App\Http\Requests\Payment\PaymentGatewayConfig;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentGatewayConfigRequest extends FormRequest
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
            'key' => [
                'required',
                'string',
                'alpha_dash_only',
                'min:3',
                'max:100',
                'unique:payment_gateway_configs,key,NULL,id,payment_gateway_id,'.$this->route('payment_gateway')->id,
            ],
            'value' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
