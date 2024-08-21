<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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

        //  'nullable|max:200|unique:menus,module,' . $this->menu?->id
        return [
            'name' => 'required|max:200',
            'module' => [
                'required',
                'max:200',
                'unique:menus,module,'.$this->menu?->id,
            ],
            'slug' => 'required|max:200|unique:menus,slug,'.$this->menu?->id,
            'url' => 'nullable|max:200|unique:menus,url,'.$this->menu?->id,
            'icon' => 'nullable|max:200',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'type' => 'required|in:menu,group,divider',
            'target' => 'required|in:_self,_blank',
            'location' => 'required|in:sidebar,topbar',
            'is_active' => 'required|in:0,1',
        ];
    }

    //translate ke indonesia
    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 200 karakter',
            'module.required' => 'Module harus diisi',
            'module.max' => 'Module maksimal 200 karakter',
            'module.unique' => 'Module sudah ada',
            'slug.required' => 'Slug harus diisi',
            'slug.max' => 'Slug maksimal 200 karakter',
            'slug.unique' => 'Slug sudah ada',
            'url.max' => 'Url maksimal 200 karakter',
            'url.unique' => 'Url sudah ada',
            'icon.max' => 'Icon maksimal 200 karakter',
            'parent_id.exists' => 'Parent id tidak valid',
            'order.required' => 'Order harus diisi',
            'order.integer' => 'Order harus berupa angka',
            'type.required' => 'Type harus diisi',
            'type.in' => 'Type tidak valid',
            'target.required' => 'Target harus diisi',
            'target.in' => 'Target tidak valid',
            'location.required' => 'Location harus diisi',
            'location.in' => 'Location tidak valid',
            'is_active.required' => 'Is active harus diisi',
            'is_active.in' => 'Is active tidak valid',
        ];
    }
}
