<?php

namespace App\Http\Requests\Cms\Timeline;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimelineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tanggal_mulai_pendaftaran' => 'required|date|before:tanggal_selesai_pendaftaran',

            'tanggal_selesai_pendaftaran' => 'required|date|after:tanggal_mulai_pendaftaran|before_or_equal:tanggal_selesai_administrasi',

            'tanggal_mulai_administrasi' => 'required|date|before:tanggal_selesai_administrasi|after_or_equal:tanggal_mulai_pendaftaran',

            'tanggal_selesai_administrasi' => 'required|date|after_or_equal:tanggal_mulai_administrasi|before_or_equal:tanggal_mulai_assesmen',

            'tanggal_mulai_assesmen' => 'required|date|after_or_equal:tanggal_selesai_administrasi|before_or_equal:tanggal_seleksi_evaluasi_diri',

            'tanggal_seleksi_evaluasi_diri' => 'required|date|after_or_equal:tanggal_mulai_assesmen',
        ];
    }
}
