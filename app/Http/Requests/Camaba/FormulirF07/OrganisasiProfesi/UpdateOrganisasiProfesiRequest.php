<?php

namespace App\Http\Requests\Camaba\FormulirF07\OrganisasiProfesi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganisasiProfesiRequest extends FormRequest
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
            'nama_organisasi' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'tingkat' => ['required', 'in:internasional,nasional,provinsi,kabupaten_kota'],
            'jabatan' => ['required', 'min:3', 'max:255'],
            'tempat' => ['required', 'min:3', 'max:255'],
            'bukti_organisasi' => $this->file('bukti_organisasi') ? ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'] : ['required'],
        ];
    }

    public function messages()
    {
        return [
            'nama_organisasi.required' => 'Nama Organisasi harus diisi',
            'nama_organisasi.min' => 'Nama Organisasi minimal 3 karakter',
            'nama_organisasi.max' => 'Nama Organisasi maksimal 255 karakter',
            'nama_organisasi.alpha_num_spaces_with_alphabet_and_symbol' => 'Nama Organisasi tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.numeric' => 'Tahun harus berupa angka',
            'tahun.digits' => 'Tahun harus 4 digit',
            'tingkat.required' => 'Tingkat harus diisi',
            'tingkat.in' => 'Tingkat tidak valid',
            'jabatan.required' => 'Jabatan harus diisi',
            'jabatan.min' => 'Jabatan minimal 3 karakter',
            'jabatan.max' => 'Jabatan maksimal 255 karakter',
            'tempat.required' => 'Tempat harus diisi',
            'tempat.min' => 'Tempat minimal 3 karakter',
            'tempat.max' => 'Tempat maksimal 255 karakter',
            'bukti_organisasi.required' => 'Bukti Organisasi harus diisi',
            'bukti_organisasi.file' => 'Bukti Organisasi harus berupa file',
            'bukti_organisasi.max' => 'Ukuran file maksimal 2048 KB',
            'bukti_organisasi.mimes' => 'Format file tidak valid',
        ];
    }
}
