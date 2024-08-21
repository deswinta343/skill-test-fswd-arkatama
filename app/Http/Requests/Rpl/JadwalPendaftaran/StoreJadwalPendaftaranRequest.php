<?php

namespace App\Http\Requests\Rpl\JadwalPendaftaran;

use App\Models\Akademik\TahunAjaran;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJadwalPendaftaranRequest extends FormRequest
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
        $currentTahunAjaran = TahunAjaran::getCurrent();
        if ($this->method() == 'PUT') {
            return [
                'prodi_id' => [
                    'required',
                    'exists:ref_prodi,id',
                    Rule::unique('jadwal_pendaftaran')->where(
                        fn (Builder $query) => $query->where('prodi_id', $this->prodi_id)
                            ->where('tahun_ajaran_id', $currentTahunAjaran['id'])
                            ->where('id', '!=', $this->route('jadwal_pendaftaran')->id)
                    ),
                ],
                'tanggal_mulai_pendaftaran' => 'required|date|before:tanggal_selesai_pendaftran',
                'tanggal_selesai_pendaftaran' => 'required|date|after:tanggal_mulai_pendaftaran|before_or_equal:tanggal_pengumuman,tanggal_selesai_administrasi',
                'tanggal_mulai_administrasi' => 'required|date|before:tanggal_selesai_administrasi|after_or_equal:tanggal_mulai_pendaftaran',
                'tanggal_selesai_administrasi' => 'required|date|after:tanggal_mulai_administrasi|before_or_equal:tanggal_pengumuman',
                'tanggal_pengumuman' => 'required|date|after_or_equal:tanggal_selesai_pendaftaran,tanggal_selesai_administrasi',

                'kuota_pendaftar' => 'required|numeric',
            ];
        } else {
            return [
                'prodi_id' => 'required|exists:ref_prodi,id|unique:jadwal_pendaftaran,prodi_id,NULL,id,tahun_ajaran_id,'.$currentTahunAjaran->id,
                'tanggal_mulai_pendaftaran' => 'required|date|before:tanggal_selesai_pendaftaran',
                'tanggal_selesai_pendaftaran' => 'required|date|after:tanggal_mulai_pendaftaran|before_or_equal:tanggal_pengumuman',
                'tanggal_mulai_administrasi' => 'required|date|before:tanggal_selesai_administrasi|after_or_equal:tanggal_mulai_pendaftaran',
                'tanggal_selesai_administrasi' => 'required|date|after:tanggal_mulai_administrasi|before_or_equal:tanggal_pengumuman',
                'tanggal_pengumuman' => 'required|date|after_or_equal:tanggal_selesai_pendaftaran,tanggal_selesai_administrasi',
                'kuota_pendaftar' => 'required|numeric',
            ];
        }
    }

    public function messages()
    {
        return [
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'prodi_id.unique' => 'Jadwal Pendaftaran untuk Program Studi ini sudah ada',
            'tanggal_mulai_pendaftaran.required' => 'Tanggal Mulai Pendaftaran harus diisi',
            'tanggal_mulai_pendaftaran.date' => 'Tanggal Mulai Pendaftaran harus berupa tanggal',
            'tanggal_mulai_pendaftaran.before' => 'Tanggal Mulai Pendaftaran harus sebelum Tanggal Selesai Pendaftaran',
            'tanggal_selesai_pendaftaran.required' => 'Tanggal Selesai Pendaftaran harus diisi',
            'tanggal_selesai_pendaftaran.date' => 'Tanggal Selesai Pendaftaran harus berupa tanggal',
            'tanggal_selesai_pendaftaran.after' => 'Tanggal Selesai Pendaftaran harus setelah Tanggal Mulai Pendaftaran',
            'tanggal_selesai_pendaftaran.before_or_equal' => 'Tanggal Selesai Pendaftaran harus sebelum atau sama dengan Tanggal Pengumuman',
            'tanggal_mulai_administrasi.required' => 'Tanggal Mulai Administrasi harus diisi',
            'tanggal_mulai_administrasi.date' => 'Tanggal Mulai Administrasi harus berupa tanggal',
            'tanggal_mulai_administrasi.before' => 'Tanggal Mulai Administrasi harus sebelum Tanggal Selesai Administrasi',
            'tanggal_mulai_administrasi.after_or_equal' => 'Tanggal Mulai Administrasi harus setelah atau sama dengan Tanggal Mulai Pendaftaran',
            'tanggal_selesai_administrasi.required' => 'Tanggal Selesai Administrasi harus diisi',
            'tanggal_selesai_administrasi.date' => 'Tanggal Selesai Administrasi harus berupa tanggal',
            'tanggal_selesai_administrasi.after' => 'Tanggal Selesai Administrasi harus setelah Tanggal Mulai Administrasi',
            'tanggal_selesai_administrasi.before_or_equal' => 'Tanggal Selesai Administrasi harus sebelum atau sama dengan Tanggal Pengumuman',
            'tanggal_pengumuman.required' => 'Tanggal Pengumuman harus diisi',
            'tanggal_pengumuman.date' => 'Tanggal Pengumuman harus berupa tanggal',
            'tanggal_pengumuman.after_or_equal' => 'Tanggal Pengumuman harus setelah atau sama dengan Tanggal Selesai',
            'kuota.required' => 'Kuota harus diisi',
            'kuota.numeric' => 'Kuota harus berupa angka',
        ];
    }
}
