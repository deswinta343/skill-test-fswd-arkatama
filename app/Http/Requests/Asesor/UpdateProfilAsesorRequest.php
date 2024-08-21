<?php

namespace App\Http\Requests\Asesor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilAsesorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules()
    {
        $allowedFormat = getSetting('document_allowed_file_types', 'jpg,jpeg,png');
        $maxFileSize = getSetting('document_max_file_size', 2048);

        return [
            'nama_lengkap' => 'required|string|max:200|min:3|alpha_spaces',
            'tempat_lahir' => 'required|string|max:200',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|numeric|starts_with:0,62|digits_between:10,15',
            'no_fax' => 'nullable|numeric|starts_with:0,62|digits_between:10,15',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:200',
            'kabupaten' => 'nullable|string|max:200',
            'kecamatan' => 'nullable|string|max:200',
            'kodepos' => 'nullable|numeric',
            'pendidikan_terakhir' => 'nullable|string|max:200',
            'bidang_keahlian' => 'nullable|string|max:200',
            'asosiasi_profesi_keanggotaan' => 'nullable|string|max:200|alpha_spaces',
            'asosiasi_profesi_no_anggota' => 'nullable|numeric',
            'akademik_pangkat' => 'nullable|string|max:200',
            'akademik_jabatan' => 'nullable|string|max:200',
            'akademik_nip' => 'nullable|numeric',
            'akademik_nidn' => 'nullable|numeric',
            'akademik_nama_pt' => 'nullable|string|max:200',
            'akademik_alamat_pt' => 'nullable|string|max:200',
            'profesi_pekerjaan' => 'nullable|string|max:200',
            'profesi_nama_instansi' => 'nullable|string|max:200',
            'profesi_jabatan_instansi' => 'nullable|string|max:200',
            'profesi_alamat_instansi' => 'nullable|string|max:200',
            'profesi_telepon_instansi' => 'nullable|numeric|starts_with:0,62|digits_between:10,15',
            'profesi_bidang_keahlian' => 'nullable|string|max:200',
            'foto_profil' => $this->file('foto_profil') ? ['nullable', 'image', 'mimes:'.$allowedFormat, 'max:'.$maxFileSize] : ['nullable'],
        ];
    }
}
