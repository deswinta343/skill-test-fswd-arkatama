<?php

namespace App\Http\Requests\Rpl\MataKuliahSetting;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataKuliahSettingRequest extends FormRequest
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
            'checked' => 'array',
            'unchecked' => 'array',
            'checked.*' => 'exists:matakuliah,id',
            'unchecked.*' => 'exists:matakuliah,id',
        ];
    }

    public function messages()
    {
        return [
            'checked.array' => 'Data yang dikirimkan harus berupa array',
            'unchecked.array' => 'Data yang dikirimkan harus berupa array',
            'checked.*.exists' => 'Data yang dikirimkan harus mata kuliah yang valid',
            'unchecked.*.exists' => 'Data yang dikirimkan harus mata kuliah yang valid',
        ];
    }
}
