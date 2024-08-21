<?php

namespace App\Http\Requests\Camaba\FormulirF07\Pendidikan;

use Illuminate\Foundation\Http\FormRequest;

class StorePendidikanRequest extends FormRequest
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
            'nama_institusi' => ['required', 'alpha_num_spaces', 'min:3', 'max:255'],
            'jenjang_pendidikan_id' => ['required', 'exists:ref_jenjang_pendidikan,id'],
            'tahun_lulus' => ['required', 'numeric', 'digits:4', 'min:1900', 'max:'.(date('Y') + 100)],
            'jurusan' => ['required', 'min:3', 'max:255', 'alpha_num_spaces_with_alphabet_and_symbol'],
            'bukti_ijazah' => ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_institusi.required' => 'Nama Institusi harus diisi',
            'nama_institusi.alpha_num_spaces' => 'Nama Institusi harus berupa huruf, angka, dan spasi',
            'nama_institusi.min' => 'Nama Institusi minimal 3 karakter',
            'nama_institusi.max' => 'Nama Institusi maksimal 255 karakter',
            'jenjang_pendidikan_id.required' => 'Jenjang Pendidikan harus diisi',
            'jenjang_pendidikan_id.exists' => 'Jenjang Pendidikan tidak valid',
            'tahun_lulus.required' => 'Tahun Lulus harus diisi',
            'tahun_lulus.numeric' => 'Tahun Lulus harus berupa angka',
            'tahun_lulus.digits' => 'Tahun Lulus harus 4 digit',
            'tahun_lulus.min' => 'Tahun Lulus minimal 1900',
            'tahun_lulus.max' => 'Tahun Lulus maksimal '.(date('Y') + 100),
            'jurusan.required' => 'Jurusan harus diisi',
            'jurusan.alpha_num_spaces_with_alphabet_and_symbol' => 'Jurusan tidak boleh hanya angka atau simbol, paling tidak harus ada satu huruf',
            'jurusan.min' => 'Jurusan minimal 3 karakter',
            'jurusan.max' => 'Jurusan maksimal 255 karakter',
            'bukti_ijazah.required' => 'Bukti Ijazah harus diisi',
            'bukti_ijazah.file' => 'Bukti Ijazah harus berupa file',
            'bukti_ijazah.max' => 'Ukuran file maksimal 2048 KB',
            'bukti_ijazah.mimes' => 'Format file tidak valid',
        ];
    }
}
