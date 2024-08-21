<?php

namespace App\Http\Requests\Setting\SiteSetting;

use App\Traits\JsonValidationResponse;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Http\FormRequest;

class SiteSettingStoreRequest extends FormRequest
{
    use JsonValidationResponse, SoftDeletes;

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            'type' => 'required|in:site-identity,hero,profile',
            'name' => [
                'required',
                'min:3',
                'max:200',
                'alpha_dash_only',
                'unique:site_settings,name,'.$this->site_setting?->id,
            ],
            'value' => 'required|min:1|max:255',
            'description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Tipe harus diisi',
            'type.in' => 'Tipe tidak valid',
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 200 karakter',
            'name.alpha_dash_only' => 'Nama harus berupa alfanumerik dengan tanda hubung',
            'name.unique' => 'Nama sudah ada',
            'value.required' => 'Nilai harus diisi',
            'value.min' => 'Nilai minimal 1 karakter',
            'value.max' => 'Nilai maksimal 255 karakter',
        ];
    }
}
