<?php

namespace App\Http\Requests\Camaba\Formulirf02;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataCamabaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'tempat_lahir' => 'required|min:3|max:100|alpha_spaces',
            'tanggal_lahir' => 'required|date',
            'status_pernikahan' => 'required|in:lajang,menikah,pernah menikah',
            'kebangsaan' => 'required|in:WNI,WNA',
            'alamat' => 'required|min:3|max:200',
            'provinsi_id' => 'required|exists:ref_provinsi,id',
            'kabupaten_kota_id' => 'required|exists:ref_kabupaten_kota,id',
            'pendidikan_terakhir' => 'required|min:3|max:100',
            'nama_instansi_pendidikan' => 'required|min:3|max:100',
            'jurusan' => 'required|min:3|max:100',
            'tahun_lulus' => 'required|digits:4',
        ];
    }
}
