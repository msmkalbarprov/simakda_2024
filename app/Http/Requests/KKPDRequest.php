<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KKPDRequest extends FormRequest
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
            'no_kkpd'       => 'required',
            'nm_kkpd'       => 'required',
            'kd_skpd'       => 'required',
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
            'kd_skpd.required'          => 'Kode SKPD harus diisi!',
            'no_kkpd.required'          => 'Nomor KKPD harus diisi!',
            'nm_kkpd.required'          => 'Nama Pemilik KKPD harus diisi!'
        ];
    }
}
