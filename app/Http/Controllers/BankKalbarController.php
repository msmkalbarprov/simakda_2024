<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankKalbarController extends Controller
{
    function get_token_api()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://222.124.219.178:10080/sppd/sppd/hh/auth",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n    \"key\" : \"AENao6JDrf9+xCSwJks18IghphTdQuvcOBcVc7abvCo0WeZSDxm/9IPy+2EaqnVG\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($response);
        $j = $array->data[0]->token;
        return $j;
    }

    public function cek_rekening(Request $request)
    {
        if ($request->ajax()) {
            $data['kodeBank'] = $request->kode_bank;
            $data['noAkun'] = $request->no_rek;
            $data['namaPenerima'] = $request->nm_rek;
            $datakirim = json_encode($data);
            $api_key = $this->get_token_api();
            $headers = array(
                'Authorization: Bearer ' . $api_key,
                "Content-Type: application/json"
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://222.124.219.178:10080/sppd/sppd/penerima/validasi",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $datakirim,
                CURLOPT_HTTPHEADER => $headers,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return response()->json($response);
        }
    }

    function cek_npwp(Request $request)
    {
        if ($request->ajax()) {
            $data['kodeMap'] = $request->kode_akun;
            $data['kodeSetor'] = $request->kode_setor;
            $data['nomorPokokWajibPajak'] = $request->npwp;
            $datakirim = json_encode($data);
            $api_key = $this->get_token_api();
            $headers = array(
                'Authorization: Bearer ' . $api_key,
                "Content-Type: application/json"
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://222.124.219.178:10080/sppd/sppd/npwp/validasi",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $datakirim,
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return response()->json($response);
        }
    }
}
