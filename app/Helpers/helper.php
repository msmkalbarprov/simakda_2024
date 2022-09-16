<?php

use Illuminate\Support\Facades\DB;

function bulan($data)
{
    if ($data == '1') {
        return 'Januari';
    }
    if ($data == '2') {
        return 'Februari';
    }
    if ($data == '3') {
        return 'Maret';
    }
    if ($data == '4') {
        return 'April';
    }
    if ($data == '5') {
        return 'Mei';
    }
    if ($data == '6') {
        return 'Juni';
    }
    if ($data == '7') {
        return 'Juli';
    }
    if ($data == '8') {
        return 'Agustus';
    }
    if ($data == '9') {
        return 'September';
    }
    if ($data == '10') {
        return 'Oktober';
    }
    if ($data == '11') {
        return 'November';
    }
    if ($data == '12') {
        return 'Desember';
    }
}

function jenis($beban, $jenis)
{
    if ($beban == '3') {
        if ($jenis == '1') {
            return 'TU';
        }
    } elseif ($beban == '4') {
        if ($jenis == '1') {
            return 'Gaji & Tunjangan';
        }
        if ($jenis == '2') {
            return 'Kespeg';
        }
        if ($jenis == '3') {
            return 'Uang Makan';
        }
        if ($jenis == '4') {
            return 'Upah Pungut';
        }
        if ($jenis == '5') {
            return 'Upah Pungut PBB';
        }
        if ($jenis == '6') {
            return 'Upah Pungut PBB-KB PKB & BBN-KB';
        }
        if ($jenis == '7') {
            return 'Tambahan/Kekurangan Gaji & Tunjangan';
        }
        if ($jenis == '8') {
            return 'Tunjangan Transport';
        }
        if ($jenis == '9') {
            return 'Tunjangan Lainnya';
        }
        if ($jenis == '10') {
            return 'Gaji Anggota DPRD';
        }
    } elseif ($beban == '5') {
        if ($jenis == '1') {
            return 'Hibah berupa uang';
        }
        if ($jenis == '2') {
            return 'Bantuan Sosial berupa uang';
        }
        if ($jenis == '3') {
            return 'Bantuan Keuangan';
        }
        if ($jenis == '4') {
            return 'Subsidi';
        }
        if ($jenis == '5') {
            return 'Bagi Hasil';
        }
        if ($jenis == '6') {
            return 'Belanja Tidak Terduga';
        }
        if ($jenis == '7') {
            return 'Pembayaran kewajiban pemda atas putusan pengadilan, dan rekomendasi APIP dan/atau rekomendasi BPK';
        }
        if ($jenis == '8') {
            return 'Pengeluaran Pembiayaan';
        }
        if ($jenis == '9') {
            return 'Barang yang diserahkan ke masyarakat';
        }
    } elseif ($beban == '6') {
        if ($jenis == '1') {
            return 'Tambahan Penghasilan';
        }
        if ($jenis == '2') {
            return 'Operasional KDH/WKDH';
        }
        if ($jenis == '3') {
            return 'Operasional DPRD';
        }
        if ($jenis == '4') {
            return 'Honor Kontrak';
        }
        if ($jenis == '5') {
            return 'Jasa Pelayanan Kesehatan';
        }
        if ($jenis == '6') {
            return 'Pihak ketiga';
        }
        if ($jenis == '7') {
            return 'Rutin (PNS)';
        }
    }
}

function nilai($data)
{
    return number_format($data, 2, ',', '.');
}

function rupiah($data)
{
    return number_format($data, 2, ',', '.');
}

function terbilang($number)
{
    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        $hasil = "Minus " . trim(depan($number));
        $poin = trim(belakang($number));
    } else {
        $poin = trim(belakang($number));
        $hasil = trim(depan($number));
    }

    if ($poin) {
        $hasil = $hasil . " koma " . $poin . " Rupiah";
    } else {
        $hasil = $hasil . " Rupiah";
    }
    return $hasil;
}

