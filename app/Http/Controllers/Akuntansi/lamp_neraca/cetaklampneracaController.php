<?php

namespace App\Http\Controllers\Akuntansi\lamp_neraca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class cetaklampneracaController extends Controller
{

    public function cetak_lamp_neraca(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $skpd = $request->kd_skpd;
        $rek3    = $request->rek3;
        $cetakan = $request->cetakan;
        $cetak   = $request->cetak;
        
        // dd($kd_skpd);
        
        $lntahunang    = tahun_anggaran();
        $thn_ang1   = $lntahunang-1;

        if ($rek3==1301) {
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi<br>Kota/Kab.</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Sertifikat</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Luas</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                </tr>
                </thead>";
            }else{
                $head = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi<br>Kota/Kab.</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Sertifikat</td>
                        <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Luas</td>
                        <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                        <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                        <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                        <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                    </tr>
                    <tr>
                        <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                        <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                        <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                        <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                        <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                    </tr>
                    <tr>
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                       <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                    </tr>
                    </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' lokasi,'' alamat, '' sert, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '1301' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) AS kode1, LEFT(kd_rek5,5) AS kode,(SELECT UPPER(nm_rek4_64) FROM ms_rek4_64 WHERE kd_rek4_64 = LEFT(kd_rek5,5)) AS nama, ''  tahun,  '' lokasi,'' alamat, '' sert, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset  where kd_rek3 = '1301' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' lokasi,'' alamat, '' sert, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '1301' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (kd_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  lokasi, alamat,  sert, luas,  satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where kd_rek3 = '1301' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' AS kode1, '' AS kode,(SELECT 'TOTAL '+UPPER(nm_rek4_64) FROM ms_rek4_64 WHERE kd_rek4_64 = LEFT(kd_rek5,5)) AS nama, ''  tahun,  '' lokasi,'' alamat, '' sert, 0 luas, '' satuan,  SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '1301' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, lokasi, alamat, tahun");  
            $query_jum = collect(DB::select("SELECT   SUM(sal_awal) as sal_awal,  sum(kurang) as kurang,  SUM(tambah) as tambah, sum(tahun_n) as tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '1301' and kd_skpd = '$skpd'"))->first(); 
        }else if($rek3==1303 || $rek3==1306 || $rek3==1304){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi<br>Kota/Kab.</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Luas</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi<br>Kota/Kab.</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Luas</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' lokasi,'' alamat, '' fungsi, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode, (SELECT UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64 = LEFT(kd_rek5,5)) as nama, ''  tahun,  '' lokasi,'' alamat, '' fungsi, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' lokasi,'' alamat, '' fungsi, 0 luas, '' satuan,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  lokasi, alamat,  fungsi, luas,  satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64 = LEFT(kd_rek5,5)) as nama, ''  tahun,  '' lokasi,'' alamat, '' fungsi, 0 luas, '' satuan,  SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, lokasi, alamat, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3_64 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal,  SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1302){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' merk,'' no_polisi, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode,(SELECT UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1,kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' merk, '' no_polisi, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  merk, no_polisi, jumlah,  satuan, harga_satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, 0 jumlah, '' satuan, 0 harga_satuan, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1112){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"4\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">X</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"4\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">X</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' merk,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun,  '' merk,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_x, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' merk,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                       UNION
                    SELECT '' no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, ''  tahun,  '' merk,0 jumlah, 
                    0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b,
                     0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan 
                    FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                    UNION ALL
                    SELECT no_lamp, case when (kd_rek7='') then kd_rek6 else kd_rek7 end as kode1, 
                    case when (kd_rek7='') then kd_rek6 else kd_rek7 end as kode, case when (nm_rek7='') then nm_rek6 else nm_rek7 end as nama,
                     tahun,  merk, jumlah, kondisi_b+kondisi_rr+kondisi_rb jumlah_akhir, satuan, harga_satuan, sal_awal,  kurang,  tambah,
                     tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb, kondisi_x, keterangan 
                    FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun,  '' merk,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun,jumlah");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==2101){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>
                    
                   
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' lokasi,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun,  '' lokasi,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_x, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' lokasi,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                       UNION
                    SELECT '' no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, ''  tahun,  '' lokasi,0 jumlah, 
                    0 jumlah_akhir, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b,
                     0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan 
                    FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                    UNION ALL
                    SELECT no_lamp, case when (kd_rek7='') then kd_rek6 else kd_rek7 end as kode1, 
                    case when (kd_rek7='') then kd_rek6 else kd_rek7 end as kode, case when (nm_rek7='') then nm_rek6 else nm_rek7 end as nama,
                     tahun,  lokasi, jumlah, kondisi_b+kondisi_rr+kondisi_rb jumlah_akhir, satuan, harga_satuan, sal_awal,  kurang,  tambah,
                     tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb, kondisi_x, keterangan 
                    FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun,  '' merk,0 jumlah, 0 jumlah_akhir, '' satuan, 0 harga_satuan,   SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, 0 kondisi_x, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun,jumlah");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1305){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\"  bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' merk,0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode,(SELECT UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk,0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' merk,0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  merk, jumlah,  satuan, harga_satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk,0 jumlah, '' satuan, 0 harga_satuan,   SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3_64 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1111){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun<br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jenis Aset</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Nama<br>Perusahaan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Nomor<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Realisasi<br>Perjanjian</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tanggal Jatuh<br>Tempo</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Waktu<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Sisa Hari<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Akhir</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                    

                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun<br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jenis Aset</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Nama<br>Perusahaan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Nomor<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Realisasi<br>Perjanjian</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tanggal Jatuh<br>Tempo</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Waktu<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Sisa Hari<br>Perjanjian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Akhir</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 

                </tr>
                </thead>";
            }
            $query = DB::select("SELECT no_lamp,kode1,kode,nama,tahun,jenis_aset,nama_perusahaan,no_polis,realisasi_janji,tgl_awal,tgl_akhir,jam,sisa_hari,sal_awal,  kurang,  tambah, tahun_n,koreksi,keterangan,seling,
                    case when jam_std <=0 and sisa_hari!=0 then (sisa_hari-0.5)
                        when jam_std > 0 and sisa_hari!=0 then (sisa_hari-1) else 0 end as sis
                        from
                    (SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, '' tahun, '' jenis_aset, '' nama_perusahaan, '' no_polis, 0 realisasi_janji, '' tgl_awal, '' tgl_akhir, '' jam, 0 sisa_hari, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 seling,0 jam_std FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' tahun, '' jenis_aset, '' nama_perusahaan, '' no_polis, 0 realisasi_janji, '' tgl_awal, '' tgl_akhir, '' jam, 0 sisa_hari, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan,0 seling ,0 jam_std FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun, jenis_aset, nama_perusahaan, no_polis, realisasi_janji, cast(tgl_awal as char(10)) tgl_awal, cast(tgl_akhir as char(10)) tgl_akhir, jam, case when (DateDiff (Day,tgl_awal,tgl_akhir)-DateDiff (Day,tgl_awal,'$lntahunang-12-31')) < 0 then 0 else (DateDiff (Day,tgl_awal,tgl_akhir)-DateDiff (Day,tgl_awal,'$lntahunang-12-31')) end as sisa_hari, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan , (DateDiff (Day,tgl_awal,tgl_akhir)) seling  ,(((12-left(jam,2))*3600)+((right(jam,2)*-1)*60))jam_std FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' tahun, '' jenis_aset, '' nama_perusahaan, '' no_polis, 0 realisasi_janji, '' tgl_awal, '' tgl_akhir, '' jam, 0 sisa_hari,   SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ) a
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah,SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1109){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Koreksi</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Sudah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Koreksi</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Sudah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun, jumlah,  piutang_awal, piutang_koreksi, piutang_sudah, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1110){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Koreksi</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Sudah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Koreksi</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Sudah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun, jumlah,  piutang_awal, piutang_koreksi, piutang_sudah, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, ''  tahun, 0 jumlah, 0 piutang_awal, 0 piutang_koreksi, 0 piutang_sudah, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1103 || $rek3==1104 || $rek3==1106){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Awal</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Awal</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Piutang<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, '' lokasi, ''  tahun, 0 jumlah, 0 piutang_awal, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' lokasi, ''  tahun, 0 jumlah, 0 piutang_awal,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, '' lokasi, ''  tahun, 0 jumlah, 0 piutang_awal, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, lokasi, tahun, jumlah,  piutang_awal, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' lokasi, ''  tahun, 0 jumlah, 0 piutang_awal,  SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1102 || $rek3==1201 || $rek3==1202){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Dasar<br>Hukum</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kepemilikan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Investasi<br>Awal</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Investasi<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 

                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Dasar<br>Hukum</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kepemilikan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Investasi<br>Awal</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Investasi<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 

                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, '' hukum, ''  tahun, '' kepemilikan, 0 jumlah, 0 investasi_awal, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' hukum, ''  tahun, '' kepemilikan, 0 jumlah, 0 investasi_awal,  0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, hukum, tahun, kepemilikan, jumlah,  investasi_awal, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama, '' hukum, ''  tahun, '' kepemilikan, 0 jumlah, 0 investasi_awal, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1101){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Setoran<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>

                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Setoran<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td>

                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) as kode1, LEFT(kd_rek5,6) as kode,(SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama,  ''  tahun,  0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun, jumlah, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4=LEFT(kd_rek5,6)) as nama,  ''  tahun,  0 jumlah, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1401){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Dasar<br>Hukum</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td>
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian Akun</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Dasar<br>Hukum</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td>
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' hukum,0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode,(SELECT UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' hukum, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' hukum,0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  hukum, jumlah, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' hukum,0 jumlah, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3_64 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==1501 || $rek3==1502 || $rek3==1503 || $rek3==1504){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah/Luas</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">21</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">22</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah/Luas</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">21</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">22</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">23</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun,  '' merk, '' no_polisi,'' fungsi, '' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode,(SELECT UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, '' fungsi,'' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, ''  tahun,  '' merk, '' no_polisi, '' fungsi, '' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  merk, no_polisi, fungsi, lokasi, alamat, jumlah,  satuan, harga_satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, '' fungsi,'' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,  SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama, SUM(sal_awal) sal_awal,SUM(kurang) kurang,SUM(tambah) tambah,SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else if($rek3==2105){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>                    
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-familysize:12px\">Tahun<br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jenis Aset</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tanggal Kerjasama</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Akhir</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                  
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>                    
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-familysize:12px\">Tahun<br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jenis Aset</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tanggal Kerjasama</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Awal</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Akhir</td>
                    <td  width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                  
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, 
                        ''  tahun, '' lokasi ,'' jenis_aset , '' tgl_awal, '' tgl_akhir,
                        0 sal_awal,0 kurang, 0 tambah, 0 tahun_n, 0 koreksi,'' keterangan 
                        FROM lamp_aset 
                        where kd_rek3 = '$rek3' and kd_skpd = '$skpd'                    
                    UNION
                        SELECT '' no_lamp, LEFT(kd_rek6,6) AS kode1, LEFT(kd_rek6,6) AS kode,
                         (SELECT UPPER(nm_rek4) FROM ms_rek4 WHERE kd_rek4 = LEFT(kd_rek6,6)) AS nama, 
                        ''  tahun, '' lokasi ,'' jenis_aset , '' tgl_awal, '' tgl_akhir,
                        0 sal_awal,0 kurang, 0 tambah, 0 tahun_n, 0 koreksi,'' keterangan 
                        FROM lamp_aset 
                        where kd_rek3 = '$rek3' and kd_skpd = '$skpd'   
                    UNION ALL
                        SELECT  no_lamp, 
                        case when (kd_rek6='') then kd_rek6 else kd_rek6 end as kode1, 
                        case when (kd_rek6='') then kd_rek6 else kd_rek6 end as kode, 
                        case when (kd_rek6='') then nm_rek6 else nm_rek6 end as nama, tahun,  lokasi , jenis_aset ,  
                        cast(tgl_awal as char(10)) tgl_awal, cast(tgl_akhir as char(10)) tgl_akhir,
                        sal_awal,  kurang,  tambah,
                        tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi,
                         keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'  
                    -- UNION ALL
                    --     SELECT 'x' no_lamp, LEFT(kd_rek6,6)+'99' AS kode1, '' AS kode,
                    --     (SELECT 'TOTAL '+UPPER(nm_rek4) FROM ms_rek4 WHERE kd_rek4 = LEFT(kd_rek6,6)) AS nama, ''  tahun,
                    --     '' lokasi ,'' jenis_aset , '' tgl_awal, '' tgl_akhir, 
                    --     SUM(sal_awal) sal_awal, 
                    --     SUM(kurang) kurang, 
                    --     SUM(tambah) tambah, 
                    --     SUM(tahun_n) tahun_n, 
                    --     SUM(isnull(korplus,0)-isnull(kormin,0)) koreksi,
                    --     '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek6,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT   (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama,
                       SUM(sal_awal) as sal_awal,  sum(kurang) as kurang,  SUM(tambah) as tambah, sum(tahun_n) as tahun_n, 
                       sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' 
                           and kd_skpd = '$skpd'"))->first();
        }else if($rek3==2102 || $rek3==2103 || $rek3==2106 || $rek3==1108 || $rek3==2210 || $rek3==2202){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">NO Lampiran </td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td rowspan=\"2\" width=\"10%\" bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td  width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, UPPER(nm_rek3) as nama, ''  tahun, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi,'' keterangan 
                        FROM lamp_aset 
                        where kd_rek3 = '$rek3' and kd_skpd = '$skpd'                    
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,6) AS kode1, LEFT(kd_rek5,6) AS kode,
                         (SELECT UPPER(nm_rek4) FROM ms_rek4 WHERE kd_rek4 = LEFT(kd_rek5,6)) AS nama,
                         ''  tahun, 0 sal_awal, 0 kurang, 0 tambah,0 tahun_n, 0 koreksi,'' keterangan 
                        FROM lamp_aset 
                        where kd_rek3 = '$rek3' and kd_skpd = '$skpd'    
                         UNION ALL
                        SELECT  no_lamp, 
                        case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, 
                        case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, 
                        case when (kd_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  sal_awal,  kurang,  tambah,
                        tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi,
                         keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd'  
                        --  UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,6)+'99' AS kode1, '' AS kode,
                        -- (SELECT 'TOTAL '+UPPER(nm_rek4) FROM ms_rek4 WHERE kd_rek4 = LEFT(kd_rek5,6)) AS nama, ''  tahun, 
                        -- SUM(sal_awal) sal_awal, 
                        -- SUM(kurang) kurang, 
                        -- SUM(tambah) tambah, 
                        -- SUM(tahun_n) tahun_n, 
                        -- SUM(isnull(korplus,0)-isnull(kormin,0)) koreksi,
                        -- '' keterangan FROM lamp_aset where kd_rek3 = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,6)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT   (SELECT UPPER(nm_rek3) from ms_rek3 where kd_rek3 = '$rek3') as nama,
                             SUM(sal_awal) as sal_awal,  sum(kurang) as kurang,  SUM(tambah) as tambah, sum(tahun_n) as tahun_n, 
                             sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where kd_rek3 = '$rek3' 
                                 and kd_skpd = '$skpd'"))->first();
        }else if($rek3==15){
            if($cetakan=='1') {
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah/Luas</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">21</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">22</td> 
                </tr>
                </thead>";
            }else{
                $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                <thead>
                <tr>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No Lampiran</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kode Rekening </td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Uraian</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Tahun <br>Perolehan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Merk/Type</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">No. Polisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Fungsi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Lokasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Alamat</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Jumlah/Luas</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Satuan</td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" style=\"font-size:12px\">Harga<br>Satuan</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Awal</td>
                    <td colspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Mutasi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Pengadaan<br>Tahun $lntahunang</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Saldo<br>Akhir</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Koreksi BPK</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Audited</td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Kondisi</td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" style=\"font-size:12px\">Keterangan</td>
                </tr>
                <tr>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Berkurang</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">Bertambah</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">B</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RR</td>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px\">RB</td>
                </tr>
                <tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">21</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">22</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">23</td> 
                </tr>
                </thead>";
            }
            $query = DB::select("SELECT '' no_lamp, kd_rek2 as kode1, kd_rek2 as kode, nm_rek2 as nama, ''  tahun,  '' merk, '' no_polisi,'' fungsi, '' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM ms_rek2_64 where kd_rek2='$rek3'
                        UNION
                        SELECT '' no_lamp, kd_rek3 as kode1, kd_rek3 as kode, nm_rek3 as nama, ''  tahun,  '' merk, '' no_polisi,'' fungsi, '' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, LEFT(kd_rek5,5) as kode1, LEFT(kd_rek5,5) as kode,(SELECT nm_rek4_64 from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, '' fungsi,'' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,   0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd'
                        UNION
                        SELECT '' no_lamp, case when (kd_rek6<>'') then kd_rek5 end as kode1, case when (kd_rek6<>'') then kd_rek5 end as kode, case when (kd_rek6<>'') then nm_rek5 end as nama, case when (kd_rek6<>'') then '' end tahun,  case when (kd_rek6<>'') then '' end merk, case when (kd_rek6<>'') then '' end no_polisi, case when (kd_rek6<>'') then '' end fungsi, case when (kd_rek6<>'') then '' end lokasi, case when (kd_rek6<>'') then '' end alamat, case when (kd_rek6<>'') then 0 end jumlah, case when (kd_rek6<>'') then '' end satuan, case when (kd_rek6<>'') then 0 end harga_satuan,  case when (kd_rek6<>'') then 0 end sal_awal, case when (kd_rek6<>'') then 0 end kurang, case when (kd_rek6<>'') then 0 end tambah, case when (kd_rek6<>'') then 0 end tahun_n, case when (kd_rek6<>'') then 0 end koreksi, case when (kd_rek6<>'') then '' end kondisi_b, case when (kd_rek6<>'') then '' end kondisi_rr, case when (kd_rek6<>'') then '' end kondisi_rb, case when (kd_rek6<>'') then '' end keterangan FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd'
                        UNION ALL
                        SELECT no_lamp, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode1, case when (kd_rek6='') then kd_rek5 else kd_rek6 end as kode, case when (nm_rek6='') then nm_rek5 else nm_rek6 end as nama, tahun,  merk, no_polisi, fungsi, lokasi, alamat, jumlah,  satuan, harga_satuan, sal_awal,  kurang,  tambah, tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, kondisi_b,  kondisi_rr,  kondisi_rb,  keterangan FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd'
                        -- UNION ALL
                        -- SELECT 'x' no_lamp, LEFT(kd_rek5,5)+'99' as kode1, '' as kode, (SELECT 'TOTAL '+UPPER(nm_rek4_64) from ms_rek4_64 where kd_rek4_64=LEFT(kd_rek5,5)) as nama, ''  tahun,  '' merk, '' no_polisi, '' fungsi,'' lokasi, '' alamat, 0 jumlah, '' satuan, 0 harga_satuan,  SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi, 0 kondisi_b, 0 kondisi_rr, 0 kondisi_rb, '' keterangan FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd' group by left(kd_rek5,5)
                        ORDER BY kode1, tahun");
            $query_jum = collect(DB::select("SELECT (SELECT UPPER(nm_rek2) from ms_rek2_64 where kd_rek2 = '$rek3') as nama, SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah)  tambah, SUM(tahun_n) tahun_n, sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset where left(kd_rek3,2) = '$rek3' and kd_skpd = '$skpd'"))->first();
        }else{
            $head="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                 <tr>
                    <td colspan=\"5\" style=\"border-top:none;border-right:none;border-left:none;font-size:12px\" align=\"center\">
                    BELUM ADA CETAKAN                                        
                    </td>           
                </tr>
                </table>";
            $query = DB::select("SELECT 0 ");
            $query_jum = collect(DB::select("SELECT 0 "))->first();
        }

        $namanya = collect(DB::select("SELECT nm_rek3 from ms_rek3 where kd_rek3='$rek3'"))->first();


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'          => $query,
            'query_jum'      => $query_jum,
            'namanya'      => $namanya,
            'head'           => $head,
            'cetakan'        => $cetakan,
            'skpd'           => $skpd,
            'rek3'           => $rek3,
            'lntahunang'     => $lntahunang,
            'thn_ang1'       => $thn_ang1         
            ];
        // dd($data['ekuitas_awal']->nilai);
            $view =  view('akuntansi.cetakan.lamp_neraca.lamp_neraca')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Lampiran Neraca.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Lampiran Neraca.xls"');
            return $view;
        }
    }

    public function cetak_umur_piutang(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $skpd = $request->kd_skpd;
        $lntahunang = $request->tahun;       
        $bulan = 12+1;
        $cetak   = $request->cetak;
        
        // dd($kd_skpd);
        
        // $lntahunang    = tahun_anggaran();
        // $thn_ang1   = $lntahunang-1;

        $query = DB::select("SELECT '' no_lamp, kd_rek3 kode1, kd_rek3 kode, UPPER(nm_rek3) nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                         0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  and kd_skpd='$skpd' and tahun<= $lntahunang
                         union

                        select '' no_lamp, LEFT(kd_rek6,6) kode1, LEFT(kd_rek6,6) kode, 
                        (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,6)) nama, 
                        '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                        0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                        union

                        select '' no_lamp, LEFT(kd_rek6,8) kode1, LEFT(kd_rek6,8) kode, 
                        (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,8)) nama, 
                        '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                        0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang

                          union all

                        select no_lamp, kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal,  kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, 
                        case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 'Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3  then 'Kurang Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 'Diragukan'
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 'Macet'

                        when (left(kode1,4)in('1103', '1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 'Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 'Macet'
                        when (left(kode1,4)in('1103', '1106' )) and umur_t>=6 then 'Macet'
                        
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 'Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 'Kurang Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 'Diragukan'
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 'Macet'
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 'Kurang Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 'Diragukan'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 'Macet'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 'Macet'
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103', '1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103', '1106' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as penyi_piu 
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when $bulan-isnull(bulan,1)=12 then $lntahunang-tahun+1 else $lntahunang-tahun end 
                        as umur_t, case when $bulan-isnull(bulan,1)=12 then 0 else $bulan-isnull(bulan,1) end as umur_b 
                        FROM lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang) a
                        union all

                        select 'x' no_lamp, LEFT(kode1,5)+'988' kode1, '' kode, (SELECT 'TOTAL '+UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kode1,5)) nama, 
                        '' lokasi, 0 tahun, 0 bulan, 0 jumlah, sum(sal_awal) sal_awal, sum(kurang) kurang, 
                        sum(tambah) tambah, sum(tahun_n) tahun_n, sum(koreksi) koreksi, '' keterangan, 0 umur_t, 
                        0 umur_b, '' kualitas, sum(penyi_piu) penyi_piu
                        from (select kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal, kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 'Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3  then 'Kurang Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 'Diragukan'
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 'Macet'

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 'Macet'
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 'Macet'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 'Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 'Kurang Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 'Diragukan'
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 'Macet'
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 'Kurang Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 'Diragukan'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 'Macet'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 'Macet'
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as penyi_piu 
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when $bulan-isnull(bulan,1)=12 then $lntahunang-tahun+1 else $lntahunang-tahun end 
                        as umur_t, case when $bulan-isnull(bulan,1)=12 then 0 else $bulan-isnull(bulan,1) end as umur_b 
                        FROM lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang) a) a group by left(kode1,5)
                        union all
                        select 'xx' no_lamp, '99999999' kode1, '' kode, 'TOTAL PIUTANG PENDAPATAN' nama, 
                        '' lokasi, 0 tahun, 0 bulan, 0 jumlah, sum(sal_awal) sal_awal, sum(kurang) kurang, 
                        sum(tambah) tambah, sum(tahun_n) tahun_n, sum(koreksi) koreksi, '' keterangan, 0 umur_t, 
                        0 umur_b, '' kualitas, sum(penyi_piu) penyi_piu
                        from (select kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal, kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 'Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3  then 'Kurang Lancar'
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 'Diragukan'
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 'Macet'

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 'Macet'
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 'Macet'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 'Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 'Kurang Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 'Diragukan'
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 'Macet'
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 'Kurang Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 'Diragukan'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 'Macet'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 'Macet'
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        -- cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as penyi_piu 
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when $bulan-isnull(bulan,1)=12 then $lntahunang-tahun+1 else $lntahunang-tahun end 
                        as umur_t, case when $bulan-isnull(bulan,1)=12 then 0 else $bulan-isnull(bulan,1) end as umur_b 
                        FROM lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang) a) a
                        union
                        select '' no_lamp, LEFT(kd_rek6,6)+'9999' kode1, '' kode, '' nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                        order by kode1, tahun, bulan ,sal_awal ");  
             
        


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $skpd)->first();
            // dd($sus);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'          => $query,
            'skpd'           => $skpd,
            'lntahunang'     => $lntahunang  
            ];
        // dd($data['ekuitas_awal']->nilai);
            $view =  view('akuntansi.cetakan.lamp_neraca.umur_piutang')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Umur Piutang.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Umur Piutang.xls"');
            return $view;
        }
    }

    public function cetak_penyisihan_piutang(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $skpd = $request->kd_skpd;
        $lntahunang = $request->tahun;
        $lntahunang_1   = $lntahunang-1;       
        $bulan = 12+1;
        $cetak   = $request->cetak;
        
        // dd($kd_skpd);
        
        // $lntahunang    = tahun_anggaran();
        // $thn_ang1   = $lntahunang-1;
        if ($skpd=='1.02.0.00.0.00.02.0000') {
            $query = DB::select("SELECT a.no_lamp,a.kode1,a.kode,a.nama,a.lokasi,a.tahun,a.bulan,a.jumlah,a.sal_awal,a.kurang,a.tambah,a.tahun_n,a.koreksi,a.keterangan,a.umur_t,a.umur_b,a.kualitas,b.kualitas as kualitas_lalu,a.penyi_piu,
                        b.sal_akhir,b.piu_tahun_lalu,(b.sal_awal*b.kualitas)piu_lalu from
                        (
                        SELECT '' no_lamp, kd_rek3 kode1, kd_rek3 kode, UPPER(nm_rek3) nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                     0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  and kd_skpd='$skpd' and tahun<= $lntahunang
                     union

                    select '' no_lamp, LEFT(kd_rek6,6) kode1, LEFT(kd_rek6,6) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,6)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                    union

                    select '' no_lamp, LEFT(kd_rek6,8) kode1, LEFT(kd_rek6,8) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,8)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang

                      union all

                        select no_lamp, kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal,  kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, 
                        case 
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 'Lancar'
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3  then 'Kurang Lancar'
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 'Diragukan'
                        when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 'Macet'
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=0 and isnull(umur_b,1)>=1 then 'Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)>=1 then 'Macet'
                        when (left(kode1,4)in('1103' )) and umur_t>=6 then 'Macet'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 'Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 'Kurang Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 'Diragukan'
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 'Macet'
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 'Kurang Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 'Diragukan'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 'Macet'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 'Macet'
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as penyi_piu 
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when 13-isnull(bulan,1)=12 then $lntahunang-tahun+1 else $lntahunang-tahun end 
                        as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b 
                        FROM lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang) a
                        
                        union
                        select '' no_lamp, LEFT(kd_rek6,6)+'9999' kode1, '' kode, '' nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                        )a




                    left join 
                    (SELECT '' no_lamp,kd_rek3 kode1, kd_rek3 kode, UPPER(nm_rek3) nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                     0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, 0 kualitas, 0 piu_tahun_lalu , 0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  and kd_skpd='$skpd' and tahun<= $lntahunang_1
                     union

                    select '' no_lamp, LEFT(kd_rek6,6) kode1, LEFT(kd_rek6,6) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,6)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1
                    union

                    select '' no_lamp, LEFT(kd_rek6,8) kode1, LEFT(kd_rek6,8) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,8)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0, '' keterangan, 0 umur_t, 
                    0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1

                      union all

                        select no_lamp, kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal,  kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, 
                        case 
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 0.005
                       when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 2 then 0.1
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 0.5
                       when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 1
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)=0 then 0.005
                        when (left(kode1,4)in('1103' )) and umur_t=0 and isnull(umur_b,1)>=1 then 0.005
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)>=1 then 0.1
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)=0 then 0.1
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)>=1 then 1
                        when (left(kode1,4)in('1103' )) and umur_t>=6 then 1
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 0.005
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 0.01
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 0.5
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 1
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 0.005
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 0.1
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 0.5
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 1
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 1
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as piu_tahun_lalu , (sal_awal-kurang+tambah+tahun_n) sal_akhir
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when 13-isnull(bulan,1)=12 then $lntahunang_1-tahun+1 else $lntahunang_1-tahun end 
                        as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b,ISNULL((sal_awal-kurang+tambah+tahun_n),0) sal_akhir
                        FROM lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang_1) a
                        
                        
                        union
                        select '' no_lamp, LEFT(kd_rek6,6)+'9999' kode1, '' kode, '' nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                        from lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1
                        ) b on a.no_lamp=b.no_lamp and a.kode1=b.kode1 and a.kode=b.kode 
                                                order by a.kode1, a.tahun, a.bulan ,a.sal_awal");  
        }else{
            $query = DB::select("SELECT a.no_lamp,a.kode1,a.kode,a.nama,a.lokasi,a.tahun,a.bulan,a.jumlah,a.sal_awal,a.kurang,a.tambah,a.tahun_n,a.koreksi,a.keterangan,a.umur_t,a.umur_b,a.kualitas,b.kualitas as kualitas_lalu,a.penyi_piu,
                    b.sal_akhir,b.piu_tahun_lalu,(b.sal_awal*b.kualitas)piu_lalu from
                    (
                    SELECT '' no_lamp, kd_rek3 kode1, kd_rek3 kode, UPPER(nm_rek3) nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                     0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  and kd_skpd='$skpd' and tahun<= $lntahunang
                     union

                    select '' no_lamp, LEFT(kd_rek6,6) kode1, LEFT(kd_rek6,6) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,6)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                    union

                    select '' no_lamp, LEFT(kd_rek6,8) kode1, LEFT(kd_rek6,8) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,8)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, '' kualitas, 0 penyi_piu 
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang

                      union all

                        select no_lamp, kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal,  kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, 
                        case 
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 'Lancar'
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3  then 'Kurang Lancar'
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 'Diragukan'
                        --when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 'Macet'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 'Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 'Kurang Lancar'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 'Diragukan'
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 'Macet'
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 'Macet'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 'Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 'Kurang Lancar'
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 'Diragukan'
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 'Macet'
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 'Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 'Kurang Lancar'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 'Diragukan'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 'Macet'
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 'Macet'
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 3 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as penyi_piu 
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when 13-isnull(bulan,1)=12 then $lntahunang-tahun+1 else $lntahunang-tahun end 
                        as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b 
                        FROM lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang) a
                        
                        union
                        select '' no_lamp, LEFT(kd_rek6,6)+'9999' kode1, '' kode, '' nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, '' kualitas, 0 penyi_piu 
                        from lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang
                        )a

                    left join 
                    (SELECT '' no_lamp,kd_rek3 kode1, kd_rek3 kode, UPPER(nm_rek3) nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                     0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, 0 kualitas, 0 piu_tahun_lalu , 0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  and kd_skpd='$skpd' and tahun<= $lntahunang_1
                     union

                    select '' no_lamp, LEFT(kd_rek6,6) kode1, LEFT(kd_rek6,6) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,6)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 
                    0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1
                    union

                    select '' no_lamp, LEFT(kd_rek6,8) kode1, LEFT(kd_rek6,8) kode, 
                    (SELECT UPPER(nm_rek4) from ms_rek4 where kd_rek4 = LEFT(kd_rek6,8)) nama, 
                    '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 0 kurang, 0 tambah, 0 tahun_n, 0, '' keterangan, 0 umur_t, 
                    0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                    from lamp_aset 
                    where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1

                      union all

                        select no_lamp, kode1, kode, nama, lokasi, tahun, bulan, jumlah,  sal_awal,  kurang,  
                        tambah, tahun_n, koreksi, keterangan, umur_t, isnull(umur_b,1) umur_b, 
                        case 
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 0.005
                       -- when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 2 then 0.01
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 0.5
                       -- when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 1
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 0.005
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 0.005
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 0.1
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 0.1
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 0.5
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 1
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 1
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then 0.005
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 0.01
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 0.5
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 1
                        when left(kode1,6)='110613' then ''
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 0.005
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 0.1
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 0.5
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 1
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 1
                        else 'Tidak Diketahui' end as kualitas,
                         case 
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 0 and 1 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 1 and 2 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=0 and isnull(umur_b,1) between 3 and 12 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        --when (left(kode1,4)in('1106' )) and umur_t=1 and isnull(umur_b,1)>=12 then 
                        --cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))

                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=0 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=1 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=2 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=3 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=4 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t=5 and isnull(umur_b,1)>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1103','1106' )) and umur_t>=6 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)<=1 then
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4)in('1104')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when left(kode1,6)='110613' then 0
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=0 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.005 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 0.1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)=2 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi)* 0.5 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t=0 and isnull(umur_b,1)>=3 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        when (left(kode1,4) not in ('1103','1104') or left(kode1,6) not in ('110613')) and umur_t>=1 then 
                        cast((sal_awal-kurang+tambah+tahun_n+koreksi) * 1 as decimal(20,2))
                        else 'Tidak Diketahui' end as piu_tahun_lalu , (sal_awal-kurang+tambah+tahun_n) sal_akhir
                        from
                        (SELECT no_lamp, kd_rek6 as kode1, kd_rek6 as kode, nm_rek6 as nama, lokasi, tahun, isnull(bulan,1) bulan, jumlah,   
                        isnull(sal_awal,0)sal_awal,  isnull(kurang,0) kurang,  isnull(tambah,0) tambah, isnull(tahun_n,0)tahun_n, isnull(korplus,0)-isnull(kormin,0) koreksi, keterangan, case when 13-isnull(bulan,1)=12 then $lntahunang_1-tahun+1 else $lntahunang_1-tahun end 
                        as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b,ISNULL((sal_awal-kurang+tambah+tahun_n),0) sal_akhir
                        FROM lamp_aset 
                        where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd ='$skpd' and tahun<= $lntahunang_1) a
                        
                        
                        union
                        select '' no_lamp, LEFT(kd_rek6,6)+'9999' kode1, '' kode, '' nama, '' lokasi, 0 tahun, 0 bulan, 0 jumlah, 0 sal_awal, 
                        0 kurang, 0 tambah, 0 tahun_n, 0 koreksi, '' keterangan, 0 umur_t, 0 umur_b, 0 kualitas, 0 piu_tahun_lalu ,0 sal_akhir
                        from lamp_aset where kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109') and kd_skpd='$skpd' and tahun<= $lntahunang_1
                        ) b on a.no_lamp=b.no_lamp and a.kode1=b.kode1 and a.kode=b.kode 
                                                order by a.kode1, a.tahun, a.bulan ,a.sal_awal");
        }
             
        


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $skpd)->first();
            // dd($sus);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'          => $query,
            'skpd'           => $skpd,
            'lntahunang'     => $lntahunang,
            'lntahunang_1'     => $lntahunang_1  
            ];
        // dd($data['ekuitas_awal']->nilai);
            $view =  view('akuntansi.cetakan.lamp_neraca.penyisihan_piutang')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Umur Piutang.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Umur Piutang.xls"');
            return $view;
        }
    }

    public function cetak_ikhtisar(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan = $request->bulan;  
        $anggaran = $request->jns_ang;
        $cetak   = $request->cetak;
        
        // dd($kd_skpd);
        
        $lntahunang    = tahun_anggaran();
        // $thn_ang1   = $lntahunang-1;

        $query = DB::select("SELECT seq, kd_skpd, kd_kegiatan, nm_rek, anggaran, realisasi FROM BABIII($bulan,'$anggaran',$lntahunang) ORDER BY kd_skpd");  
             
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $skpd)->first();
            // dd($sus);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'          => $query,
            'bulan'           => $bulan,
            'lntahunang'     => $lntahunang  
            ];
        // dd($data['ekuitas_awal']->nilai);
            $view =  view('akuntansi.cetakan.lamp_neraca.ikhtisar')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Umur Piutang.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Umur Piutang.xls"');
            return $view;
        }
    }
}
