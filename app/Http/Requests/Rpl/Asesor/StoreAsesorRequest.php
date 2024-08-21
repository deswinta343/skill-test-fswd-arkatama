<?php

namespace App\Http\Requests\Rpl\Asesor;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsesorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'jenis_asesor' => 'required|string|in:akademisi,praktisi',
            'prodi_id' => 'required|exists:ref_prodi,id',
            'nama_lengkap' => 'required|string|max:200|min:3|alpha_num_spaces_with_alphabet_and_symbol',
            'tempat_lahir' => 'required|string|max:200',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|string|in:L,P',
            'no_hp' => 'required|numeric|starts_with:0,62|digits_between:10,15|unique:asesor,no_hp,'.$this->asesor?->id,
            'email' => 'required|email|unique:users,email,'.$this->asesor?->user_id,
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string|max:200',
            'bidang_keahlian' => 'required|string|max:200|alpha_num_spaces_with_alphabet_and_symbol',
            'asosiasi_profesi_keanggotaan' => 'required|string|max:200|alpha_num_spaces_with_alphabet_and_symbol',
            'asosiasi_profesi_no_anggota' => 'required|string|max:200',
            'akademik_pangkat' => 'nullable|string|max:200|alpha_num_spaces_with_alphabet_and_symbol',
            'akademik_jabatan' => 'nullable|string|max:200|alpha_num_spaces_with_alphabet_and_symbol',
            'akademik_nip' => 'nullable|digits:18',
            'akademik_nidn' => 'nullable|digits:10',
            'akademik_nama_pt' => 'nullable|string|max:200',
            'akademik_alamat_pt' => 'nullable|string|max:200',
            'profesi_pekerjaan' => 'nullable|string|max:200',
            'profesi_nama_instansi' => 'nullable|string|max:200',
            'profesi_jabatan_instansi' => 'nullable|string|max:200',
            'profesi_alamat_instansi' => 'nullable|string|max:200',
            'profesi_telepon_instansi' => 'nullable|numeric|starts_with:0,62|digits_between:10,15',
            'profesi_bidang_keahlian' => 'nullable|string|max:200',
        ];
    }

    public function messages()
    {
        return [
            'jenis_asesor.required' => 'Jenis Asesor harus diisi',
            'jenis_asesor.in' => 'Jenis Asesor tidak valid',
            'prodi_id.required' => 'Program Studi harus diisi',
            'prodi_id.exists' => 'Program Studi tidak valid',
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
            'nama_lengkap.max' => 'Nama Lengkap maksimal 200 karakter',
            'nama_lengkap.min' => 'Nama Lengkap minimal 3 karakter',
            'nama_lengkap.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Lengkap tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'tempat_lahir.required' => 'Tempat Lahir harus diisi',
            'tempat_lahir.max' => 'Tempat Lahir maksimal 200 karakter',
            'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
            'tanggal_lahir.date' => 'Tanggal Lahir harus berupa tanggal',
            'tanggal_lahir.before_or_equal' => 'Tanggal Lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis Kelamin tidak valid',
            'no_hp.required' => 'No HP harus diisi',
            'no_hp.numeric' => 'No HP harus berupa angka',
            'no_hp.starts_with' => 'No HP harus diawali dengan 0 atau 62',
            'no_hp.digits_between' => 'No HP minimal 10 dan maksimal 15 karakter',
            'no_hp.unique' => 'No HP sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus berbentuk email valid dengan disertai @ dan .',
            'email.unique' => 'Email sudah terdaftar',
            'alamat.required' => 'Alamat harus diisi',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi',
            'pendidikan_terakhir.max' => 'Pendidikan Terakhir maksimal 200 karakter',
            'bidang_keahlian.required' => 'Bidang Keahlian harus diisi',
            'bidang_keahlian.max' => 'Bidang Keahlian maksimal 200',
            'bidang_keahlian.alpha_num_spaces_with_alphabet_and_symbol' => 'Bidang Keahlian tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'asosiasi_profesi_keanggotaan.required' => 'Asosiasi Profesi Keanggotaan harus diisi',
            'asosiasi_profesi_keanggotaan.max' => 'Asosiasi Profesi Keanggotaan maksimal 200 karakter',
            'asosiasi_profesi_keanggotaan.alpha_num_spaces_with_alphabet_and_symbol' => 'Asosiasi Profesi Keanggotaan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'asosiasi_profesi_no_anggota.required' => 'Asosiasi Profesi No Anggota harus diisi',
            'asosiasi_profesi_no_anggota.max' => 'Asosiasi Profesi No Anggota maksimal 200 karakter',
            'akademik_pangkat.max' => 'Akademik Pangkat maksimal 200 karakter',
            'akademik_pangkat.alpha_num_spaces_with_alphabet_and_symbol' => 'Akademik Pangkat tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'akademik_jabatan.max' => 'Akademik Jabatan maksimal 200 karakter',
            'akademik_jabatan.alpha_num_spaces_with_alphabet_and_symbol' => 'Akademik Jabatan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'akademik_nip.digits' => 'Akademik NIP harus 18 karakter',
            'akademik_nidn.digits' => 'Akademik NIDN harus 10 karakter',
            'akademik_nama_pt.max' => 'Akademik Nama PT maksimal 200 karakter',
            'akademik_alamat_pt.max' => 'Akademik Alamat PT maksimal 200 karakter',
            'profesi_pekerjaan.max' => 'Profesi Pekerjaan maksimal 200 karakter',
            'profesi_nama_instansi.max' => 'Profesi Nama Instansi maksimal 200 karakter',
            'profesi_jabatan_instansi.max' => 'Profesi Jabatan Instansi maksimal 200 karakter',
            'profesi_alamat_instansi.max' => 'Profesi Alamat Instansi maksimal 200 karakter',
            'profesi_telepon_instansi.numeric' => 'Profesi Telepon Instansi harus berupa angka',
            'profesi_telepon_instansi.starts_with' => 'Profesi Telepon Instansi harus diawali dengan 0 atau 62',
            'profesi_telepon_instansi.digits_between' => 'Profesi Telepon Instansi minimal 10 dan maksimal 15 karakter',
            'profesi_bidang_keahlian.max' => 'Profesi Bidang Keahlian maksimal 200 karakter',
        ];
    }
}
