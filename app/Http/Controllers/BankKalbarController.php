<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankKalbarController extends Controller
{
    function get_token_api()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/hh/auth",
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
                CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/penerima/validasi",
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
                CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/npwp/validasi",
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

    public function isiListPot(Request $request)
    {
        $reff = DB::table('noref_MPN')->select(DB::raw("noRef + 1 as noReff"))->first();
        $noReff = $reff->noReff;
        $data['idBilling'] = $request->id_billing;
        $data['referenceNo'] = $noReff;
        $data['reInquiry'] = "false";

        $datakirim = json_encode($data);
        $api_key = $this->get_token_api();
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            "Content-Type: application/json"
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/ntp/validasi",
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
        DB::table('noref_MPN')->update([
            'noRef' => $noReff
        ]);
        return response()->json($response);
    }

    public function createBilling(Request $request)
    {
        $npwp                   = $request->npwp;
        $kode_map               = $request->kode_map;
        $nama_map               = $request->nama_map;
        $kode_setor             = $request->kode_setor;
        $nama_setor             = $request->nama_setor;
        $masa_pajak             = $request->masa_pajak;
        $tahun_pajak            = $request->tahun_pajak;
        $jumlah_bayar           = $request->jumlah_bayar;
        $nop                    = $request->nop;
        $no_sk                  = $request->no_sk;
        $npwp_setor             = $request->npwp_setor;
        $nama_wajib_pajak       = $request->nama_wajib_pajak;
        $alamat_wajib_pajak     = $request->alamat_wajib_pajak;
        $kota                   = $request->kota;
        $nik                    = $request->nik;
        $npwp_rekanan           = $request->npwp_rekanan;
        $nik_rekanan            = $request->nik_rekanan;
        $no_faktur              = $request->no_faktur;
        $kd_skpd                = $request->kd_skpd;
        $no_spm                 = $request->no_spm;
        $nama_akun_potongan     = $request->nama_akun_potongan;
        $kode_akun_potongan     = $request->kode_akun_potongan;
        $kode_akun_transaksi    = $request->kode_akun_transaksi;

        $data['nomorPokokWajibPajak']           = isset($npwp) ? $npwp : '';
        $data['kodeMap']                        = isset($kode_map) ? $kode_map : '';
        $data['kodeSetor']                      = isset($kode_setor) ? $kode_setor : '';
        $data['masaPajak']                      = isset($masa_pajak) ? $masa_pajak : '';
        $data['tahunPajak']                     = isset($tahun_pajak) ? $tahun_pajak : '';
        $data['jumlahBayar']                    = isset($jumlah_bayar) ? $jumlah_bayar : '';
        $data['nomorObjekPajak']                = isset($nop) ? $nop : '';
        $data['nomorSK']                        = isset($no_sk) ? $no_sk : '';
        $data['nomorPokokWajibPajakPenyetor']   = isset($npwp_setor) ? $npwp_setor : '';
        $data['namaWajibPajak']                 = isset($nama_wajib_pajak) ? $nama_wajib_pajak : '';
        $data['alamatWajibPajak']               = isset($alamat_wajib_pajak) ? $alamat_wajib_pajak : '';
        $data['kota']                           = isset($kota) ? $kota : '';
        $data['nik']                            = isset($nik) ? $nik : '';
        $data['nomorPokokWajibPajakRekanan']    = isset($npwp_rekanan) ? $npwp_rekanan : '';
        $data['nikRekanan']                     = isset($nik_rekanan) ? $nik_rekanan : '';
        $data['nomorFakturPajak']               = isset($no_faktur) ? $no_faktur : '';
        $data['nomorSKPD']                      = isset($kd_skpd) ? $kd_skpd : '';
        $data['nomorSPM']                       = isset($no_spm) ? $no_spm : '';

        $datakirim = json_encode($data);
        $api_key = $this->get_token_api();
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            "Content-Type: application/json"
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/idbilling/create",
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
        $potongan = json_decode($response);
        curl_close($curl);

        DB::beginTransaction();
        try {
            // if ($potongan->data[0]->response_code == "00") {
            $inputtgl = date('Y-m-d H:i:s', strtotime($potongan->data[0]->data->tanggalExpiredBilling));
            DB::table('trspmpot')->insert([
                'no_spm' => $no_spm,
                'kd_skpd' => $kd_skpd,
                'kd_rek6' => $kode_akun_potongan,
                'map_pot' => $kode_akun_potongan,
                'nm_rek6' => $nama_akun_potongan,
                'nilai' => $jumlah_bayar,
                'kd_trans' => $kode_akun_transaksi,
                'nomorPokokWajibPajak' => $npwp,
                'namaWajibPajak' => $nama_wajib_pajak,
                'alamatWajibPajak' => $alamat_wajib_pajak,
                'kota' => $kota,
                'nik' => $nik,
                'kodeMap' => $kode_map,
                'keteranganKodeMap' => $nama_map,
                'kodeSetor' => $kode_setor,
                'keteranganKodeSetor' => $nama_setor,
                'masaPajak' => $masa_pajak,
                'tahunPajak' => $tahun_pajak,
                'jumlahBayar' => $jumlah_bayar,
                'nomorObjekPajak' => $nop,
                'nomorSK' => $no_sk,
                'nomorPokokWajibPajakPenyetor' => $npwp_setor,
                'nomorPokokWajibPajakRekanan' => $npwp_rekanan,
                'nikRekanan' => $nik_rekanan,
                'nomorFakturPajak' => $no_faktur,
                'idBilling' => $potongan->data[0]->data->idBilling,
                // 'idBilling' => '12131',
                'tanggalExpiredBilling' => $inputtgl,
                // 'tanggalExpiredBilling' => date('Y-m-d H:i:s'),
                'jenis' => '2',
                'username' => Auth::user()->nama,
                'last_update' => date('Y-m-d H:i:s')
            ]);
            DB::commit();
            curl_close($curl);
            return response()->json($response);
            // }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($response);
        }
    }

    public function createReport(Request $request)
    {
        $data['noReferensi'] = $request->id_billing;
        $data['jenisReport'] = $request->jnsreport;
        $data['tanggalReportAwal'] = '';
        $data['tanggalReportAkhir'] = '';
        $data['formatReport'] = 'pdf';
        $datakirim = json_encode($data);
        $api_key = $this->get_token_api();
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            "Content-Type: application/json"
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "222.124.219.178:10090/api/sppd/ntp/report",
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

    public function cekBilling(Request $request)
    {
        $data['idBilling'] = $request->id_billing;
        $datakirim = json_encode($data);

        $api_key = $this->get_token_api();
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            "Content-Type: application/json"
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://192.168.9.2:10090/api/sppd/idbilling/validasi",
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
