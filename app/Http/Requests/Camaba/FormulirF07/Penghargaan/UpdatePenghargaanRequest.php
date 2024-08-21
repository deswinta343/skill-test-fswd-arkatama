<?php

namespace App\Http\Requests\Camaba\FormulirF07\Penghargaan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenghargaanRequest extends FormRequest
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
            'nama_penghargaan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'pemberi' => ['required', 'alpha_spaces', 'min:3', 'max:255'],
            'tingkat' => ['required', 'in:internasional,nasional,provinsi,kabupaten_kota'],
            'bukti_penghargaan' => $this->file('bukti_penghargaan') ? ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'] : ['required'],
        ];
    }

    public function messages()
    {
        return [
            'nama_penghargaan.required' => 'Nama Penghargaan harus diisi',
            'nama_penghargaan.min' => 'Nama Penghargaan minimal 3 karakter',
            'nama_penghargaan.max' => 'Nama Penghargaan maksimal 255 karakter',
            'nama_penghargaan.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Penghargaan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.numeric' => 'Tahun harus berupa angka',
            'tahun.digits' => 'Tahun harus 4 digit',
            'pemberi.required' => 'Pemberi harus diisi',
            'pemberi.alpha_spaces' => 'Pemberi harus berupa huruf dan spasi',
            'pemberi.min' => 'Pemberi minimal 3 karakter',
            'pemberi.max' => 'Pemberi maksimal 255 karakter',
            'tingkat.required' => 'Tingkat harus diisi',
            'tingkat.in' => 'Tingkat tidak valid',
            'bukti_penghargaan.required' => 'Bukti Penghargaan harus diisi',
            'bukti_penghargaan.file' => 'Bukti Penghargaan harus berupa file',
            'bukti_penghargaan.max' => 'Ukuran file maksimal 2048 KB',
            'bukti_penghargaan.mimes' => 'Format file tidak valid',
        ];
    }
}
