<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenerimaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'bank' => 'required',
            'nama_bank' => 'required',
            'bic' => 'required',
            'cabang' => 'required',
            'nama_cabang' => 'required',
            'jenis' => 'required',
            'kode_akun' => 'required',
            'kode_setor' => 'required',
            'keterangan' => 'required',
            'no_rekening_validasi' => 'required|numeric',
            'nm_rekening_validasi' => 'required',
            'npwp_validasi' => 'required',
            'nm_npwp_validasi' => 'required',
        ];
        if (request()->isMethod('post')) {
            $rule = $rules;
        } elseif (request()->isMethod('put')) {
            $rule = $rules;
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'bank.required'    => 'Bank harus dipilih!',
            'nama_bank.required'    => 'Nama bank harus diisi!',
            'bic.required'    => 'BIC harus diisi!',
            'cabang.required'    => 'Cabang pusat harus dipilih!',
            'nama_cabang.required'    => 'Nama cabang pusat harus diisi!',
            'jenis.required'    => 'Jenis rekening pusat harus dipilih!',
            'kode_akun.required'    => 'Kode akun harus dipilih!',
            'kode_setor.required'    => 'Kode setor harus dipilih!',
            'keterangan.required'    => 'Keterangan harus diisi!',
            'no_rekening_validasi.required'    => 'No rekening bank harus diisi!',
            'no_rekening_validasi.numeric'    => 'No rekening bank harus berformat angka!',
            'nm_rekening_validasi.required'    => 'Nama pemilik/penerima harus diisi!',
            'npwp_validasi.required'    => 'NPWP harus diisi!',
            'nm_npwp_validasi.required'    => 'Nama WP harus diisi!',
        ];
    }
}
