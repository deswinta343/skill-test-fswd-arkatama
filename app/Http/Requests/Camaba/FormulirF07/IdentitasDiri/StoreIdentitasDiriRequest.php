<?php

namespace App\Http\Requests\Camaba\FormulirF07\IdentitasDiri;

use Illuminate\Foundation\Http\FormRequest;

class StoreIdentitasDiriRequest extends FormRequest
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
            'nama_lengkap' => 'required|alpha_spaces|min:3|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|alpha_spaces|min:3|max:100',
            'tanggal_lahir' => 'required|date',
            'kebangsaan' => 'required|in:WNI,WNA',
            'status_pernikahan' => 'required|in:lajang,menikah,pernah menikah',
            'alamat' => 'required|min:3|max:200',
            'provinsi_id' => 'required|exists:ref_provinsi,id',
            'kabupaten_kota_id' => 'required|exists:ref_kabupaten_kota,id',
            'kecamatan_id' => 'required|exists:ref_kecamatan,id',
            'pendidikan_terakhir' => 'required|min:3|max:100',
            'kode_pos' => 'required|numeric|digits:5',
            'nomor_telepon' => 'required|numeric|digits_between:10,15|starts_with:0,62',
            //            'email' => 'required|email:rfc,dns',
            'nama_kantor' => 'required|min:3|max:100',
            'alamat_kantor' => 'required|min:3|max:100',
            'telepon_kantor' => 'required|numeric|digits_between:5,15',
            'jabatan' => 'required|min:3|max:100',
            'status_pekerjaan' => 'required|in:pegawai tetap,pegawai kontrak,pegawai honorer,pegawai negeri sipil,pegawai lainnya',
        ];
    }

    public function messages()
    {
        return [
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
            'nama_lengkap.alpha_spaces' => 'Nama Lengkap harus berupa huruf',
            'nama_lengkap.min' => 'Nama Lengkap minimal 3 karakter',
            'nama_lengkap.max' => 'Nama Lengkap maksimal 200 karakter',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis Kelamin tidak valid',
            'tempat_lahir.required' => 'Tempat Lahir harus diisi',
            'tempat_lahir.alpha_spaces' => 'Tempat Lahir harus berupa huruf',
            'tempat_lahir.min' => 'Tempat Lahir minimal 3 karakter',
            'tempat_lahir.max' => 'Tempat Lahir maksimal 100 karakter',
            'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
            'tanggal_lahir.date' => 'Tanggal Lahir tidak valid',
            'kebangsaan.required' => 'Kebangsaan harus diisi',
            'kebangsaan.in' => 'Kebangsaan tidak valid',
            'status_pernikahan.required' => 'Status Pernikahan harus diisi',
            'status_pernikahan.in' => 'Status Pernikahan tidak valid',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.min' => 'Alamat minimal 3 karakter',
            'alamat.max' => 'Alamat maksimal 200 karakter',
            'provinsi_id.required' => 'Provinsi harus diisi',
            'provinsi_id.exists' => 'Provinsi tidak valid',
            'kabupaten_kota_id.required' => 'Kabupaten/Kota harus diisi',
            'kabupaten_kota_id.exists' => 'Kabupaten/Kota tidak valid',
            'kecamatan_id.required' => 'Kecamatan harus diisi',
            'kecamatan_id.exists' => 'Kecamatan tidak valid',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi',
            'pendidikan_terakhir.min' => 'Pendidikan Terakhir minimal 3 karakter',
            'pendidikan_terakhir.max' => 'Pendidikan Terakhir maksimal 100 karakter',
            'kode_pos.required' => 'Kode Pos harus diisi',
            'kode_pos.numeric' => 'Kode Pos harus berupa angka',
            'kode_pos.digits' => 'Kode Pos harus 5 digit',
            'nomor_telepon.required' => 'Nomor Telepon harus diisi',
            'nomor_telepon.numeric' => 'Nomor Telepon harus berupa angka',
            'nomor_telepon.digits_between' => 'Nomor Telepon minimal 10 dan maksimal 15 karakter',
            'nomor_telepon.starts_with' => 'Nomor Telepon harus diawali dengan 0 atau 62',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'nama_kantor.required' => 'Nama Kantor harus diisi',
            'nama_kantor.min' => 'Nama Kantor minimal 3 karakter',
            'nama_kantor.max' => 'Nama Kantor maksimal 100 karakter',
            'alamat_kantor.required' => 'Alamat Kantor harus diisi',
            'alamat_kantor.min' => 'Alamat Kantor minimal 3 karakter',
            'alamat_kantor.max' => 'Alamat Kantor maksimal 100 karakter',
            'telepon_kantor.required' => 'Telepon Kantor harus diisi',
            'telepon_kantor.numeric' => 'Telepon Kantor harus berupa angka',
            'telepon_kantor.digits_between' => 'Telepon Kantor minimal 5 dan maksimal 15 karakter',
            'jabatan.required' => 'Jabatan harus diisi',
            'jabatan.min' => 'Jabatan minimal 3 karakter',
            'jabatan.max' => 'Jabatan maksimal 100 karakter',
            'status_pekerjaan.required' => 'Status Pekerjaan harus diisi',
            'status_pekerjaan.in' => 'Status Pekerjaan tidak valid',
        ];
    }
}
