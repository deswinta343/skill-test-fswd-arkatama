<?php

namespace App\Http\Requests\Payment\PaymentGateway;

use App\Rules\Payment\PaymentHelperIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentGatewayRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'alpha_num_spaces_with_alphabet_and_symbol',
                'unique:payment_gateways,name,'.$this->route('payment_gateway')->id,
            ],
            'helper' => [
                'required',
                new PaymentHelperIsExistsRule,
                'unique:payment_gateways,helper,'.$this->route('payment_gateway')->id,
            ],
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
            'name.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
        ];
    }
}
