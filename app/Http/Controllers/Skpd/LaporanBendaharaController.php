<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;

class LaporanBendaharaController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd,'kode'=>'BK'])->orderBy('nip')->orderBy('nama')->get(),
            'pa_kpa' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode',['PA','KPA'])->orderBy('nip')->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran()
        ];

        return view('skpd.laporan_bendahara.index')->with($data);
    }
    
// get skpd by radio    
    public function cariSkpd(Request $request)
    {   
        $type       = Auth::user()->is_admin;
        $jenis      = $request->jenis;
        $kd_skpd    = $request->kd_skpd;
        $kd_org     = substr($kd_skpd,0,17);
        if ($type=='1'){
            if($jenis=='skpd'){
                $data   = DB::table('ms_organisasi')->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->orderBy('kd_org')->get();
            }else{
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        }else{
            if($jenis=='skpd'){
                $data   = DB::table('ms_organisasi')->where(DB::raw("LEFT(kd_org)"),'=',$kd_org)->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->get();
            }else{
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd)"),'=',$kd_org)->select('kd_skpd', 'nm_skpd')->get();
            }
        }

        return response()->json($data);
    }

// get bendahara pengeluaran
    function cariBendahara(Request $request)
    {
        if(strlen($request->kd_skpd)=='17'){
            $kd_skpd    = $request->kd_skpd.'.0000';
        }else{
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd,'kode'=>'BK'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);

    }

    function cariPaKpa(Request $request)
    {
        if(strlen($request->kd_skpd)=='17'){
            $kd_skpd    = $request->kd_skpd.'.0000';
        }else{
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode',['PA','KPA'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);

    }
   

    // Cetak List
    public function cetakbku(Request $request)
    {
            $tanggal_ttd    = $request->tgl_ttd ;
            $pa_kpa         = $request->pa_kpa ;
            $bendahara      = $request->bendahara ;
            $bulan          = $request->bulan;
            $enter          = $request->spasi;
            $kd_skpd        = Auth::user()->kd_skpd;
            $tahun_anggaran = tahun_anggaran();

            // TANDA TANGAN
            $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
            $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();

            $data_tahun_lalu = DB::table('ms_skpd')
            ->select(DB::raw('isnull(sld_awal,0) AS nilai'),'sld_awalpajak')
            ->where('kd_skpd', $kd_skpd)
            ->first();
            $data_sawal1 = DB::table('trhrekal as a')
            ->select('kd_skpd', 'tgl_kas', 'tgl_kas AS tanggal', 'no_kas', DB::raw("'' AS kegiatan"),
                    DB::raw("'' AS rekening"),'uraian',DB::raw("'0' AS terima"),DB::raw("'0' AS keluar"), DB::raw("'' AS st"), 'jns_trans')
            ->where(DB::raw("month(tgl_kas)"),'<', $bulan)
            ->where(DB::raw("YEAR(tgl_kas)"), $tahun_anggaran)
            ->where('kd_skpd', $kd_skpd);
            
            $data_sawal2 = DB::table('trdrekal as a')
                ->leftjoin('trhrekal as b', function ($join) {
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->where(DB::raw("month(b.tgl_kas)"),'<', $bulan)->where(DB::raw("YEAR(b.tgl_kas)"), $tahun_anggaran)
                ->where('b.kd_skpd', $kd_skpd)->select('b.kd_skpd','b.tgl_kas', DB::raw(" '' AS tanggal"),'a.no_kas','a.kd_sub_kegiatan as kegiatan','a.kd_rek6 AS rekening', 'a.nm_rek6 AS uraian', 
                        DB::raw("CASE WHEN a.keluar + a.terima <0 THEN (a.keluar*-1) ELSE a.terima END as terima"), 
                        DB::raw("CASE WHEN a.keluar+a.terima<0 THEN (a.terima*-1) ELSE a.keluar END as keluar"),
                        DB::raw("case when a.terima<>0 then '1' else '2' end AS st"), 'b.jns_trans' )
                ->unionAll($data_sawal1)
                ->distinct();
            
            $result = DB::table(DB::raw("({$data_sawal2->toSql()}) AS sub"))
                ->select(DB::raw('SUM(terima) AS terima'),DB::raw('SUM(keluar) AS keluar'),DB::raw('SUM(terima) - SUM(keluar) AS sel'))
                ->mergeBindings($data_sawal2)
                ->first();
            
            
            // RINCIAN
            $rincian1 = DB::table('trhrekal as a')
            ->select('kd_skpd', 'tgl_kas', 'tgl_kas AS tanggal', 'no_kas', DB::raw("'' AS kegiatan"),
                    DB::raw("'' AS rekening"),'uraian',DB::raw("'0' AS terima"),DB::raw("'0' AS keluar"), DB::raw("'' AS st"), 'jns_trans')
            ->where(DB::raw("month(tgl_kas)"),'=', $bulan)
            ->where(DB::raw("YEAR(tgl_kas)"), $tahun_anggaran)
            ->where('kd_skpd', $kd_skpd);
            
            $rincian2 = DB::table('trdrekal as a')
                ->leftjoin('trhrekal as b', function ($join) {
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->where(DB::raw("month(b.tgl_kas)"),'=', $bulan)->where(DB::raw("YEAR(b.tgl_kas)"), $tahun_anggaran)
                ->where('b.kd_skpd', $kd_skpd)->select('b.kd_skpd','b.tgl_kas',DB::raw(" '' AS tanggal"),'a.no_kas','a.kd_sub_kegiatan as kegiatan','a.kd_rek6 AS rekening', 'a.nm_rek6 AS uraian', 
                        DB::raw("CASE WHEN a.keluar + a.terima <0 THEN (a.keluar*-1) ELSE a.terima END as terima"), 
                        DB::raw("CASE WHEN a.keluar+a.terima<0 THEN (a.terima*-1) ELSE a.keluar END as keluar"),
                        DB::raw("case when a.terima<>0 then '1' else '2' end AS st"), 'b.jns_trans' )
                ->unionAll($rincian1)
                ->distinct();

            $result_rincian = DB::table(DB::raw("({$rincian2->toSql()}) AS sub"))
            ->orderBy('tgl_kas')
            ->orderBy(DB::raw("CAST(no_kas AS INT)"))
            ->orderBy('jns_trans')
            ->orderBy('st')
            ->orderBy('rekening')
            ->mergeBindings($rincian2)
            ->get();

        // SALDO TUNAI
        // DB::select('exec my_stored_procedure(?,?,..)',array($Param1,$param2));
        $tunai_lalu = DB::select("exec kas_tunai_lalu ?,?", array($kd_skpd,$bulan));
        $tunai      = DB::select("exec kas_tunai ?,?", array($kd_skpd,$bulan));


        $terima_lalu = 0;
        $keluar_lalu = 0;
        foreach($tunai_lalu as $lalu){
            $terima_lalu += $lalu->terima;
            $keluar_lalu += $lalu->keluar;
        }
        
        $terima = 0;
        $keluar = 0;
        foreach($tunai as $sekarang){
            $terima += $sekarang->terima;
            $keluar += $sekarang->keluar;
        }
        // KAS BANK
        $kas_bank = sisa_bank_by_bulan($bulan);

        // KAS SALDO BERHARGA
        $surat_berharga = DB::table('trhsp2d')
        ->select(DB::raw('isnull(sum(nilai),0) AS nilai'))
        ->where(DB::raw("month(tgl_terima)"),'=', $bulan)
        ->where(['kd_skpd' => $kd_skpd, 'status_terima' => '1'])
        ->where(function($query) use($bulan){
            $query->where(DB::raw('month(tgl_kas)'),'=', $bulan)->orWhereNull('no_kas')->orWhere('no_kas', '');
        })
        ->first();

        // SALDO PAJAK
        $saldo_pajak_1 =DB::table('trhtrmpot as a')
                        ->select('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd',
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)< $bulan THEN b.nilai ELSE 0 END) AS terima_lalu"),        
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)= $bulan THEN b.nilai ELSE 0 END) AS terima_ini"),
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)<= $bulan THEN b.nilai ELSE 0 END) AS terima"),
                                DB::raw("0 AS setor_lalu"),
                                DB::raw("0 AS setor_ini"),
                                DB::raw("0 AS setor"))
                        ->join('trdtrmpot as b', function ($join) {
                            $join->on('a.no_bukti', '=', 'b.no_bukti');
                            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                            })
                        ->where('a.kd_skpd', $kd_skpd)
                        ->groupBy('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd');
        
        $saldo_pajak_2 =DB::table('trhstrpot as a')
                        ->select('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd',
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)< $bulan THEN b.nilai ELSE 0 END) AS terima_lalu"),        
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)= $bulan THEN b.nilai ELSE 0 END) AS terima_ini"),
                                DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)<= $bulan THEN b.nilai ELSE 0 END) AS terima"),
                                DB::raw("0 AS setor_lalu"),
                                DB::raw("0 AS setor_ini"),
                                DB::raw("0 AS setor"))
                        ->join('trdstrpot as b', function ($join) {
                            $join->on('a.no_bukti', '=', 'b.no_bukti');
                            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                            })
                        ->leftJoin('trhsp2d as c', function ($join) {
                            $join->on('a.no_bukti', '=', 'c.no_sp2d');
                            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
                            })
                        ->where('a.kd_skpd', $kd_skpd)
                        ->groupBy('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd')
                        ->unionAll($saldo_pajak_1);
                        
        $saldo_pajak_3 = DB::table('ms_pot as a')
                        ->select(DB::raw('RTRIM(a.map_pot) as kd_rek6'),'a.nm_rek6')
                        ->whereIn('a.kd_rek6',['210106010001','210105020001','210105010001','210105030001','210109010001']);

        $saldo_pajak1 = DB::table($saldo_pajak_3,'a')
                        ->leftJoinSub($saldo_pajak_2,'b', function ($join) {
                            $join->on('a.kd_rek6', '=', 'b.kd_rek6');
                        })->distinct()->get();

        $sisa_pajak             = 0;
        foreach($saldo_pajak1 as $pajak1){
            $sisa_pajak             += $pajak1->terima_ini+$pajak1->terima_lalu - $pajak1->setor_lalu - $pajak1->setor_ini;
        }


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();


                // $saldo_pajak10 = DB::select("SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima, ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, ISNULL(SUM(terima)-SUM(setor),0) as sisa
                //             FROM
                //             (
                //             SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001','210109010001'))a
                //             LEFT JOIN 
                //             (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
                //             SUM(CASE WHEN MONTH(tgl_bukti)<? THEN b.nilai ELSE 0 END) AS terima_lalu,
                //             SUM(CASE WHEN MONTH(tgl_bukti)=? THEN b.nilai ELSE 0 END) AS terima_ini,
                //             SUM(CASE WHEN MONTH(tgl_bukti)<=? THEN b.nilai ELSE 0 END) AS terima,
                //             0 as setor_lalu,
                //             0 as setor_ini,
                //             0 as setor
                //             FROM trhtrmpot a
                //             INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                //             LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
                //             WHERE a.kd_skpd=?								
                //             GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

                //             UNION ALL

                //             SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
                //             0 as terima_lalu,
                //             0 as terima_ini,
                //             0 as terima,
                //             SUM(CASE WHEN MONTH(tgl_bukti)<? THEN b.nilai ELSE 0 END) AS setor_lalu,
                //             SUM(CASE WHEN MONTH(tgl_bukti)=? THEN b.nilai ELSE 0 END) AS setor_ini,
                //             SUM(CASE WHEN MONTH(tgl_bukti)<=? THEN b.nilai ELSE 0 END) AS setor
                //             FROM trhstrpot a
                //             INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                //             LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
                //             WHERE a.kd_skpd=?				
                //             GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd
                //         )b ON a.kd_rek6=b.kd_rek6", [$bulan,$bulan,$bulan,$kd_skpd,$bulan,$bulan,$bulan,$kd_skpd]);

        // KIRIM KE VIEW
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan','logo_pemda_hp')->first(),
                'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
                'bulan'             => $bulan,
                'data_sawal'        => $result,
                'data_rincian'      => $result_rincian,
                'data_tahun_lalu'   => $data_tahun_lalu,
                'tunai_lalu'        => $tunai_lalu,
                'tunai'             => $tunai,
                'terima_lalu'       => $terima_lalu,
                'keluar_lalu'       => $keluar_lalu,
                'terima'            => $terima,
                'keluar'            => $keluar,
                'saldo_bank'        => $kas_bank,
                'surat_berharga'    => $surat_berharga,
                'pajak'             => $sisa_pajak,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'cari_pa_kpa'       => $cari_pakpa,
                'cari_bendahara'    => $cari_bendahara
            ];

        return view('skpd.laporan_bendahara.cetak.bku')->with($data);
    }

    public function cetakSpjFungsional(Request $request)
    {   
        
        $tanggal_ttd    = $request->tgl_ttd ;
        $pa_kpa         = $request->pa_kpa ;
        $bendahara      = $request->bendahara ;
        $jns_anggaran   = $request->jns_anggaran ;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $kd_skpd        = Auth::user()->kd_skpd;
        $tahun_anggaran = tahun_anggaran();
        // get daerah
        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // get bendahara
        $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
        // get pa kpa
        $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();

        // tabel rincian
        $rincian = DB::select("exec spj_skpd ?,?,?", array($kd_skpd,$bulan,$jns_anggaran));

        // Penerimaan Sp2D
        $terima_sp2d = DB::select("SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)=? AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
        (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)<? AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
        (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)=? AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
        (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)<? AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
        (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)=? AND c.jns_spp in ('5','6')  AND a.status='1') AS sp2d_brjs_ini,
        (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
        ON a.no_spp = b.no_spp INNER JOIN trhspp c
        ON a.no_spp = c.no_spp WHERE a.kd_skpd = ? AND 
        MONTH(a.tgl_kas)<? AND c.jns_spp in ('5','6') AND a.status='1') AS sp2d_brjs_ll", [$kd_skpd,$bulan,$kd_skpd,$bulan,$kd_skpd,$bulan,$kd_skpd,$bulan,$kd_skpd,$bulan,$kd_skpd,$bulan]);
        
        foreach($terima_sp2d as $trm_sp2d){
            $sp2d_gj_ll     = $trm_sp2d->sp2d_gj_ll;
            $sp2d_gj_ini    = $trm_sp2d->sp2d_gj_ini;
            $sp2d_brjs_ll   = $trm_sp2d->sp2d_brjs_ll;
            $sp2d_brjs_ini  = $trm_sp2d->sp2d_brjs_ini;
            $sp2d_up_ll     = $trm_sp2d->sp2d_up_ll;
            $sp2d_up_ini    = $trm_sp2d->sp2d_up_ini;
        }

        // terima potongan ppn
        $trmpot_pot_ppn = get_terimapotongan($kd_skpd,'210106010001',$bulan);

        foreach($trmpot_pot_ppn as $trm_ppn){
            $ppn_gaji_ll    = $trm_ppn->pot_gaji_ll;
            $ppn_gaji_ini   = $trm_ppn->pot_gaji_ini;
            $ppn_brjs_ini   = $trm_ppn->pot_brjs_ini;
            $ppn_brjs_ll    = $trm_ppn->pot_brjs_ll;
            $ppn_up_ll      = $trm_ppn->pot_up_ll;
            $ppn_up_ini     = $trm_ppn->pot_up_ini;
        }
        // terima potongan PPH 21
        $trmpot_pot_pph21 = get_terimapotongan($kd_skpd,'210105010001',$bulan);
        foreach($trmpot_pot_pph21 as $trm_pph21){
            $pph21_gaji_ll    = $trm_pph21->pot_gaji_ll;
            $pph21_gaji_ini   = $trm_pph21->pot_gaji_ini;
            $pph21_brjs_ini   = $trm_pph21->pot_brjs_ini;
            $pph21_brjs_ll    = $trm_pph21->pot_brjs_ll;
            $pph21_up_ll      = $trm_pph21->pot_up_ll;
            $pph21_up_ini     = $trm_pph21->pot_up_ini;
        }

        // terima potongan PPH 22
        $trmpot_pot_pph22 = get_terimapotongan($kd_skpd,'210105020001',$bulan);
        foreach($trmpot_pot_pph22 as $trm_pph22){
            $pph22_gaji_ll    = $trm_pph22->pot_gaji_ll;
            $pph22_gaji_ini   = $trm_pph22->pot_gaji_ini;
            $pph22_brjs_ini   = $trm_pph22->pot_brjs_ini;
            $pph22_brjs_ll    = $trm_pph22->pot_brjs_ll;
            $pph22_up_ll      = $trm_pph22->pot_up_ll;
            $pph22_up_ini     = $trm_pph22->pot_up_ini;
        }


        // terima potongan PPH 23
        $trmpot_pot_pph23 = get_terimapotongan($kd_skpd,'210105030001',$bulan);
        foreach($trmpot_pot_pph23 as $trm_pph23){
            $pph23_gaji_ll    = $trm_pph23->pot_gaji_ll;
            $pph23_gaji_ini   = $trm_pph23->pot_gaji_ini;
            $pph23_brjs_ini   = $trm_pph23->pot_brjs_ini;
            $pph23_brjs_ll    = $trm_pph23->pot_brjs_ll;
            $pph23_up_ll      = $trm_pph23->pot_up_ll;
            $pph23_up_ini     = $trm_pph23->pot_up_ini;
        }

         // terima potongan PPH Pasal 4 Ayat 2
         $trmpot_pot_pph4ayat2 = get_terimapotongan($kd_skpd,'210109010001',$bulan);
         foreach($trmpot_pot_pph4ayat2 as $trm_pph4ayat2){
             $pph4ayat2_gaji_ll    = $trm_pph4ayat2->pot_gaji_ll;
             $pph4ayat2_gaji_ini   = $trm_pph4ayat2->pot_gaji_ini;
             $pph4ayat2_brjs_ini   = $trm_pph4ayat2->pot_brjs_ini;
             $pph4ayat2_brjs_ll    = $trm_pph4ayat2->pot_brjs_ll;
             $pph4ayat2_up_ll      = $trm_pph4ayat2->pot_up_ll;
             $pph4ayat2_up_ini     = $trm_pph4ayat2->pot_up_ini;
         }
        // 
        // terima IWP
        $terima_iwp = get_terimapotongan($kd_skpd,'210108010001',$bulan);

        foreach($terima_iwp as $trm_iwp){
            $iwp_gaji_ll    = $trm_iwp->pot_gaji_ll;
            $iwp_gaji_ini   = $trm_iwp->pot_gaji_ini;
            $iwp_brjs_ini   = $trm_iwp->pot_brjs_ini;
            $iwp_brjs_ll    = $trm_iwp->pot_brjs_ll;
            $iwp_up_ll      = $trm_iwp->pot_up_ll;
            $iwp_up_ini     = $trm_iwp->pot_up_ini;
        }


        // terima taperum
        $terima_taperum = get_terimapotongan($kd_skpd,'210107010001',$bulan);

        foreach($terima_taperum as $trm_taperum){
            $taperum_gaji_ll    = $trm_taperum->pot_gaji_ll;
            $taperum_gaji_ini   = $trm_taperum->pot_gaji_ini;
            $taperum_brjs_ini   = $trm_taperum->pot_brjs_ini;
            $taperum_brjs_ll    = $trm_taperum->pot_brjs_ll;
            $taperum_up_ll      = $trm_taperum->pot_up_ll;
            $taperum_up_ini     = $trm_taperum->pot_up_ini;
        }
        //  PPNPN / Jaminan Kesehatan
        $terima_ppnpn = get_terimapotongan($kd_skpd,'210102010001',$bulan);

        foreach($terima_ppnpn as $trm_ppnpn){
            $ppnpn_gaji_ll    = $trm_ppnpn->pot_gaji_ll;
            $ppnpn_gaji_ini   = $trm_ppnpn->pot_gaji_ini;
            $ppnpn_brjs_ini   = $trm_ppnpn->pot_brjs_ini;
            $ppnpn_brjs_ll    = $trm_ppnpn->pot_brjs_ll;
            $ppnpn_up_ll      = $trm_ppnpn->pot_up_ll;
            $ppnpn_up_ini     = $trm_ppnpn->pot_up_ini;
        }
        // Denda keterlambatan 410411010001
        $terima_dk = get_terimapotongan($kd_skpd,'410411010001',$bulan);

        foreach($terima_dk as $trm_dk){
            $dk_gaji_ll    = $trm_dk->pot_gaji_ll;
            $dk_gaji_ini   = $trm_dk->pot_gaji_ini;
            $dk_brjs_ini   = $trm_dk->pot_brjs_ini;
            $dk_brjs_ll    = $trm_dk->pot_brjs_ll;
            $dk_up_ll      = $trm_dk->pot_up_ll;
            $dk_up_ini     = $trm_dk->pot_up_ini;
        }

        // Pelimpahan
        $terima_pelimpahan = DB::select("SELECT sum(x.bln_lalu) as pot_up_ll,sum(x.bln_ini) as pot_up_ini from(
                                        select 
                                                    SUM(CASE WHEN MONTH(tgl_kas)< ? THEN nilai ELSE 0 END) as bln_lalu,
                                                    SUM(CASE WHEN MONTH(tgl_kas)= ? THEN nilai ELSE 0 END) as bln_ini
                                                    from tr_setorpelimpahan
                                                    WHERE kd_skpd= ?
                                        UNION ALL            
                                        select 
                                                    SUM(CASE WHEN MONTH(tgl_kas)< ? THEN nilai ELSE 0 END) as bln_lalu,
                                                    SUM(CASE WHEN MONTH(tgl_kas)= ? THEN nilai ELSE 0 END) as bln_ini
                                                    from tr_setorpelimpahan_bank
                                                    WHERE kd_skpd= ?           
                                        UNION ALL
                                        select 
                                                    SUM(CASE WHEN MONTH(tgl_kas)< ? THEN nilai ELSE 0 END) as bln_lalu,
                                                    SUM(CASE WHEN MONTH(tgl_kas)= ? THEN nilai ELSE 0 END) as bln_ini
                                                    from tr_setorpelimpahan_tunai
                                                    WHERE kd_skpd= ?           
                                        UNION ALL
                                        SELECT 
                                                    SUM(CASE WHEN MONTH(tgl_kas)< ? THEN nilai ELSE 0 END) as bln_lalu,
                                                    SUM(CASE WHEN MONTH(tgl_kas)= ? THEN nilai ELSE 0 END) as bln_ini
                                        from tr_setorsimpanan WHERE kd_skpd= ? and jenis='3'
                                        )x", [$bulan,$bulan,$kd_skpd,$bulan,$bulan,$kd_skpd,$bulan,$bulan,$kd_skpd,$bulan,$bulan,$kd_skpd]);
        foreach($terima_pelimpahan as $trm_pelimpahan){
            $pelimpahan_up_ll      = $trm_pelimpahan->pot_up_ll;
            $pelimpahan_up_ini     = $trm_pelimpahan->pot_up_ini;
        }

        // Terima PANJAR
        $terima_panjar = DB::select("SELECT SUM(x.pot_up_ll) pot_up_ll, SUM(x.pot_up_ini) pot_up_ini FROM(
                                        SELECT 
                                        SUM(CASE WHEN MONTH(tgl_kas)< ? THEN nilai ELSE 0 END) as pot_up_ll,
                                        SUM(CASE WHEN MONTH(tgl_kas)= ? THEN nilai ELSE 0 END) as pot_up_ini
                                        from 
                                        tr_jpanjar where jns=1 and kd_skpd= ?
                                        )x", [$bulan,$bulan,$kd_skpd]);
        foreach($terima_panjar as $trm_panjar){
            $panjar_up_ll      = $trm_panjar->pot_up_ll;
            $panjar_up_ini     = $trm_panjar->pot_up_ini;
        }

        // BOS
        $terima_bos = DB::select("SELECT SUM(x.bos_bln_lalu) bos_bln_lalu, SUM(x.bos_bln_ini) bos_bln_ini FROM(
                                    SELECT 
                                    SUM(CASE WHEN MONTH(tgl_sp2b)< ? THEN b.nilai ELSE 0 END) as bos_bln_lalu,
                                    SUM(CASE WHEN MONTH(tgl_sp2b)= ? THEN b.nilai ELSE 0 END) as bos_bln_ini
                                    from trhsp2b a inner join trdsp2b b on a.kd_skpd=b.kd_skpd and a.no_sp2b=b.no_sp2b 
                                    where a.kd_skpd= ?
                                    UNION
                                    
                                    SELECT 
                                    SUM(CASE WHEN MONTH(tgl_sp2h)< ? THEN b.nilai ELSE 0 END) as bos_bln_lalu,
                                    SUM(CASE WHEN MONTH(tgl_sp2h)= ? THEN b.nilai ELSE 0 END) as bos_bln_ini
                                    from trhsp2h a inner join trdsp2h b on a.no_sp2h=b.no_sp2h
                                    where a.kd_skpd= ? )x",[$bulan,$bulan,$kd_skpd,$bulan,$bulan,$kd_skpd] );

        foreach($terima_bos as $trm_bos){
            $bos_bln_lalu      = $trm_bos->bos_bln_lalu;
            $bos_bln_ini        = $trm_bos->bos_bln_ini;
        }

        // terima BLUD
        $terima_blud = DB::select("SELECT SUM(CASE WHEN MONTH(tgl_kas)< ? THEN b.nilai ELSE 0 END) as blud_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)= ? THEN b.nilai ELSE 0 END) as blud_bln_ini
        from trhtransout_blud a inner join trdtransout_blud b on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti 
        where a.kd_skpd= ? and left(b.kd_rek6,1)='5' and b.sumber='BLUD'",[$bulan, $bulan,$bulan,$kd_skpd] );
        
        foreach($terima_blud as $trm_blud){
            $blud_bln_lalu      = $trm_blud->blud_bln_lalu;
            $blud_bln_ini       = $trm_blud->blud_bln_ini;
        }

        // Terima lain-lain
        $terima_lain = DB::select("SELECT 
        SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
        SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
        SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
         FROM(
        SELECT 
        SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)< ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
        SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)= ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
        SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)< ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
        SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)= ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
        SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)< ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
        SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)= ? AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
        FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        WHERE a.kd_skpd= ? and left(a.kd_rek6,6)<>'210601'
        UNION ALL

        SELECT 
        SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)< ? THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
        SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)= ? THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
        SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)< ? THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
        SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)= ? THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
        SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)< ? THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
        SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)= ? THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
        FROM TRHINLAIN a WHERE pengurang_belanja !='1' 
        AND a.kd_skpd= ?
        ) a ",[$bulan,$bulan,$bulan,$bulan,$bulan,$bulan,$kd_skpd,$bulan,$bulan,$bulan,$bulan,$bulan,$bulan,$kd_skpd]);

