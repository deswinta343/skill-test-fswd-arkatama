<?php

namespace App\Http\Requests\Rpl\ProdiPilihan;

use App\Models\Akademik\TahunAjaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdiPilihanRequest extends FormRequest
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
        $prodiPilihan = $this->route('prodi_pilihan');

        return [
            'prodi_id' => [
                'required',
                'exists:ref_prodi,id',
                Rule::unique('prodi_pilihan', 'prodi_id')->where(function ($query) {
                    return $query->where('tahun_ajaran_id', TahunAjaran::getCurrent()['id']);
                })->ignore($prodiPilihan),
            ],
            'tahun_ajaran_id' => request()->isMethod('PUT') ? 'required|exists:ref_tahun_ajaran,id' : 'nullable',
            'tanggal_mulai_pendaftaran' => 'required|date|before:tanggal_selesai_pendaftaran',
            'tanggal_selesai_pendaftaran' => 'required|date|after:tanggal_mulai_pendaftaran|before_or_equal:tanggal_selesai_administrasi',
            'tanggal_mulai_administrasi' => 'required|date|before:tanggal_selesai_administrasi|after_or_equal:tanggal_mulai_pendaftaran',
            'tanggal_selesai_administrasi' => 'required|date|after:tanggal_mulai_administrasi|before_or_equal:tanggal_pengumuman',
            'tanggal_pengumuman' => 'required|date|after_or_equal:tanggal_selesai_administrasi',
            'kuota_pendaftar' => 'required|numeric',

        ];
    }

    public function messages()
    {
        return [
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'prodi_id.unique' => 'Pilihan Program Studi untuk Program Studi ini sudah ada untuk Tahun Ajaran yang dipilih',
            'tahun_ajaran_id.required' => 'Tahun Ajaran harus diisi',
            'tahun_ajaran_id.exists' => 'Tahun Ajaran tidak valid',
            'tanggal_mulai_pendaftaran.required' => 'Tanggal Mulai Pendaftaran harus diisi',
            'tanggal_mulai_pendaftaran.before' => 'Tanggal Mulai Pendaftaran harus sebelum Tanggal Selesai Pendaftaran',
            'tanggal_selesai_pendaftaran.required' => 'Tanggal Selesai Pendaftaran harus diisi',
            'tanggal_selesai_pendaftaran.after' => 'Tanggal Selesai Pendaftaran harus setelah Tanggal Mulai Pendaftaran',
            'tanggal_selesai_pendaftaran.before_or_equal' => 'Tanggal Selesai Pendaftaran harus sebelum atau sama dengan Selesai Administrasi',
            'tanggal_mulai_administrasi.required' => 'Tanggal Mulai Administrasi harus diisi',
            'tanggal_mulai_administrasi.before' => 'Tanggal Mulai Administrasi harus sebelum Tanggal Selesai Administrasi',
            'tanggal_selesai_administrasi.required' => 'Tanggal Selesai Administrasi harus diisi',
            'tanggal_selesai_administrasi.after' => 'Tanggal Selesai Administrasi harus setelah Tanggal Mulai Administrasi',
            'tanggal_selesai_administrasi.before_or_equal' => 'Tanggal Selesai Administrasi harus sebelum atau sama dengan Tanggal Pengumuman',
            'tanggal_pengumuman.required' => 'Tanggal Pengumuman harus diisi',
            'tanggal_pengumuman.after_or_equal' => 'Tanggal Pengumuman harus setelah atau sama dengan Tanggal Selesai Administrasi',
            'kuota_pendaftar.required' => 'Kuota Pendaftar harus diisi',
            'kuota_pendaftar.numeric' => 'Kuota Pendaftar harus berupa angka',
        ];
    }
}
