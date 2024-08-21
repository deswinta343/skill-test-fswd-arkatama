<?php

namespace App\Http\Requests\Camaba\FormulirF07\Konferensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreKonferensiRequest extends FormRequest
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
            'judul' => 'required|min:3|max:255|string',
            'tahun' => 'required|numeric|digits:4|min:1900|max:'.(date('Y') + 100),
            'jenis_kegiatan' => 'required|in:seminar,konferensi,lokarkarya,simposium',
            'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'penyelenggara' => 'required|min:3|max:255',
            'tempat' => 'required|min:3|max:255',
            'peran' => 'required|in:Peserta,Pembicara,Panitia',
            'bukti_seminar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul harus diisi',
            'judul.min' => 'Judul minimal 3 karakter',
            'judul.max' => 'Judul maksimal 255 karakter',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.numeric' => 'Tahun harus berupa angka',
            'tahun.digits' => 'Tahun harus 4 digit',
            'tahun.min' => 'Tahun minimal 1900',
            'tahun.max' => 'Tahun maksimal '.(date('Y') + 100),
            'jenis_kegiatan.required' => 'Jenis Kegiatan harus diisi',
            'jenis_kegiatan.in' => 'Jenis Kegiatan tidak valid',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berupa tanggal',
            'tanggal_mulai.before_or_equal' => 'Tanggal Mulai harus sebelum atau sama dengan Tanggal Selesai',
            'tanggal_selesai.required' => 'Tanggal Selesai harus diisi',
            'tanggal_selesai.date' => 'Tanggal Selesai harus berupa tanggal',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai',
            'penyelenggara.required' => 'Penyelenggara harus diisi',
            'penyelenggara.min' => 'Penyelenggara minimal 3 karakter',
            'penyelenggara.max' => 'Penyelenggara maksimal 255 karakter',
            'tempat.required' => 'Tempat harus diisi',
            'tempat.min' => 'Tempat minimal 3 karakter',
            'tempat.max' => 'Tempat maksimal 255 karakter',
            'peran.required' => 'Peran harus diisi',
            'peran.in' => 'Peran tidak valid',
            'bukti_seminar.required' => 'Bukti Seminar harus diisi',
            'bukti_seminar.file' => 'Bukti Seminar harus berupa file',
            'bukti_seminar.mimes' => 'Format file tidak valid',
            'bukti_seminar.max' => 'Ukuran file maksimal 2048',
        ];
    }
}