function depan($number)
{
    $number = abs($number);
    $nomor_depan = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $depans = "";

    if ($number < 12) {
        $depans = " " . $nomor_depan[$number];
    } else if ($number < 20) {
        $depans = depan($number - 10) . " belas";
    } else if ($number < 100) {
        $depans = depan($number / 10) . " puluh " . depan(fmod($number, 10));
    } else if ($number < 200) {
        $depans = "seratus " . depan($number - 100);
    } else if ($number < 1000) {
        $depans = depan($number / 100) . " ratus " . depan(fmod($number, 100));
        //$depans = depan($number/100)." Ratus ".depan($number%100);
    } else if ($number < 2000) {
        $depans = "seribu " . depan($number - 1000);
    } else if ($number < 1000000) {
        $depans = depan($number / 1000) . " ribu " . depan(fmod($number, 1000));
    } else if ($number < 1000000000) {
        $depans = depan($number / 1000000) . " juta " . depan(fmod($number, 1000000));
    } else if ($number < 1000000000000) {
        $depans = depan($number / 1000000000) . " milyar " . depan(fmod($number, 1000000000));
        //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

    } else if ($number < 1000000000000000) {
        $depans = depan($number / 1000000000000) . " triliun " . depan(fmod($number, 1000000000000));
        //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

    } else {
        $depans = "Undefined";
    }
    return $depans;
}

function belakang($number)
{
    $number = abs($number);
    $number = stristr($number, ".");
    $nomor_belakang = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");

    $belakangs = "";
    $length = strlen($number);
    $i = 1;
    while ($i < $length) {
        $get = substr($number, $i, 1);
        $i++;
        $belakangs .= " " . $nomor_belakang[$get];
    }
    return $belakangs;
}

function left($string, $count)
{
    return substr($string, 0, $count);
}

function right($value, $count)
{
    return substr($value, ($count * -1));
}

function dotrek($rek)
{
    $nrek = strlen($rek);
    switch ($nrek) {
        case 1:
            $rek = left($rek, 1);
            break;
        case 2:
            $rek = left($rek, 1) . '.' . substr($rek, 1, 1);
            break;
        case 4:
            $rek = left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2);
            break;
        case 6:
            $rek = left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2);
            break;
        case 8:
            $rek = left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
            break;
        case 12:
            $rek = left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);
            break;
        default:
            $rek = "";
    }
    return $rek;
}

function cari_bank($bank)
{
    if (!$bank) {
        return 'Belum Pilih Bank';
    } else {
        $data_bank = DB::table('ms_bank')->select('nama')->where(['kode' => $bank])->first();
        return $data_bank->nama;
    }
}

function tanggal($tgl)
{
    return \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('DD MMMM Y');
}

