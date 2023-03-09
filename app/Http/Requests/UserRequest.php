<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        if (request()->isMethod('post')) {
            $passwordRule = 'required';
            $confirmationPasswordRule = 'required';
        } elseif (request()->isMethod('put')) {
            $passwordRule = 'sometimes';
            $confirmationPasswordRule = 'sometimes';
        }
        return [
            'username' => ['required', Rule::unique('pengguna')->ignore(request()->segment(3))],
            'nama' => ['required', Rule::unique('pengguna')->ignore(request()->segment(3))],
            'password' => [$passwordRule],
            // 'password2' => [$passwordRule],
            'confirmation_password' => [$confirmationPasswordRule, 'same:password'],
            'kd_skpd' => ['required'],
            'tipe' => ['required'],
            'status' => ['required'],
            'peran' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'    => 'Username harus diisi!',
            'username.unique'    => 'Username telah ada!',
            'nama.required'    => 'Nama harus diisi!',
            'nama.unique'    => 'Nama telah ada!',
            'password.required'    => 'Password harus diisi!',
            'confirmation_password.required'    => 'Konfirmasi password harus diisi!',
            'kd_skpd.required'    => 'Kode SKPD harus dipilih!',
            'tipe.required'    => 'Tipe harus dipilih!',
            'status.required'    => 'Status harus dipilih!',
            'peran.required'    => 'Peran harus dipilih!',
        ];
    }
}
