<?php

namespace App\Http\Requests\Rpl\MataKuliahSetting;

use Illuminate\Foundation\Http\FormRequest;

class DestroyMataKuliahSettingRequest extends FormRequest
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
            'checked' => 'array',
            'checked.*' => 'exists:matakuliah_settings,id',
        ];
    }

    public function messages()
    {
        return [
            'checked.array' => 'Data yang dikirimkan harus berupa array',
            'checked.*.exists' => 'Data yang dikirimkan harus pengaturan mata kuliah yang valid',
        ];
    }
}