foreach($terima_lain as $trm_lain){
        $jlain_gaji_ll    = $trm_lain->jlain_gaji_ll;
        $jlain_gaji_ini   = $trm_lain->jlain_gaji_ini;
        $jlain_brjs_ll    = $trm_lain->jlain_brjs_ll;
        $jlain_brjs_ini   = $trm_lain->jlain_brjs_ini;
        $jlain_up_ll      = $trm_lain->jlain_up_ll;
        $jlain_up_ini     = $trm_lain->jlain_up_ini;
}

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan','logo_pemda_hp')->first(),
            'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'bulan'             => $bulan,
            'rincian'           => $rincian,
            // sp2d
            'sp2d_gj_ll'        => $sp2d_gj_ll,
            'sp2d_gj_ini'       => $sp2d_gj_ini,
            'sp2d_brjs_ll'      => $sp2d_brjs_ll,
            'sp2d_brjs_ini'     => $sp2d_brjs_ini,
            'sp2d_up_ll'        => $sp2d_up_ll,
            'sp2d_up_ini'       => $sp2d_up_ini,
            // potongan
            'ppn_gaji_ll'       => $ppn_gaji_ll,
            'ppn_gaji_ini'      => $ppn_gaji_ini,
            'ppn_brjs_ini'      => $ppn_brjs_ini,
            'ppn_brjs_ll'       => $ppn_brjs_ll,
            'ppn_up_ll'         => $ppn_up_ll,
            'ppn_up_ini'        => $ppn_up_ini,

            'pph21_gaji_ll'     => $pph21_gaji_ll,
            'pph21_gaji_ini'    => $pph21_gaji_ini,
            'pph21_brjs_ini'    => $pph21_brjs_ini,
            'pph21_brjs_ll'     => $pph21_brjs_ll,
            'pph21_up_ll'       => $pph21_up_ll,
            'pph21_up_ini'      => $pph21_up_ini,
            
            'pph22_gaji_ll'     => $pph22_gaji_ll,
            'pph22_gaji_ini'    => $pph22_gaji_ini,
            'pph22_brjs_ini'    => $pph22_brjs_ini,
            'pph22_brjs_ll'     => $pph22_brjs_ll,
            'pph22_up_ll'       => $pph22_up_ll,
            'pph22_up_ini'      => $pph22_up_ini,

            'pph23_gaji_ll'     => $pph23_gaji_ll,
            'pph23_gaji_ini'    => $pph23_gaji_ini,
            'pph23_brjs_ini'    => $pph23_brjs_ini,
            'pph23_brjs_ll'     => $pph23_brjs_ll,
            'pph23_up_ll'       => $pph23_up_ll,
            'pph23_up_ini'      => $pph23_up_ini,

            'pph4ayat2_gaji_ll'     => $pph4ayat2_gaji_ll,
            'pph4ayat2_gaji_ini'    => $pph4ayat2_gaji_ini,
            'pph4ayat2_brjs_ini'    => $pph4ayat2_brjs_ini,
            'pph4ayat2_brjs_ll'     => $pph4ayat2_brjs_ll,
            'pph4ayat2_up_ll'       => $pph4ayat2_up_ll,
            'pph4ayat2_up_ini'      => $pph4ayat2_up_ini,

            // IWP
            'trm_iwp_gaji_ll'   => $iwp_gaji_ll,
            'trm_iwp_gaji_ini'  => $iwp_gaji_ini,
            'trm_iwp_brjs_ini'  => $iwp_brjs_ini,
            'trm_iwp_brjs_ll'   => $iwp_brjs_ll,
            'trm_iwp_up_ll'     => $iwp_up_ll,
            'trm_iwp_up_ini'    => $iwp_up_ini,

            // taperum
            'trm_taperum_gaji_ll'   => $taperum_gaji_ll,
            'trm_taperum_gaji_ini'  => $taperum_gaji_ini,
            'trm_taperum_brjs_ini'  => $taperum_brjs_ini,
            'trm_taperum_brjs_ll'   => $taperum_brjs_ll,
            'trm_taperum_up_ll'     => $taperum_up_ll,
            'trm_taperum_up_ini'    => $taperum_up_ini,

            // ppnpn
            'trm_ppnpn_gaji_ll'   => $ppnpn_gaji_ll,
            'trm_ppnpn_gaji_ini'  => $ppnpn_gaji_ini,
            'trm_ppnpn_brjs_ini'  => $ppnpn_brjs_ini,
            'trm_ppnpn_brjs_ll'   => $ppnpn_brjs_ll,
            'trm_ppnpn_up_ll'     => $ppnpn_up_ll,
            'trm_ppnpn_up_ini'    => $ppnpn_up_ini,

            // denda keterlambatan
            'trm_dk_gaji_ll'   => $dk_gaji_ll,
            'trm_dk_gaji_ini'  => $dk_gaji_ini,
            'trm_dk_brjs_ini'  => $dk_brjs_ini,
            'trm_dk_brjs_ll'   => $dk_brjs_ll,
            'trm_dk_up_ll'     => $dk_up_ll,
            'trm_dk_up_ini'    => $dk_up_ini,

            // Pelimpahan
            'pelimpahan_up_ll' =>$pelimpahan_up_ll,
            'pelimpahan_up_ini'=>$pelimpahan_up_ini,

            // panjar
            'panjar_up_ll'      => $panjar_up_ll,
            'panjar_up_ini'     => $panjar_up_ini,

            // BOS
            'bos_bln_lalu'       =>$bos_bln_lalu,
            'bos_bln_ini'        =>$bos_bln_ini,

            // BLUD
            'blud_bln_lalu'     => $blud_bln_lalu,
            'blud_bln_ini'      => $blud_bln_ini,

            // Lainnya
            'jlain_gaji_ll'     => $jlain_gaji_ll,
            'jlain_gaji_ini'    => $jlain_gaji_ini,
            'jlain_brjs_ll'     => $jlain_brjs_ll,
            'jlain_brjs_ini'    => $jlain_brjs_ini,
            'jlain_up_ll'       => $jlain_up_ll,
            'jlain_up_ini'      => $jlain_up_ini,

            // footer
            'enter'             => $enter,
            'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'cari_pa_kpa'       => $cari_pakpa,
            'cari_bendahara'    => $cari_bendahara,
            'terima_sp2d'       => $terima_sp2d
        ];
        return view('skpd.laporan_bendahara.cetak.spj_fungsional')->with($data);
    }
}