function total_beban($data_spm, $kd_skpd, $status_angkas)
{
    $beban1 = DB::table('trdspp as a')->join('trskpd as b', function ($join) {
        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    })->where(['no_spp' => $data_spm->no_spp, 'a.kd_skpd' => $kd_skpd, 'b.jns_ang' => $status_angkas->jns_ang])->groupByRaw("SUBSTRING(a.kd_sub_kegiatan,1,7), nm_program")->select(DB::raw("'1' AS urut"), DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,7) as kode"), 'b.nm_program as nama', DB::raw("SUM(nilai) as nilai"));
    $beban2 = DB::table('trdspp as a')->join('trskpd as b', function ($join) {
        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    })->where(['no_spp' => $data_spm->no_spp, 'a.kd_skpd' => $kd_skpd, 'b.jns_ang' => $status_angkas->jns_ang])->groupByRaw("SUBSTRING(a.kd_sub_kegiatan,1,12), nm_kegiatan")->select(DB::raw("' ' AS urut"), DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,12) as kode"), 'b.nm_kegiatan as nama', DB::raw("SUM(nilai) as nilai"))->unionAll($beban1);
    $beban3 = DB::table('trdspp')->select(DB::raw("' ' as urut"), 'kd_sub_kegiatan as kode', 'nm_sub_kegiatan as nama', DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->groupBy('kd_sub_kegiatan', 'nm_sub_kegiatan')->unionAll($beban2);
    $beban4 = DB::table('trdspp as a')->join('ms_rek3 as b', DB::raw("LEFT(a.kd_rek6,3)"), '=', 'b.kd_rek3')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->select(DB::raw("' ' as urut"), DB::raw("a.kd_sub_kegiatan + '.' + LEFT(a.kd_rek6,3) as kode"), 'b.nm_rek3 as nama', DB::raw("SUM(a.nilai) as nilai"))->groupBy('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,3)"), 'b.nm_rek3')->unionAll($beban3);
    $beban5 = DB::table('trdspp as a')->join('ms_rek4 as b', DB::raw("LEFT(a.kd_rek6,5)"), '=', 'b.kd_rek4')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->select(DB::raw("' ' as urut"), DB::raw("a.kd_sub_kegiatan + '.' + LEFT(a.kd_rek6,5) as kode"), 'b.nm_rek4 as nama', DB::raw("SUM(a.nilai) as nilai"))->groupBy('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,5)"), 'b.nm_rek4')->unionAll($beban4);
    $beban6 = DB::table('trdspp')->select(DB::raw("' ' as urut"), DB::raw("kd_sub_kegiatan + '.' + kd_rek6 as kode"), 'nm_rek6 as nama', 'nilai')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->unionAll($beban5);
    $total_beban = DB::table(DB::raw("({$beban6->toSql()}) AS sub"))
        ->mergeBindings($beban6)
        ->count();
    return $total_beban;
}

function data_beban($data_spm, $kd_skpd, $status_angkas)
{
    $beban1 = DB::table('trdspp as a')->join('trskpd as b', function ($join) {
        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    })->where(['no_spp' => $data_spm->no_spp, 'a.kd_skpd' => $kd_skpd, 'b.jns_ang' => $status_angkas->jns_ang])->groupByRaw("SUBSTRING(a.kd_sub_kegiatan,1,7), nm_program")->select(DB::raw("'1' AS urut"), DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,7) as kode"), 'b.nm_program as nama', DB::raw("SUM(nilai) as nilai"));
    $beban2 = DB::table('trdspp as a')->join('trskpd as b', function ($join) {
        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    })->where(['no_spp' => $data_spm->no_spp, 'a.kd_skpd' => $kd_skpd, 'b.jns_ang' => $status_angkas->jns_ang])->groupByRaw("SUBSTRING(a.kd_sub_kegiatan,1,12), nm_kegiatan")->select(DB::raw("' ' AS urut"), DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,12) as kode"), 'b.nm_kegiatan as nama', DB::raw("SUM(nilai) as nilai"))->unionAll($beban1);
    $beban3 = DB::table('trdspp')->select(DB::raw("' ' as urut"), 'kd_sub_kegiatan as kode', 'nm_sub_kegiatan as nama', DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->groupBy('kd_sub_kegiatan', 'nm_sub_kegiatan')->unionAll($beban2);
    $beban4 = DB::table('trdspp as a')->join('ms_rek3 as b', DB::raw("LEFT(a.kd_rek6,3)"), '=', 'b.kd_rek3')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->select(DB::raw("' ' as urut"), DB::raw("a.kd_sub_kegiatan + '.' + LEFT(a.kd_rek6,3) as kode"), 'b.nm_rek3 as nama', DB::raw("SUM(a.nilai) as nilai"))->groupBy('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,3)"), 'b.nm_rek3')->unionAll($beban3);
    $beban5 = DB::table('trdspp as a')->join('ms_rek4 as b', DB::raw("LEFT(a.kd_rek6,5)"), '=', 'b.kd_rek4')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->select(DB::raw("' ' as urut"), DB::raw("a.kd_sub_kegiatan + '.' + LEFT(a.kd_rek6,5) as kode"), 'b.nm_rek4 as nama', DB::raw("SUM(a.nilai) as nilai"))->groupBy('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,5)"), 'b.nm_rek4')->unionAll($beban4);
    $beban6 = DB::table('trdspp')->select(DB::raw("' ' as urut"), DB::raw("kd_sub_kegiatan + '.' + kd_rek6 as kode"), 'nm_rek6 as nama', 'nilai')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->unionAll($beban5);
    $total_beban = DB::table(DB::raw("({$beban6->toSql()}) AS sub"))
        ->select('urut', 'kode', 'nama', 'nilai')
        ->mergeBindings($beban6)
        ->orderBy('kode')
        ->get();
    return $total_beban;
}

function data_beban1($data_spm, $kd_skpd, $status_angkas)
{
    $beban1 = DB::table('trdspp as a')->join('trskpd as b', DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,21)"), '=', 'b.kd_program')->where(['no_spp' => $data_spm->no_spp, 'a.kd_skpd' => $kd_skpd, 'b.jns_ang' => $status_angkas->jns_ang])->groupByRaw("SUBSTRING(a.kd_sub_kegiatan,1,18), nm_program")->select(DB::raw("'1' AS urut"), DB::raw("SUBSTRING(a.kd_sub_kegiatan,1,18) as kode"), 'b.nm_program as nama', DB::raw("SUM(nilai) as nilai"));

    $beban2 = DB::table('trdspp')->select(DB::raw("'1' as urut"), 'kd_sub_kegiatan as kode', 'nm_sub_kegiatan as nama', DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->groupBy('kd_sub_kegiatan', 'nm_sub_kegiatan')->unionAll($beban1);

    $beban3 = DB::table('trdspp as a')->join('ms_rek3 as b', DB::raw("LEFT(a.kd_rek6,3)"), '=', 'b.kd_rek3')->where(['no_spp' => $data_spm->no_spp, 'kd_skpd' => $kd_skpd])->select(DB::raw("'1' as urut"), DB::raw("a.kd_sub_kegiatan + '.' + LEFT(a.kd_rek6,3) as kode"), 'b.nm_rek3 as nama', DB::raw("SUM(a.nilai) as nilai"))->groupBy('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,3)"), 'b.nm_rek3')->unionAll($beban2);

    $beban4 = DB::query()->select(DB::raw("'2' as urut"), DB::raw("' ' as kode"), DB::raw("'- Rincian Terlampir' as nama"), DB::raw("0 as nilai"))->unionAll($beban3);
    $total_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
        ->select('urut', 'kode', 'nama', 'nilai')
        ->mergeBindings($beban4)
        ->orderBy('urut')
        ->orderBy('kode')
        ->get();
    return $total_beban;
}

function nogub($status_anggaran, $kd_skpd)
{
    $daerah = DB::table('sclient')->select('kab_kota', 'daerah', 'nogub_susun', 'nogub_perubahan', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')->where(['kd_skpd' => $kd_skpd])->first();
    if ($status_anggaran == 'M') {
        $nogub = $daerah->nogub_susun;
    } else if ($status_anggaran == 'P1') {
        $nogub = $daerah->nogub_p1;
    } else if ($status_anggaran == 'P2') {
        $nogub = $daerah->nogub_p2;
    } else if ($status_anggaran == 'P3') {
        $nogub = $daerah->nogub_p3;
    } else if ($status_anggaran == 'P4') {
        $nogub = $daerah->nogub_p4;
    } else if ($status_anggaran == 'P5') {
        $nogub = $daerah->nogub_p5;
    } else if ($status_anggaran == 'U1') {
        $nogub = $daerah->nogub_perubahan;
    } else if ($status_anggaran == 'U2') {
        $nogub = $daerah->nogub_perubahan2;
    } else if ($status_anggaran == 'U3') {
        $nogub = $daerah->nogub_perubahan3;
    } else if ($status_anggaran == 'U4') {
        $nogub = $daerah->nogub_perubahan4;
    } else {
        $nogub = $daerah->nogub_perubahan5;
    }
    return $nogub;
}

function pengantar_spm($no_spm, $kd_skpd, $beban, $data_spp, $no_spp, $tgl_spd, $giatspp, $cari_rek)
{
    if ($beban == '1') {
        $data_beban = DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', 'a.no_spp', '=', 'c.no_spp')->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.no_spd', 'a.nilai', 'c.*', DB::raw("(SELECT nama FROM ms_bank WHERE kode=a.bank) as nama_bank"), DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT SUM ( nilai ) FROM trdspd WHERE no_spd = a.no_spd) as spd"), DB::raw("(SELECT SUM ( nilai ) FROM trhspp WHERE no_spd = a.no_spd AND no_spp <> a.no_spp AND kd_skpd = a.kd_skpd) as spp"))->first();
    } elseif ($beban == '2') {
        $data_beban = DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'a.nmrekan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.bank', 'c.*', 'a.no_spd', 'a.nilai', DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT npwp FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as npwp"), DB::raw("(SELECT SUM
		( x.nilai ) FROM trdspd x INNER JOIN trhspd w ON x.no_spd = w.no_spd WHERE w.jns_beban= '5' AND w.kd_skpd= a.kd_skpd AND w.status= '1' AND w.tgl_spd<= '$tgl_spd->tgl_spd') as spd"), DB::raw("(SELECT SUM( b.nilai ) FROM trdspp b INNER JOIN trhspp a ON b.no_spp= a.no_spp AND b.kd_skpd = a.kd_skpd INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd= '$kd_skpd' AND a.jns_spp IN ( '1', '2', '3', '6' ) AND a.no_spp != '$no_spp->no_spp' AND c.tgl_sp2d <= '$data_spp->tgl_spp') as spp"))->first();
    } elseif ($beban == '3') {
        $data_beban = DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'a.nmrekan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.bank', 'c.*', 'a.no_spd', 'a.nilai', DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT npwp FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as npwp"), DB::raw("(SELECT SUM
		( x.nilai ) FROM trdspd x INNER JOIN trhspd w ON x.no_spd = w.no_spd WHERE w.jns_beban= '5' AND w.status= '1' AND w.tgl_spd<= '$tgl_spd->tgl_spd' AND x.kd_sub_kegiatan= '$giatspp' AND x.kd_unit= a.kd_skpd) as spd"), DB::raw("(SELECT SUM( b.nilai ) FROM trdspp b INNER JOIN trhspp a ON b.no_spp= a.no_spp AND b.kd_skpd = a.kd_skpd INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd= '$kd_skpd' AND a.jns_spp IN ( '1', '2', '3', '6' ) AND a.no_spp != '$no_spp->no_spp' AND c.tgl_sp2d <= '$data_spp->tgl_spp' AND b.kd_sub_kegiatan= '$giatspp') as spp"))->first();
    } elseif ($beban == '4') {
        $rek6 = substr($cari_rek->kd_rek6, 0, 6);
        if ($rek6 == '510101') {
            $data_beban =
                DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', 'a.no_spp', '=', 'c.no_spp')->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'a.nmrekan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.bank', 'c.*', 'a.no_spd', 'a.nilai', DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT npwp FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as npwp"), DB::raw("(SELECT SUM( x.nilai ) FROM trdspd x INNER JOIN trhspd w ON x.no_spd = w.no_spd WHERE w.tgl_spd<= '$tgl_spd->tgl_spd' AND w.kd_skpd= '$kd_skpd') as spd"), DB::raw("(SELECT SUM( b.nilai ) FROM trdspp b INNER JOIN trhspp a ON b.no_spp= a.no_spp AND b.kd_skpd = a.kd_skpd INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd= '$kd_skpd' AND a.no_spp != '$no_spp->no_spp' AND c.tgl_sp2d <= '$data_spp->tgl_spp') as spp"))->first();
        } else {
            $data_beban = DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'a.nmrekan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.bank', 'c.*', 'a.no_spd', 'a.nilai', DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT npwp FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as npwp"), DB::raw("(SELECT SUM( x.nilai ) FROM trdspd x INNER JOIN trhspd w ON x.no_spd = w.no_spd WHERE w.tgl_spd<= '$tgl_spd->tgl_spd' AND x.kd_unit= '$kd_skpd' AND x.kd_rek6 = '$cari_rek->kd_rek6') as spd"), DB::raw("(SELECT SUM( b.nilai ) FROM trdspp b INNER JOIN trhspp a ON b.no_spp= a.no_spp AND b.kd_skpd = a.kd_skpd INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd= '$kd_skpd' AND a.no_spp != '$no_spp->no_spp' AND c.tgl_sp2d <= '$data_spp->tgl_spp' AND b.kd_rek6 = '$cari_rek->kd_rek6') as spp"))->first();
        }
    } elseif (in_array($beban, ['5', '6'])) {
        $data_beban = DB::table('trhspp as a')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->join('trhspm as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['c.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->select('a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'a.nmrekan', 'a.no_rek as no_rek_rek', 'a.npwp as npwp_rek', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.bank', 'c.*', 'a.jns_beban', 'a.no_spd', 'a.nilai', DB::raw("(SELECT rekening FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as no_rek"), DB::raw("(SELECT npwp FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as npwp"), DB::raw("(SELECT nama FROM ms_bank WHERE kode = a.bank) as nama_bank_rek"), DB::raw("(SELECT SUM( x.nilai ) FROM trdspd x INNER JOIN trhspd w ON x.no_spd = w.no_spd WHERE w.jns_beban= '5' AND x.kd_sub_kegiatan='$giatspp'
		AND LEFT ( w.kd_skpd, 17 ) = LEFT ( '$kd_skpd', 17 ) AND w.status= '1' AND w.tgl_spd<= '$tgl_spd->tgl_spd') as spd"), DB::raw("(SELECT SUM( b.nilai ) FROM trdspp b INNER JOIN trhspp a ON b.no_spp= a.no_spp AND b.kd_skpd = a.kd_skpd INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE LEFT ( a.kd_skpd, 17 ) = LEFT ( '$kd_skpd', 17 ) AND a.jns_spp IN ( '1', '2', '3', '6' ) AND a.no_spp != '$no_spp->no_spp' AND b.kd_sub_kegiatan= '$giatspp'  AND c.tgl_sp2d <= '$data_spp->tgl_spp') as spp"))->first();
    }
    return $data_beban;
}

function cari_bank_spm($kd_skpd)
{
    $data = DB::table('ms_skpd')->select('bank')->where(['kd_skpd' => $kd_skpd])->first();
    if (!$data->bank) {
        $data1 = '-';
    } else {
        $data2 = DB::table('ms_bank')->select('nama')->where(['kode' => $data->bank])->first();
        $data1 = $data2->nama;
    }
    return $data1;
}
