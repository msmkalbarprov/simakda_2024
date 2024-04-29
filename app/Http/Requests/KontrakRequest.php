<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class KontrakRequest extends FormRequest
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
            'kd_skpd' => 'required',
            'nm_skpd' => 'required',
            'no_kontrak' => 'required',
            'tgl_kerja' => 'required|date',
            'nmpel' => 'required',
            'pimpinan' => 'required',
            'nm_kerja' => 'required',
            'nm_rekening' => 'required',
            'no_rekening' => 'required|numeric',
            'npwp' => 'required|numeric',
            'nilai' => 'required|numeric',
        ];
        if (request()->isMethod('post')) {
            $rule = $rules;
            $this->redirect = 'master/kontrak/create';
        } elseif (request()->isMethod('put')) {
            $rule = $rules;
            $this->redirect = 'master/kontrak/' . Crypt::encryptString(request()->route('kontrak')) . '/edit';
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'kd_skpd.required'    => 'Kode SKPD harus diisi!',
            'nm_skpd.required'    => 'Nama SKPD harus diisi!',
            'no_kontrak.required'    => 'No kontrak harus diisi!',
            'tgl_kerja.required'    => 'Tanggal kontrak harus diisi!',
            'tgl_kerja.date'    => 'Tanggal kontrak harus berformat tanggal!',
            'nmpel.required'    => 'Pelaksana pekerjaan/rekanan harus diisi!',
            'pimpinan.required'    => 'Pimpinan harus diisi!',
            'nm_kerja.required'    => 'Nama pekerjaan harus diisi!',
            'nm_rekening.required'    => 'Nama pemilik rekening harus dipilih!',
            'no_rekening.required'    => 'No rekening harus diisi!',
            'no_rekening.numeric'    => 'No rekening harus berformat angka!',
            'npwp.required'    => 'NPWP harus diisi!',
            'npwp.numeric'    => 'NPWP harus berformat angka!',
            'nilai.required'    => 'Nilai kontrak harus diisi!',
            'nilai.numeric'    => 'Nilai kontrak harus berformat angka!',
        ];
    }
}
