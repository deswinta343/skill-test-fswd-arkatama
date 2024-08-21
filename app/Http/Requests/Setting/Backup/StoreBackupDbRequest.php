<?php

namespace App\Http\Requests\Setting\Backup;

use Illuminate\Foundation\Http\FormRequest;

class StoreBackupDbRequest extends FormRequest
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
        if (request()->method() == 'PUT') {
            return [
                'name' => 'required|string|max:255|unique:backup_schedules,name,'.$this->backupSchedule->id,
                'frequency' => 'required|in:daily,weekly,monthly',
                'time' => 'required',
                'tables' => 'required|array',
            ];
        }

        return [
            'name' => 'required|string|max:255|unique:backup_schedules,name',
            'frequency' => 'required|in:daily,weekly,monthly',
            'time' => 'required',
            'tables' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Backup harus diisi',
            'name.string' => 'Nama Backup harus berupa string',
            'name.max' => 'Nama Backup maksimal 255 karakter',
            'name.unique' => 'Nama Backup sudah ada',
            'frequency.required' => 'Frekuensi Backup harus diisi',
            'frequency.in' => 'Frekuensi Backup harus diisi daily, weekly, atau monthly',
            'time.required' => 'Waktu Backup harus diisi',
            'tables.required' => 'Tabel Backup harus diisi',
            'tables.array' => 'Tabel Backup harus berupa array',
        ];
    }
}
