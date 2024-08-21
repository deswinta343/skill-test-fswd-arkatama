<?php

namespace App\Http\Requests\Akademik\PerguruanTinggi;

use App\Models\Akademik\PerguruanTinggi;
use Illuminate\Foundation\Http\FormRequest;

class StorePerguruanTinggiRequest extends FormRequest
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
        $rules = [
            'nama_perguruan_tinggi' => ['required', 'min:3', 'max:200'],
            'kode_perguruan_tinggi' => ['required', 'min:3', 'max:100'],
            'telepon' => ['required', 'digits_between:7,20', 'starts_with:0,62'],
            'email' => ['required', 'email', 'max:100'],
            'website' => ['required', 'url', 'max:100'],
            'jalan' => ['required', 'min:3', 'max:200'],
            'dusun' => ['required', 'min:3', 'max:100'],
            'rt_rw' => ['required', 'min:3', 'max:100'],
            'kelurahan' => ['required', 'min:3', 'max:100'],
            'kode_pos' => ['required', 'min:3', 'max:10'],
            'bank' => ['required', 'min:3', 'max:255'],
            'unit_cabang' => ['required', 'min:3', 'max:255'],
            'nomor_rekening' => ['required', 'min:3', 'max:30'],
            'sk_pendirian' => ['required', 'min:3', 'max:255'],
            'tanggal_sk_pendirian' => ['required', 'date'],
            'sk_izin_operasional' => ['required', 'min:3', 'max:255'],
            'tanggal_sk_izin_operasional' => ['required', 'date'],
            'predikat_akreditasi' => ['required', 'min:1', 'max:255'],
            'sk_akreditasi' => ['required', 'min:3', 'max:255'],
            'tanggal_sk_akreditasi' => ['required', 'date'],
        ];

        $perguruanTinggi = PerguruanTinggi::first();
        if (! $perguruanTinggi) {
            $rules['logo'] = ['required', 'image', 'max:1024'];
        } else {
            $rules['logo'] = ['required'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nama_perguruan_tinggi.required' => 'Nama Perguruan Tinggi harus diisi',
            'nama_perguruan_tinggi.min' => 'Nama Perguruan Tinggi minimal 3 karakter',
            'nama_perguruan_tinggi.max' => 'Nama Perguruan Tinggi maksimal 200 karakter',
            'kode_perguruan_tinggi.required' => 'Kode Perguruan Tinggi harus diisi',
            'kode_perguruan_tinggi.min' => 'Kode Perguruan Tinggi minimal 3 karakter',
            'kode_perguruan_tinggi.max' => 'Kode Perguruan Tinggi maksimal 100 karakter',
            'telepon.required' => 'Telepon harus diisi',
            'telepon.digits_between' => 'Telepon harus berupa angka dan minimal 7 karakter',
            'telepon.starts_with' => 'Telepon harus diawali dengan 0 atau 62',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email maksimal 100 karakter',
            'website.required' => 'Website harus diisi',
            'website.url' => 'Website tidak valid',
            'website.max' => 'Website maksimal 100 karakter',
            'jalan.required' => 'Jalan harus diisi',
            'jalan.min' => 'Jalan minimal 3 karakter',
            'jalan.max' => 'Jalan maksimal 200 karakter',
            'dusun.required' => 'Dusun harus diisi',
            'dusun.min' => 'Dusun minimal 3 karakter',
            'dusun.max' => 'Dusun maksimal 100 karakter',
            'rt_rw.required' => 'RT/RW harus diisi',
            'rt_rw.min' => 'RT/RW minimal 3 karakter',
            'rt_rw.max' => 'RT/RW maksimal 100 karakter',
            'kelurahan.required' => 'Kelurahan harus diisi',
            'kelurahan.min' => 'Kelurahan minimal 3 karakter',
            'kelurahan.max' => 'Kelurahan maksimal 100 karakter',
            'kode_pos.required' => 'Kode Pos harus diisi',
            'kode_pos.min' => 'Kode Pos minimal 3 karakter',
            'kode_pos.max' => 'Kode Pos maksimal 10 karakter',
            'bank.required' => 'Bank harus diisi',
            'bank.min' => 'Bank minimal 3 karakter',
            'bank.max' => 'Bank maksimal 255 karakter',
            'unit_cabang.required' => 'Unit Cabang harus diisi',
            'unit_cabang.min' => 'Unit Cabang minimal 3 karakter',
            'unit_cabang.max' => 'Unit Cabang maksimal 255 karakter',
            'nomor_rekening.required' => 'Nomor Rekening harus diisi',
            'nomor_rekening.min' => 'Nomor Rekening minimal 3 karakter',
            'nomor_rekening.max' => 'Nomor Rekening maksimal 30 karakter',
            'sk_pendirian.required' => 'SK Pendirian harus diisi',
            'sk_pendirian.min' => 'SK Pendirian minimal 3 karakter',
            'sk_pendirian.max' => 'SK Pendirian maksimal 255 karakter',
            'tanggal_sk_pendirian.required' => 'Tanggal SK Pendirian harus diisi',
            'tanggal_sk_pendirian.date' => 'Tanggal SK Pendirian harus berupa tanggal',
            'sk_izin_operasional.required' => 'SK Izin Operasional harus diisi',
            'sk_izin_operasional.min' => 'SK Izin Operasional minimal 3 karakter',
            'sk_izin_operasional.max' => 'SK Izin Operasional maksimal 255 karakter',
            'tanggal_sk_izin_operasional.required' => 'Tanggal SK Izin Operasional harus diisi',
            'tanggal_sk_izin_operasional.date' => 'Tanggal SK Izin Operasional harus berupa tanggal',
            'predikat_akreditasi.required' => 'Predikat Akreditasi harus diisi',
            'predikat_akreditasi.min' => 'Predikat Akreditasi minimal 3 karakter',
            'predikat_akreditasi.max' => 'Predikat Akreditasi maksimal 255 karakter',
            'sk_akreditasi.required' => 'SK Akreditasi harus diisi',
            'sk_akreditasi.min' => 'SK Akreditasi minimal 3 karakter',
            'sk_akreditasi.max' => 'SK Akreditasi maksimal 255 karakter',
            'tanggal_sk_akreditasi.required' => 'Tanggal SK Akreditasi harus diisi',
            'tanggal_sk_akreditasi.date' => 'Tanggal SK Akreditasi harus berupa tanggal',
            'logo.required' => 'Logo harus diisi',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.max' => 'Logo maksimal 1024 KB',
        ];
    }
}
