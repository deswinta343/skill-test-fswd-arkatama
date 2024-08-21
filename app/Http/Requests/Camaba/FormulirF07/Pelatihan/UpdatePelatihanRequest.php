<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pelatihan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelatihanRequest extends FormRequest
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
            'nama_pelatihan' => ['required', 'alpha_num_spaces_with_alphabet_and_symbol', 'min:3', 'max:255'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'jenis' => ['required', 'in:DN,LN'],
            'penyelenggara' => ['required', 'alpha_num_spaces_with_alphabet_and_symbol', 'min:3', 'max:255'],
            'tempat' => ['required', 'alpha_spaces', 'min:3', 'max:255'],
            'jangka_waktu' => ['required', 'numeric', 'min:1', 'max:365'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'bukti_pelatihan' => $this->file('bukti_pelatihan') ? ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'] : [],
        ];
    }

    public function messages()
    {
        return [
            'nama_pelatihan.required' => 'Nama Pelatihan harus diisi',
            'nama_pelatihan.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Pelatihan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'nama_pelatihan.min' => 'Nama Pelatihan minimal 3 karakter',
            'nama_pelatihan.max' => 'Nama Pelatihan maksimal 255 karakter',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.numeric' => 'Tahun harus berupa angka',
            'tahun.digits' => 'Tahun harus 4 digit',
            'jenis.required' => 'Jenis Pelatihan harus diisi',
            'jenis.in' => 'Jenis Pelatihan tidak valid',
            'penyelenggara.required' => 'Penyelenggara harus diisi',
            'penyelenggara.alpha_num_spaces_with_alphabet_and_symbol' => 'Penyelenggara tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'penyelenggara.min' => 'Penyelenggara minimal 3 karakter',
            'penyelenggara.max' => 'Penyelenggara maksimal 255 karakter',
            'tempat.required' => 'Tempat harus diisi',
            'tempat.alpha_spaces' => 'Tempat harus berupa huruf dan spasi',
            'tempat.min' => 'Tempat minimal 3 karakter',
            'tempat.max' => 'Tempat maksimal 255 karakter',
            'jangka_waktu.required' => 'Jangka Waktu harus diisi',
            'jangka_waktu.numeric' => 'Jangka Waktu harus berupa angka',
            'jangka_waktu.min' => 'Jangka Waktu minimal 1 hari',
            'jangka_waktu.max' => 'Jangka Waktu maksimal 365 hari',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi',
            'tanggal_selesai.required' => 'Tanggal Selesai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai',
            'bukti_pelatihan.required' => 'Bukti Pelatihan harus diisi',
            'bukti_pelatihan.file' => 'Bukti Pelatihan harus berupa file',
            'bukti_pelatihan.max' => 'Ukuran file maksimal 2048 KB',
            'bukti_pelatihan.mimes' => 'Format file tidak valid',
        ];
    }
}
