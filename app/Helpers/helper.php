<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

function tahun_anggaran()
{
    return '2022';
}

function beban($data)
{
    switch ($data) {
        case '1':
            $nama = 'UP';
            break;
        case '2':
            $nama = 'GU';
            break;
        case '3':
            $nama = 'TU';
            break;
        case '4':
            $nama = 'LS Gaji';
            break;
        case '5':
            $nama = 'LS Pihak Ketiga Lainnya';
            break;
        case '6':
            $nama = 'LS Barang dan Jasa';
            break;
        default:
            break;
    }
    return $nama;
}

function bank($data)
{
    $bank = DB::table('ms_bank')->select('nama')->where(['kode' => $data])->first();
    return $bank->nama;
}

function tgl_spd($data)
{
    $tgl_spd = DB::table('trhspd')->select('tgl_spd')->where(['no_spd' => $data])->first();
    return tanggal($tgl_spd->tgl_spd);
}

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

function angka($data)
{
    $n1 = Str::replace('.', '', $data);
    $n2 = Str::replace(',', '.', $n1);
    return $n2;
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

function lampiran_spm($beban, $no_spm, $kd_skpd)
{
    $anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kd_skpd, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
    $status_anggaran = $anggaran->jns_ang;
    if ($beban == '1') {
        $data_beban = DB::table('trhspm as a')->select(DB::raw("'1' as urut"), 'c.kd_rek6 as kode', 'c.nm_rek6 as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->groupBy('kd_rek6', 'c.nm_rek6')->get();
    } else if ($beban == '2') {
        $beban1 = DB::table('trhspm as a')->select(DB::raw("'1' as urut"), DB::raw("LEFT(c.kd_sub_kegiatan,7) as kode"), 'd.nm_program as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->join('trskpd as d', function ($join) {
            $join->on('c.kd_sub_kegiatan', '=', 'd.kd_sub_kegiatan');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd, 'd.jns_ang' => $status_anggaran])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->groupBy(DB::raw("LEFT(c.kd_sub_kegiatan,7)"), 'd.nm_program');

        $beban2 = DB::table('trhspm as a')->select(DB::raw("'2' as urut"), DB::raw("LEFT(c.kd_sub_kegiatan,12) as kode"), 'd.nm_kegiatan as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->join('trskpd as d', function ($join) {
            $join->on('c.kd_sub_kegiatan', '=', 'd.kd_sub_kegiatan');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd, 'd.jns_ang' => $status_anggaran])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->groupBy(DB::raw("LEFT(c.kd_sub_kegiatan,12)"), 'd.nm_kegiatan')->unionAll($beban1);

        $beban3 = DB::table('trhspm as a')->select(DB::raw("'3' as urut"), 'c.kd_sub_kegiatan as kode', 'c.nm_sub_kegiatan as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->groupBy('c.kd_sub_kegiatan', 'c.nm_sub_kegiatan')->unionAll($beban2);

        $beban4 = DB::table('trhspm as a')->select(DB::raw("'4' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + LEFT(c.kd_rek6,4) as kode"), 'd.nm_rek3 as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->leftJoin('ms_rek3 as d', DB::raw("LEFT(c.kd_rek6,4)"), '=', 'd.kd_rek3')->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->groupBy('c.kd_sub_kegiatan', DB::raw("LEFT(c.kd_rek6,4)"), 'd.nm_rek3')->unionAll($beban3);

        $beban5 = DB::table('trhspm as a')->select(DB::raw("'5' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + LEFT(c.kd_rek6,6) as kode"), 'd.nm_rek4 as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->leftJoin('ms_rek4 as d', DB::raw("LEFT(c.kd_rek6,6)"), '=', 'd.kd_rek4')->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->groupBy('c.kd_sub_kegiatan', DB::raw("LEFT(c.kd_rek6,6)"), 'd.nm_rek4')->unionAll($beban4);

        $beban6 = DB::table('trhspm as a')->select(DB::raw("'6' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + c.kd_rek6 as kode"), 'c.nm_rek6 as nama', 'c.nilai')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->whereRaw("c.no_bukti IN (SELECT no_bukti FROM trhtransout WHERE LEFT ( kd_skpd, 17 ) = LEFT ( '1.01.2.22.0.00.01.0000', 17 ) AND jns_spp IN ( '1', '2', '3' ) )")->unionAll($beban5);

        $data_beban = DB::table(DB::raw("({$beban6->toSql()}) AS sub"))
            ->select("urut", "kode", "nama", "nilai")
            ->mergeBindings($beban6)
            ->orderBy('kode')
            ->get();
    } else {
        $beban1 = DB::table('trhspm as a')->select(DB::raw("'1' as urut"), DB::raw("LEFT(c.kd_sub_kegiatan,7) as kode"), 'd.nm_program as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->join('trskpd as d', function ($join) {
            $join->on('c.kd_sub_kegiatan', '=', 'd.kd_sub_kegiatan');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd, 'd.jns_ang' => $status_anggaran])->groupBy(DB::raw("LEFT(c.kd_sub_kegiatan,7)"), 'd.nm_program');

        $beban2 = DB::table('trhspm as a')->select(DB::raw("'2' as urut"), DB::raw("LEFT(c.kd_sub_kegiatan,12) as kode"), 'd.nm_kegiatan as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->join('trskpd as d', function ($join) {
            $join->on('c.kd_sub_kegiatan', '=', 'd.kd_sub_kegiatan');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd, 'd.jns_ang' => $status_anggaran])->groupBy(DB::raw("LEFT(c.kd_sub_kegiatan,12)"), 'd.nm_kegiatan')->unionAll($beban1);

        $beban3 = DB::table('trhspm as a')->select(DB::raw("'3' as urut"), 'c.kd_sub_kegiatan as kode', 'c.nm_sub_kegiatan as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->groupBy('c.kd_sub_kegiatan', 'c.nm_sub_kegiatan')->unionAll($beban2);

        $beban4 = DB::table('trhspm as a')->select(DB::raw("'4' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + LEFT(c.kd_rek6,4) as kode"), 'd.nm_rek3 as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->leftJoin('ms_rek3 as d', DB::raw("LEFT(c.kd_rek6,4)"), '=', 'd.kd_rek3')->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->groupBy('c.kd_sub_kegiatan', DB::raw("LEFT(c.kd_rek6,4)"), 'd.nm_rek3')->unionAll($beban3);

        $beban5 = DB::table('trhspm as a')->select(DB::raw("'5' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + LEFT(c.kd_rek6,6) as kode"), 'd.nm_rek4 as nama', DB::raw("SUM(c.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->leftJoin('ms_rek4 as d', DB::raw("LEFT(c.kd_rek6,6)"), '=', 'd.kd_rek4')->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->groupBy('c.kd_sub_kegiatan', DB::raw("LEFT(c.kd_rek6,6)"), 'd.nm_rek4')->unionAll($beban4);

        $beban6 = DB::table('trhspm as a')->select(DB::raw("'6' as urut"), DB::raw("c.kd_sub_kegiatan + '.' + c.kd_rek6 as kode"), 'c.nm_rek6 as nama', 'c.nilai')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as c', function ($join) {
            $join->on('b.no_spp', '=', 'c.no_spp');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->unionAll($beban5);

        $data_beban = DB::table(DB::raw("({$beban6->toSql()}) AS sub"))
            ->select("urut", "kode", "nama", "nilai")
            ->mergeBindings($beban6)
            ->orderBy('kode')
            ->get();
    }
    return $data_beban;
}

function cari_jenis($beban)
{
    if ($beban == 1) {
        $jenis2 = "UP";
    }
    if ($beban == 2) {
        $jenis2 = "GU";
    }
    if ($beban == 3) {
        $jenis2 = "TU";
    }
    if ($beban == 4) {
        $jenis2 = "LS";
    }
    if ($beban == 5) {
        $jenis2 = "LS";
    }
    if ($beban == 6) {
        $jenis2 = "LS";
    }

    return $jenis2;
}

function cari_pengguna($kd_skpd)
{
    $unit = right($kd_skpd, 2);
    if ($unit == '01' || $kd_skpd == '1.20.03.00') {
        $peng = 'Pengguna Anggaran';
    } else {
        $peng = 'Kuasa Pengguna Anggaran';
    }
    return $peng;
}

function pernyataan_spm($no_spm, $kd_skpd, $beban)
{

    $data_beban = DB::table('trhspm as a')->select('a.no_spm', 'a.jenis_beban', 'a.tgl_spm', 'a.kd_skpd', 'a.nm_skpd', 'a.bulan', 'b.kd_bidang_urusan', 'b.nm_bidang_urusan', 'a.no_spd', 'a.nilai')->join('ms_bidang_urusan as b', DB::raw("SUBSTRING(a.kd_skpd,1,4)"), '=', 'b.kd_bidang_urusan')->where(['a.no_spm' => $no_spm, 'a.kd_skpd' => $kd_skpd])->first();
    return $data_beban;
}

function nama_beban($beban, $jenis)
{
    if ($beban == '1') {
        $nama = 'UP';
    } elseif ($beban == '2') {
        $nama = 'GU';
    } elseif ($beban == '3') {
        $nama = 'TU';
    } elseif ($beban == '4') {
        switch ($jenis) {
            case '1': //UP
                $nama = "LS Gaji dan Tunjangan";
                break;
            case '2': //GU
                $nama = "LS Uang Kespeg";
                break;
            case '3': //TU
                $nama = "LS Uang Makan";
                break;
            case '4': //TU
                $nama = "LS Upah Pungut";
                break;
            case '5': //TU
                $nama = "LS Upah Pungut PBB";
                break;
            case '6': //TU
                $nama = "LS Upah Pungut PBB-KB PKB & BBN-KB ";
                break;
            case '7': //TU
                $nama = "LS Gaji & Tunjangan";
                break;
            case '8': //TU
                $nama = "LS Tunjangan Transport";
                break;
            case '9': //TU
                $nama = "LS Tunjangan Lainnya";
                break;
            default:
                $nama = "LS";
        }
    } elseif ($beban == '5') {
        $nama = 'LS Pihak Ketiga Lainnya';
    } elseif ($beban == '6') {
        switch ($jenis) {
            case '1': //UP
                $nama = "LS-Tambahan Penghasilan";
                break;
            case '2': //GU
                $nama = "LS-Operasional KDH/WKDH";
                break;
            case '3': //TU
                $nama = "LS-Operasional DPRD";
                break;
            case '4': //TU
                $nama = "LS-Honor Kontrak";
                break;
            case '5': //TU
                $nama = "LS-Jasa Pelayanan Kesehatan";
                break;
            case '6': //TU
                $nama = "LS-Pihak ketiga";
                break;
            case '7': //TU
                $nama = "LS-PNS";
                break;
        }
    }

    return $nama;
}

function nama_spm($beban, $jenis)
{
    if ($beban == '1') {
        $nama = 'Uang Persediaan (SPM - UP)';
    } elseif ($beban == '2') {
        $nama = 'Ganti Uang (SPM - GU)';
    } elseif ($beban == '3') {
        $nama = 'Tambahan Uang Persediaan (SPM - TU)';
    } elseif ($beban == '4') {
        switch ($jenis) {
            case '1': //UP
                $nama = "Langsung (SPP - LS Gaji dan Tunjangan)";
                break;
            case '2': //GU
                $nama = "Langsung (SPP - LS Uang Kespeg)";
                break;
            case '3': //TU
                $nama = "Langsung (SPP - LS Uang Makan)";
                break;
            case '4': //TU
                $nama = "Langsung (SPP - LS Upah Pungut)";
                break;
            case '5': //TU
                $nama = "Langsung (SPP - LS Upah Pungut PBB)";
                break;
            case '6': //TU
                $nama = "Langsung (SPP - LS Upah Pungut PBB-KB PKB & BBN-KB)";
                break;
            case '7': //TU
                $nama = "Langsung (SPP - LS Gaji & Tunjangan)";
                break;
            case '8': //TU
                $nama = "Langsung (SPP - LS Tunjangan Transport)";
                break;
            case '9': //TU
                $nama = "Langsung (SPP - LS Tunjangan Lainnya)";
                break;
            default:
                $nama = "LS";
        }
    } elseif ($beban == '5') {
        $nama = 'Langsung (SPM - LS PIHAK KETIGA LAINNYA)';
    } elseif ($beban == '6') {
        switch ($jenis) {
            case '1': //UP
                $nama = "Langsung (SPM-LS Tambahan Penghasilan)";
                break;
            case '2': //GU
                $nama = "Langsung (SPM-LS Operasional KDH/WKDH)";
                break;
            case '3': //TU
                $nama = "Langsung (SPM-LS Operasional DPRD)";
                break;
            case '4': //TU
                $nama = "Langsung (SPM-LS Honor Kontrak)";
                break;
            case '5': //TU
                $nama = "Langsung (SPM-LS Jasa Pelayanan Kesehatan)";
                break;
            case '6': //TU
                $nama = "Langsung (SPM-LS Pihak ketiga)";
                break;
            case '7': //TU
                $nama = "Langsung (SPM-LS PNS)";
                break;
        }
    }

    return $nama;
}

function nama_spm1($beban, $jenis)
{
    if ($beban == '1') {
        $nama = 'Uang Persediaan (UP)';
    } elseif ($beban == '2') {
        $nama = 'Ganti Uang (GU)';
    } elseif ($beban == '3') {
        $nama = 'Tambahan Uang Persediaan (TU)';
    } elseif ($beban == '4') {
        switch ($jenis) {
            case '1': //UP
                $nama = "Langsung (LS) Gaji dan Tunjangan";
                break;
            case '2': //GU
                $nama = "Langsung (LS) Uang Kespeg";
                break;
            case '3': //TU
                $nama = "Langsung (LS) Uang Makan";
                break;
            case '4': //TU
                $nama = "Langsung (LS) Upah Pungut";
                break;
            case '5': //TU
                $nama = "Langsung (LS) Upah Pungut PBB";
                break;
            case '6': //TU
                $nama = "Langsung (LS) Upah Pungut PBB-KB PKB & BBN-KB";
                break;
            case '7': //TU
                $nama = "Langsung (LS) Gaji & Tunjangan";
                break;
            case '8': //TU
                $nama = "Langsung (LS) Tunjangan Transport";
                break;
            case '9': //TU
                $nama = "Langsung (LS) Tunjangan Lainnya";
                break;
            default:
                $nama = "LS";
        }
    } elseif ($beban == '5') {
        $nama = 'Langsung (LS) Pihak Ketiga Lainnya';
    } elseif ($beban == '6') {
        switch ($jenis) {
            case '1': //UP
                $nama = "Langsung (LS) Tambahan Penghasilan";
                break;
            case '2': //GU
                $nama = "Langsung (LS) Operasional KDH/WKDH";
                break;
            case '3': //TU
                $nama = "Langsung (LS) Operasional DPRD";
                break;
            case '4': //TU
                $nama = "Langsung (LS) Honor Kontrak";
                break;
            case '5': //TU
                $nama = "Langsung (LS) Jasa Pelayanan Kesehatan";
                break;
            case '6': //TU
                $nama = "Langsung (LS) Pihak ketiga";
                break;
            case '7': //TU
                $nama = "Langsung (LS) PNS";
                break;
        }
    }

    return $nama;
}

function nama_beban1($beban, $jenis)
{
    if ($beban == '1') {
        $nama = 'UP';
    } elseif ($beban == '2') {
        $nama = 'GU';
    } elseif ($beban == '3') {
        $nama = 'TU';
    } elseif ($beban == '4') {
        $nama = 'LS-Gaji';
    } elseif ($beban == '5') {
        $nama = 'LS-Pihak Ketiga Lainnya';
    } elseif ($beban == '6') {
        $nama = 'LS-Barang dan Jasa';
    }

    return $nama;
}

function nama_beban2($beban, $jenis)
{
    if ($beban == '1') {
        $nama = 'UP';
    } elseif ($beban == '2') {
        $nama = 'GU';
    } elseif ($beban == '3') {
        $nama = 'TU';
    } elseif ($beban == '4') {
        switch ($jenis) {
            case '1': //UP
                $nama = "LS Gaji dan Tunjangan";
                break;
            case '2': //GU
                $nama = "LS Uang Kespeg";
                break;
            case '3': //TU
                $nama = "LS Uang Makan";
                break;
            case '4': //TU
                $nama = "LS Upah Pungut";
                break;
            case '5': //TU
                $nama = "LS Upah Pungut PBB";
                break;
            case '6': //TU
                $nama = "LS Upah Pungut PBB-KB PKB & BBN-KB ";
                break;
            case '7': //TU
                $nama = "LS Gaji & Tunjangan";
                break;
            case '8': //TU
                $nama = "LS Tunjangan Transport";
                break;
            case '9': //TU
                $nama = "LS Tunjangan Lainnya";
                break;
            default:
                $nama = "LS";
        }
    } elseif ($beban == '5') {
        $nama = 'LS Pihak Ketiga Lainnya';
    } elseif ($beban == '6') {
        switch ($jenis) {
            case '1': //UP
                $nama = "LS Tambahan Penghasilan";
                break;
            case '2': //GU
                $nama = "LS Operasional KDH/WKDH";
                break;
            case '3': //TU
                $nama = "LS Operasional DPRD";
                break;
            case '4': //TU
                $nama = "LS Honor Kontrak";
                break;
            case '5': //TU
                $nama = "LS Jasa Pelayanan Kesehatan";
                break;
            case '6': //TU
                $nama = "LS Pihak ketiga";
                break;
            case '7': //TU
                $nama = "LS PNS";
                break;
        }
    }

    return $nama;
}

function ringkasan_gu($kd_skpd, $beban, $tgl_spd, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        $revisi1 = DB::table('trhspd')->select(DB::raw("MAX(revisi_ke) as revisi1"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '3'])->first();
        $revisi2 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi2"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '6'])->first();
        $revisi3 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi3"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '9'])->first();
        $revisi4 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi4"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '12'])->first();

        if (substr($kd_skpd, 18, 4) == '0000') {
            $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'status' => '1'])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"));
            $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'status' => '1'])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban1);
            $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'status' => '1'])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban2);
            $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'status' => '1'])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban3);
            $data_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
                ->select('no_spd', 'tgl_spd', 'nilai')
                ->mergeBindings($beban4)
                ->orderBy('nilai')
                ->get();
        } else {
            $data_beban = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'a.kd_unit' => $kd_skpd, 'b.status' => '1'])->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->get();
        }
    } elseif ($beban == '3') {
        $revisi1 = DB::table('trhspd')->select(DB::raw("MAX(revisi_ke) as revisi1"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '3'])->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi2 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi2"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '6'])->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi3 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi3"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '9'])->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi4 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi4"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '12'])->where('tgl_spd', '<=', $tgl_spd)->first();

        $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'a.kd_unit' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd', 'a.kd_sub_kegiatan')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"), 'a.kd_sub_kegiatan');
        $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'a.kd_unit' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd', 'a.kd_sub_kegiatan')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"), 'a.kd_sub_kegiatan')->unionAll($beban1);
        $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'a.kd_unit' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd', 'a.kd_sub_kegiatan')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"), 'a.kd_sub_kegiatan')->unionAll($beban2);
        $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'a.kd_unit' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where(DB::raw("LEFT(a.kd_unit,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd', 'a.kd_sub_kegiatan')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"), 'a.kd_sub_kegiatan')->unionAll($beban3);
        $data_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
            ->select('no_spd', 'tgl_spd', 'nilai', 'kd_sub_kegiatan')
            ->mergeBindings($beban4)
            ->orderBy('nilai')
            ->get();
    } elseif ($beban == '4') {
        $revisi1 = DB::table('trhspd')->select(DB::raw("MAX(revisi_ke) as revisi1"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '3', 'status' => '1'])->first();
        $revisi2 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi2"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '6', 'status' => '1'])->first();
        $revisi3 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi3"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '9', 'status' => '1'])->first();
        $revisi4 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi4"))->where(['kd_skpd' => $kd_skpd, 'bulan_akhir' => '12', 'status' => '1'])->first();

        $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"));

        $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban1);

        $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban2);

        $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban3);

        $data_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
            ->select('no_spd', 'tgl_spd', 'nilai')
            ->mergeBindings($beban4)
            ->orderBy('nilai')
            ->get();
    } elseif ($beban == '6') {
        $revisi1 = DB::table('trhspd')->select(DB::raw("MAX(revisi_ke) as revisi1"))->where(['bulan_akhir' => '3', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->first();
        $revisi2 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi2"))->where(['bulan_akhir' => '6', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->first();
        $revisi3 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi3"))->where(['bulan_akhir' => '9', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->first();
        $revisi4 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi4"))->where(['bulan_akhir' => '12', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->first();

        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_unit,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_unit = '$kd_skpd'";
        }

        $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->whereRaw($skpd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"));

        $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->whereRaw($skpd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban1);

        $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->whereRaw($skpd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban2);

        $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['b.jns_beban' => '5', 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'status' => '1', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->whereRaw($skpd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban3);

        $data_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
            ->select('no_spd', 'tgl_spd', 'nilai')
            ->mergeBindings($beban4)
            ->orderBy('nilai')
            ->get();
    } elseif ($beban == '5') {
        $revisi1 = DB::table('trhspd')->select(DB::raw("MAX(revisi_ke) as revisi1"))->where(['bulan_akhir' => '3', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi2 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi2"))->where(['bulan_akhir' => '6', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi3 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi3"))->where(['bulan_akhir' => '9', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where('tgl_spd', '<=', $tgl_spd)->first();
        $revisi4 = DB::table('trhspd')->select(DB::raw("ISNULL(MAX(revisi_ke),0) as revisi4"))->where(['bulan_akhir' => '12', 'status' => '1'])->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where('tgl_spd', '<=', $tgl_spd)->first();

        $jns_sub_kegiatan = DB::table('ms_sub_kegiatan')->select('jns_sub_kegiatan')->where(['kd_sub_kegiatan' => $kd_sub_kegiatan])->first();

        if ($jns_sub_kegiatan->jns_sub_kegiatan == '5') {
            $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'b.jns_beban' => '5', 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"));

            $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'b.jns_beban' => '5', 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban1);

            $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'b.jns_beban' => '5', 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban2);

            $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $kd_skpd, 'b.jns_beban' => '5', 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(a.nilai) as nilai"))->unionAll($beban3);
        } else {
            $beban1 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trdskpd_ro as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on(DB::raw("LEFT(b.kd_skpd,17)"), '=', DB::raw("LEFT(c.kd_skpd,17)"));
            })->where(['c.kd_skpd' => $kd_skpd, 'b.jns_beban' => '6', 'revisi_ke' => $revisi1->revisi1, 'bulan_akhir' => '3', 'c.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(c.nilai) as nilai"));

            $beban2 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trdskpd_ro as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on(DB::raw("LEFT(b.kd_skpd,17)"), '=', DB::raw("LEFT(c.kd_skpd,17)"));
            })->where(['c.kd_skpd' => $kd_skpd, 'b.jns_beban' => '6', 'revisi_ke' => $revisi2->revisi2, 'bulan_akhir' => '6', 'c.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(c.nilai) as nilai"))->unionAll($beban1);

            $beban3 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trdskpd_ro as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on(DB::raw("LEFT(b.kd_skpd,17)"), '=', DB::raw("LEFT(c.kd_skpd,17)"));
            })->where(['c.kd_skpd' => $kd_skpd, 'b.jns_beban' => '6', 'revisi_ke' => $revisi3->revisi3, 'bulan_akhir' => '9', 'c.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(c.nilai) as nilai"))->unionAll($beban2);

            $beban4 = DB::table('trdspd as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trdskpd_ro as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on(DB::raw("LEFT(b.kd_skpd,17)"), '=', DB::raw("LEFT(c.kd_skpd,17)"));
            })->where(['c.kd_skpd' => $kd_skpd, 'b.jns_beban' => '6', 'revisi_ke' => $revisi4->revisi4, 'bulan_akhir' => '12', 'c.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('b.tgl_spd', '<=', $tgl_spd)->groupBy('a.no_spd', 'b.tgl_spd')->select('a.no_spd', 'b.tgl_spd', DB::raw("SUM(c.nilai) as nilai"))->unionAll($beban3);
        }

        $data_beban = DB::table(DB::raw("({$beban4->toSql()}) AS sub"))
            ->select('no_spd', 'tgl_spd', 'nilai')
            ->mergeBindings($beban4)
            ->orderBy('nilai')
            ->get();
    }

    return $data_beban;
}

function sp2dbelanja_up($kd_skpd, $beban, $no_spp, $tgl_spp, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        $nilai = DB::table('ms_up')->select('nilai_up as nilai')->where(['kd_skpd' => $kd_skpd])->first();
    } elseif (in_array($beban, ['3', '4'])) {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '1'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '1'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '1'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }
    return rupiah($nilai->nilai);
}

function sp2dbelanja_gu($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where(['a.jns_spp' => '2'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        } else {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['a.jns_spp' => '2', 'b.kd_bidang' => $kd_skpd])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        }
    } else if (in_array($beban, ['3', '4'])) {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.jns_spp' => '2', 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '2'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '2'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }

    return rupiah($nilai->nilai);
}

function sp2dbelanja_tu($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where(['a.jns_spp' => '3'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        } else {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['a.jns_spp' => '3', 'a.kd_skpd' => $kd_skpd])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        }
    } else if (in_array($beban, ['3', '4'])) {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.jns_spp' => '3', 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '3'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '3'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }

    return rupiah($nilai->nilai);
}

function sp2dbelanja_lsgaji($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where(['a.jns_spp' => '4'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        } else {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['a.jns_spp' => '4', 'a.kd_skpd' => $kd_skpd])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        }
    } elseif ($beban == '3') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.jns_spp' => '4', 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '4') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
        })->where(['a.jns_spp' => '4', 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(DB::raw("SUBSTRING(kd_rek6,1,5)"), '51010')->where(DB::raw("LEFT(a.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '4'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '4'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }

    return rupiah($nilai->nilai);
}

function sp2dbelanja_barang($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where(['a.jns_spp' => '6'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        } else {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['a.jns_spp' => '6', 'a.kd_skpd' => $kd_skpd])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        }
    } else if (in_array($beban, ['3', '4'])) {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.jns_spp' => '6', 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '6'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '6'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }

    return rupiah($nilai->nilai);
}

function sp2dbelanja_pihak($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    if ($beban == '2') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd', 17)"))->where(['a.jns_spp' => '5'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        } else {
            $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })->where(['a.jns_spp' => '5', 'a.kd_skpd' => $kd_skpd])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
        }
    } else if (in_array($beban, ['3', '4'])) {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', function ($join) {
            $join->on('a.no_spp', '=', 'c.no_spp');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->where(['a.jns_spp' => '5', 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
        } else {
            $skpd = "a.kd_skpd = '$kd_skpd'";
        }
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '5'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->whereRaw($skpd)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    } elseif ($beban == '5') {
        $nilai = DB::table('trdspp as b')->join('trhspp as a', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhsp2d as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_spp' => '5'])->where('a.no_spp', '!=', $no_spp)->where('c.tgl_sp2d', '<=', $tgl_spp)->where(function ($query) {
            $query->where('a.sp2d_batal', '')->orWhereNull('a.sp2d_batal');
        })->select(DB::raw("SUM(b.nilai) as nilai"))->first();
    }

    return rupiah($nilai->nilai);
}

function total_belanja($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    $up = angka(sp2dbelanja_up($kd_skpd, $beban, $no_spp, $tgl_spp, $kd_sub_kegiatan));
    $gu = angka(sp2dbelanja_gu($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    $tu = angka(sp2dbelanja_tu($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    $gaji = angka(sp2dbelanja_lsgaji($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    $barang = angka(sp2dbelanja_barang($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    $pihak = angka(sp2dbelanja_pihak($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    $total = $up + $gu + $tu + $gaji + $barang + $pihak;
    return rupiah($total);
}

function sisa_spd($total_spd, $kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan)
{
    $total_belanja = angka(total_belanja($kd_skpd, $no_spp, $tgl_spp, $beban, $kd_sub_kegiatan));
    return rupiah($total_spd - $total_belanja);
}

function judul_ringkasan($beban)
{
    if ($beban == '2') {
        $nama = 'UANG PERSEDIAAN';
    } else if ($beban == '3') {
        $nama = 'TAMBAHAN UANG PERSEDIAAN';
    } else if ($beban == '4') {
        $nama = 'LANGSUNG GAJI DAN TUNJANGAN';
    } elseif ($beban == '6') {
        $nama = 'LANGSUNG BARANG DAN JASA';
    } elseif ($beban == '5') {
        $nama = 'LANGSUNG PIHAK KETIGA LAINNYA';
    }
    return $nama;
}

function judul_ringkasan_ls($no_spm, $beban)
{
    $jenis = DB::table('trhspm')->select('jenis_beban')->where(['no_spm' => $no_spm])->first();
    if ($beban == '4') {
        switch ($jenis->jenis_beban) {
            case '1': //UP
                $lcbeban = "Gaji dan Tunjangan";
                break;
            case '2': //GU
                $lcbeban = "Uang Kespeg";
                break;
            case '3': //TU
                $lcbeban = "Uang Makan";
                break;
            case '4': //TU
                $lcbeban = "Upah Pungut";
                break;
            case '5': //TU
                $lcbeban = "Upah Pungut PBB";
                break;
            case '6': //TU
                $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                break;
            case '7': //TU
                $lcbeban = "Gaji & Tunjangan";
                break;
            case '8': //TU
                $lcbeban = "Tunjangan Transport";
                break;
            case '9': //TU
                $lcbeban = "Tunjangan Lainnya";
                break;
            default:
                $lcbeban = "LS";
        }
    } elseif ($beban == '6') {
        switch ($jenis->jenis_beban) {
            case '1': //UP
                $lcbeban = "Tambahan Penghasilan";
                break;
            case '2': //GU
                $lcbeban = "Operasional KDH/WKDH";
                break;
            case '3': //TU
                $lcbeban = " Operasional DPRD";
                break;
            case '4': //TU
                $lcbeban = " Honor Kontrak";
                break;
            case '5': //TU
                $lcbeban = " Jasa Pelayanan Kesehatan";
                break;
            case '6': //TU
                $lcbeban = " Pihak ketiga";
                break;
            case '7': //TU
                $lcbeban = " PNS";
                break;
        }
    } elseif ($beban == '5') {
        $lcbeban = 'PIHAK KETIGA LAINNYA';
    }

    return $lcbeban;
}

function nilai_anggaran_ringkasan($beban, $kd_skpd, $status_anggaran, $kd_sub_kegiatan, $no_spp)
{
    if ($beban == '2') {
        $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("LEFT(kd_rek6,1)"), '5')->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where(['jns_ang' => $status_anggaran])->first();
    } elseif ($beban == '3') {
        $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("SUBSTRING(kd_rek6,1,1)"), '5')->where(['kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran])->first();
    } elseif ($beban == '4') {
        $kdrek = DB::table('trdspp')->select('kd_rek6')->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])->orderBy('kd_rek6')->first();
        $kode = left($kdrek->kd_rek6, 5);
        if ($kode == '51010') {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("SUBSTRING(kd_rek6,1,5)"), '51010')->where(['kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran])->first();
        } else {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(['kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'jns_ang' => $status_anggaran])->first();
        }
    } elseif ($beban == '6') {
        if (substr($kd_skpd, 18, 4) == '0000') {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(['kd_sub_kegiatan' => $kd_sub_kegiatan, 'jns_ang' => $status_anggaran])->where(DB::raw("LEFT(kd_rek6,1)"), '5')->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->first();
        } else {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("LEFT(kd_rek6,1)"), '5')->where(['kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'jns_ang' => $status_anggaran])->first();
        }
    } elseif ($beban == '5') {
        $jns_sub_kegiatan = DB::table('ms_sub_kegiatan')->select('jns_sub_kegiatan')->where(['kd_sub_kegiatan' => $kd_sub_kegiatan])->first();
        if ($jns_sub_kegiatan->jns_sub_kegiatan == '5') {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("LEFT(kd_rek6,1)"), '5')->where(['kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'jns_ang' => $status_anggaran])->first();
        } else {
            $nilai = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(DB::raw("LEFT(kd_rek6,1)"), '6')->where(['kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'jns_ang' => $status_anggaran])->first();
        }
    }
    return $nilai;
}

function cara_bayar($no_spm)
{
    $cara_bayar = DB::table('trhspp as a')->join('trdspp as b', 'a.no_spp', '=', 'b.no_spp')->join('trhspm as c', 'b.no_spp', '=', 'c.no_spp')->leftJoin('trhtagih as d', 'a.no_tagih', '=', 'd.no_bukti')->where(['c.no_spm' => $no_spm])->groupBy('c.no_spm', 'd.jenis')->select('c.no_spm', DB::raw("ISNULL(d.jenis,0) as jns_tagih"))->first();
    $jns_tagih = $cara_bayar->jns_tagih;
    if ($jns_tagih == '' || $jns_tagih == '0') {
        $nama = "Tanpa Termin/Sekali Pembayaran";
    } elseif ($jns_tagih == '1') {
        $nama = "Konstruksi dalam pengerjaan";
    } elseif ($jns_tagih == "2") {
        $nama = "Uang muka";
    } elseif ($jns_tagih == "3") {
        $nama = "Hutang tahun lalu";
    } elseif ($jns_tagih == "4") {
        $nama = "Perbulan";
    } elseif ($jns_tagih == "5") {
        $nama = "Bertahap";
    } else {
        $nama = "Berdasarkan progress/pengajuan pekerjaan";
    }
    return $nama;
}

function nilai_pagu($kd_skpd, $no_spp, $beban)
{
    $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kd_skpd, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
    if ($beban == '2') {
        $kd_sub_kegiatan = DB::table('trdspp as a')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_spp' => $no_spp])->whereNotIn('b.jns_spp', ['1', '2'])->where(DB::raw("LEFT(a.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->select('a.kd_sub_kegiatan')->get();
        $data2 = json_decode(json_encode($kd_sub_kegiatan), true);
        $nilai_pagu = DB::table('trdrka')->select(DB::raw("SUM(nilai) as total"))->where(['jns_ang' => $status_anggaran->jns_ang])->whereIn('kd_sub_kegiatan', $data2)->first();
    } else {
        $kd_sub_kegiatan = DB::table('trdspp as a')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_spp' => $no_spp, 'a.kd_skpd' => $kd_skpd, 'b.kd_skpd' => $kd_skpd])->whereNotIn('b.jns_spp', ['1', '2'])->select('a.kd_sub_kegiatan')->get();
        $data2 = json_decode(json_encode($kd_sub_kegiatan), true);
        $nilai_pagu = DB::table('trdrka')->select(DB::raw("SUM(nilai) as total"))->where(['jns_ang' => $status_anggaran->jns_ang])->whereIn('kd_sub_kegiatan', $data2)->first();
    }

    return rupiah($nilai_pagu->total);
}

function cari_sp2d($sp2d, $baris)
{
    $kd_skpd = Auth::user()->kd_skpd;
    $status_ang = DB::table('trhrka as a')->join('tb_status_anggaran as b', 'a.jns_ang', '=', 'b.kode')->where(['a.kd_skpd' => $kd_skpd, 'status' => '1'])->orderByDesc('tgl_dpa')->select('a.jns_ang')->first();

    $data1 = DB::table('trdspp as a')->join('trskpd as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd, 'b.jns_ang' => $status_ang->jns_ang])->groupByRaw("LEFT(a.kd_sub_kegiatan,12), nm_kegiatan")->select(DB::raw("'1' as urut"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), 'b.nm_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"));
    $data2 = DB::table('trdspp as a')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->select(DB::raw("'2' as urut"), 'kd_sub_kegiatan', 'kd_sub_kegiatan as kd_rek', 'a.nm_sub_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data1);
    $data3 = DB::table('trdspp as a')->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_rek6', 'b.nm_rek6', 'kd_sub_kegiatan')->select(DB::raw("'3' as urut"), 'kd_sub_kegiatan', 'a.kd_rek6 as kd_rek', 'b.nm_rek6 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data2);

    $result = DB::table(DB::raw("({$data3->toSql()}) AS sub"))
        ->mergeBindings($data3)
        ->count();

    if ($result <= $baris) {
        $data1 = DB::table('trdspp as a')->join('trskpd as b', function ($join) {
            $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd, 'b.jns_ang' => $status_ang->jns_ang])->groupByRaw("LEFT(a.kd_sub_kegiatan,12), nm_kegiatan")->select(DB::raw("'1' as urut"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), 'b.nm_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"));
        $data2 = DB::table('trdspp as a')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->select(DB::raw("'2' as urut"), 'kd_sub_kegiatan', 'kd_sub_kegiatan as kd_rek', 'a.nm_sub_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data1);
        $data3 = DB::table('trdspp as a')->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_rek6', 'b.nm_rek6', 'kd_sub_kegiatan')->select(DB::raw("'3' as urut"), 'kd_sub_kegiatan', 'a.kd_rek6 as kd_rek', 'b.nm_rek6 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data2);

        $data = DB::table(DB::raw("({$data3->toSql()}) AS sub"))
            ->mergeBindings($data3)
            ->orderBy('urut')
            ->orderBy('kd_rek')
            ->get();
    } else {
        $data1 = DB::table('trdspp as a')->join('trskpd as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd, 'b.jns_ang' => $status_ang->jns_ang])->groupByRaw("LEFT(a.kd_sub_kegiatan,12), nm_kegiatan")->select(DB::raw("'1' as urut"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), 'b.nm_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"));
        $data2 = DB::query()->select(DB::raw("'2' as urut"), DB::raw("' ' as kd_sub_kegiatan"), DB::raw("' ' as kd_rek"), DB::raw("'(Rincian Terlampir)' as nm_rek"), DB::raw("0 as nilai"))->unionAll($data1);
        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->orderBy('urut')
            ->orderBy('kd_rek')
            ->get();
    }
    return $data;
}

function cari_lampiran($sp2d)
{

    $data1 = DB::table('trdspp as a')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupByRaw("LEFT(a.kd_sub_kegiatan,12)")->select(DB::raw("'1' as no"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), DB::raw("(SELECT nm_kegiatan FROM ms_kegiatan WHERE LEFT(a.kd_sub_kegiatan,12)=kd_kegiatan) as nm_rek"), DB::raw("SUM(nilai) as nilai"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as urut"));
    $data2 = DB::table('trdspp as a')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->select(DB::raw("'2' as no"), 'a.kd_sub_kegiatan', 'a.kd_sub_kegiatan as kd_rek', 'a.nm_sub_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"), 'a.kd_sub_kegiatan as urut')->unionAll($data1);
    $data3 = DB::table('trdspp as a')->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_rek6', 'b.nm_rek6', 'kd_sub_kegiatan')->select(DB::raw("'3' as no"), 'kd_sub_kegiatan', 'a.kd_rek6 as kd_rek', 'b.nm_rek6 as nm_rek', DB::raw("SUM(nilai) as nilai"), DB::raw("a.kd_sub_kegiatan+'.'+a.kd_rek6 as urut"))->unionAll($data2);

    $data = DB::table(DB::raw("({$data3->toSql()}) AS sub"))
        ->mergeBindings($data3)
        ->orderBy('urut')
        ->get();

    return $data;
}

function cari_lampiran_lama($sp2d)
{

    $data1 = DB::table('trdspp as a')->join('ms_rek2 as b', DB::raw("LEFT(a.kd_rek6,2)"), '=', 'b.kd_rek2')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy(DB::raw("LEFT(a.kd_rek6,2)"), 'nm_rek2', 'kd_sub_kegiatan')->select('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,2) as kd_rek"), 'nm_rek2 as nm_rek', DB::raw("SUM(nilai) as nilai"));

    $data2 = DB::table('trdspp as a')->join('ms_rek3 as b', DB::raw("LEFT(a.kd_rek6,4)"), '=', 'b.kd_rek3')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy(DB::raw("LEFT(a.kd_rek6,4)"), 'nm_rek3', 'kd_sub_kegiatan')->select('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,4) as kd_rek"), 'nm_rek3 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data1);

    $data3 = DB::table('trdspp as a')->join('ms_rek4 as b', DB::raw("LEFT(a.kd_rek6,6)"), '=', 'b.kd_rek4')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy(DB::raw("LEFT(a.kd_rek6,6)"), 'nm_rek4', 'kd_sub_kegiatan')->select('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,6) as kd_rek"), 'nm_rek4 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data2);

    $data4 = DB::table('trdspp as a')->join('ms_rek5 as b', DB::raw("LEFT(a.kd_rek6,8)"), '=', 'b.kd_rek5')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy(DB::raw("LEFT(a.kd_rek6,8)"), 'nm_rek5', 'kd_sub_kegiatan')->select('kd_sub_kegiatan', DB::raw("LEFT(a.kd_rek6,8) as kd_rek"), 'nm_rek5 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data3);

    $data5 = DB::table('trdspp as a')->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['a.no_spp' => $sp2d->no_spp, 'a.kd_skpd' => $sp2d->kd_skpd])->groupBy('a.kd_rek6', 'b.nm_rek6', 'kd_sub_kegiatan')->select('kd_sub_kegiatan', 'a.kd_rek6 as kd_rek', 'b.nm_rek6 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data4);

    $data = DB::table(DB::raw("({$data5->toSql()}) AS sub"))
        ->mergeBindings($data5)
        ->orderBy('kd_rek')
        ->get();

    return $data;
}

function cetak_penguji($kd_skpd)
{
    $data = DB::table('ms_ttd')->select('nama', 'jabatan')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BK'])->first();
    return $data->nama;
}

function no_urut($kd_skpd)
{
    if ($kd_skpd == '1.01.2.22.0.00.01.0000' || $kd_skpd == '4.01.0.00.0.00.01.0003' || $kd_skpd == '1.02.0.00.0.00.02.0000' || $kd_skpd == '1.02.0.00.0.00.03.0000') {
        $urut1 = DB::table('trhtransout_blud')->where(['panjar' => '3'])->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Transaksi BOS BLUD' as ket"), 'kd_skpd');
        $urut2 = DB::table('trhtransout_blud_penerimaan')->where(['panjar' => '3'])->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Transaksi BOS BLUD' as ket"), 'kd_skpd')->unionAll($urut1);
        $urut3 = DB::table('trhspb_hibah_skpd')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'SPB HIBAH' as ket"), 'kd_skpd')->unionAll($urut2);
        $urut4 = DB::table('trhsp2h')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'SPB HIBAH' as ket"), 'kd_skpd')->unionAll($urut3);
        $urut5 = DB::table('trhsp2b')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'SPB HIBAH' as ket"), 'kd_skpd')->unionAll($urut4);
        $urut6 = DB::table('trhkasin_pkd_bos')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'PENGEMBALIAN BOS' as ket"), 'kd_skpd')->unionAll($urut5);
        $urut7 = DB::table('trhsp2d')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->where('status', '1')->select('no_kas as nomor', DB::raw("'Pencairan SP2D' as ket"), 'kd_skpd')->unionAll($urut6);
        $urut8 = DB::table('trhsp2d')->where(DB::raw("ISNUMERIC(no_terima)"), '1')->where('status_terima', '1')->select('no_terima as nomor', DB::raw("'Pencairan SP2D' as ket"), 'kd_skpd')->unionAll($urut7);
        $urut9 = DB::table('trhtransout')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where(function ($query) {
            $query->where('panjar', '!=', '3')->orWhereNull('panjar');
        })->select('no_bukti as nomor', DB::raw("'Pembayaran Transaksi' as ket"), 'kd_skpd')->unionAll($urut8);
        $urut10 = DB::table('trhtransout')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('panjar', '3')->select('no_bukti as nomor', DB::raw("'Koreksi Transaksi' as ket"), 'kd_skpd')->unionAll($urut9);
        $urut11 = DB::table('tr_panjar')->where(DB::raw("ISNUMERIC(no_panjar)"), '1')->select('no_panjar as nomor', DB::raw("'Pemberian Panjar' as ket"), 'kd_skpd')->unionAll($urut10);
        $urut12 = DB::table('tr_panjar_cmsbank')->where(DB::raw("ISNUMERIC(no_panjar)"), '1')->select('no_panjar as nomor', DB::raw("'Pemberian Panjar CMS' as ket"), 'kd_skpd')->unionAll($urut11);
        $urut13 = DB::table('tr_jpanjar')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Pertanggungjawaban Panjar' as ket"), 'kd_skpd')->unionAll($urut12);
        $urut14 = DB::table('trhtrmpot')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Penerimaan Potongan' as ket"), 'kd_skpd')->unionAll($urut13);
        $urut15 = DB::table('trhstrpot')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Penyetoran Potongan' as ket"), 'kd_skpd')->unionAll($urut14);
        $urut16 = DB::table('trhkasin_pkd')->where(DB::raw("ISNUMERIC(no_sts)"), '1')->where('jns_trans', '<>', '4')->select(DB::raw("no_sts+1 as nomor"), DB::raw("'Setor Sisa Kas' as ket"), 'kd_skpd')->unionAll($urut15);
        $urut17 = DB::table('trhkasin_pkd')->where(DB::raw("ISNUMERIC(no_sts)"), '1')->where('jns_trans', '<>', '4')->where('pot_khusus', '1')->select(DB::raw("no_sts+1 as nomor"), DB::raw("'Setor Sisa Kas' as ket"), 'kd_skpd')->unionAll($urut16);
        $urut18 = DB::table('tr_ambilsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where(function ($query) {
            $query->where('status_drop', '!=', '1')->orWhereNull('status_drop');
        })->select(DB::raw("no_bukti+1 as nomor"), DB::raw("'Ambil Simpanan' as ket"), 'kd_skpd')->unionAll($urut17);
        $urut19 = DB::table('tr_ambilsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('status_drop', '1')->select('no_bukti as nomor', DB::raw("'Ambil Drop Dana' as ket"), 'kd_skpd')->unionAll($urut18);
        $urut20 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_kas as nomor', DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut19);
        $urut21 = DB::table('tr_setorpelimpahan_bank_cms')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_kas as nomor', DB::raw("'Setor Simpanan CMS' as ket"), 'kd_skpd')->unionAll($urut20);
        $urut22 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('jenis', '2')->select(DB::raw("no_kas+1 as nomor"), DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut21);
        $urut23 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('jenis', '3')->select(DB::raw("no_kas+1 as nomor"), DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut22);
        $urut24 = DB::table('TRHINLAIN')->where(DB::raw("ISNUMERIC(NO_BUKTI)"), '1')->select('NO_BUKTI as nomor', DB::raw("'Terima lain-lain' as ket"), 'KD_SKPD as kd_skpd')->unionAll($urut23);
        $urut25 = DB::table('TRHOUTLAIN')->where(DB::raw("ISNUMERIC(NO_BUKTI)"), '1')->select('NO_BUKTI as nomor', DB::raw("'Keluar lain-lain' as ket"), 'KD_SKPD as kd_skpd')->unionAll($urut24);
        $urut26 = DB::table('tr_setorpelimpahan_bank_cms')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Drop Uang ke Bidang' as ket"), 'kd_skpd')->unionAll($urut25);
        $urut27 = DB::table('tr_setorpelimpahan')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Drop Uang ke Bidang' as ket"), 'kd_skpd')->unionAll($urut26);

        $urut = DB::table(DB::raw("({$urut27->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut27)
            ->first();
        return $urut->nomor;
    } else {
        $urut1 = DB::table('trhsp2d')->where(['status' => '1'])->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Pencairan SP2D' as ket"), 'kd_skpd');
        $urut2 = DB::table('trhsp2d')->where(['status_terima' => '1'])->where(DB::raw("ISNUMERIC(no_terima)"), '1')->select('no_terima as nomor', DB::raw("'Penerimaan SP2D' as ket"), 'kd_skpd')->unionAll($urut1);
        $urut3 = DB::table('trhtransout')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where(function ($query) {
            $query->where('panjar', '!=', '3')->orWhereNull('panjar');
        })->select('no_bukti as nomor', DB::raw("'Pembayaran Transaksi' as ket"), 'kd_skpd')->unionAll($urut2);
        $urut4 = DB::table('trhtransout')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('panjar', '3')->select('no_bukti as nomor', DB::raw("'Koreksi Transaksi' as ket"), 'kd_skpd')->unionAll($urut3);
        $urut5 = DB::table('tr_panjar')->where(DB::raw("ISNUMERIC(no_panjar)"), '1')->select('no_panjar as nomor', DB::raw("'Pemberian Panjar' as ket"), 'kd_skpd')->unionAll($urut4);
        $urut6 = DB::table('tr_panjar_cmsbank')->where(DB::raw("ISNUMERIC(no_panjar)"), '1')->select('no_panjar as nomor', DB::raw("'Pemberian Panjar CMS' as ket"), 'kd_skpd')->unionAll($urut5);
        $urut7 = DB::table('tr_jpanjar')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Pertanggungjawaban Panjar' as ket"), 'kd_skpd')->unionAll($urut6);
        $urut8 = DB::table('trhtrmpot')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Penerimaan Potongan' as ket"), 'kd_skpd')->unionAll($urut7);
        $urut9 = DB::table('trhstrpot')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_bukti as nomor', DB::raw("'Penyetoran Potongan' as ket"), 'kd_skpd')->unionAll($urut8);
        $urut10 = DB::table('trhkasin_pkd')->where(DB::raw("ISNUMERIC(no_sts)"), '1')->where('jns_trans', '<>', '4')->select(DB::raw("no_sts+1 as nomor"), DB::raw("'Setor Sisa Kas' as ket"), 'kd_skpd')->unionAll($urut9);
        $urut11 = DB::table('trhkasin_pkd')->where(DB::raw("ISNUMERIC(no_sts)"), '1')->where('jns_trans', '<>', '4')->where('pot_khusus', '1')->select(DB::raw("no_sts+1 as nomor"), DB::raw("'Setor Sisa Kas' as ket"), 'kd_skpd')->unionAll($urut10);
        $urut12 = DB::table('tr_ambilsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where(function ($query) {
            $query->where('status_drop', '!=', '1')->orWhereNull('status_drop');
        })->select(DB::raw("no_bukti+1 as nomor"), DB::raw("'Ambil Simpanan' as ket"), 'kd_skpd')->unionAll($urut11);
        $urut13 = DB::table('tr_ambilsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('status_drop', '1')->select('no_bukti as nomor', DB::raw("'Ambil Drop Dana' as ket"), 'kd_skpd')->unionAll($urut12);
        $urut14 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_kas as nomor', DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut13);
        $urut15 = DB::table('tr_setorpelimpahan_bank_cms')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->select('no_kas as nomor', DB::raw("'Setor Simpanan CMS' as ket"), 'kd_skpd_sumber as kd_skpd')->unionAll($urut14);
        $urut16 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('jenis', '2')->select(DB::raw("no_kas+1 as nomor"), DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut15);
        $urut17 = DB::table('tr_setorsimpanan')->where(DB::raw("ISNUMERIC(no_bukti)"), '1')->where('jenis', '3')->select(DB::raw("no_kas+1 as nomor"), DB::raw("'Setor Simpanan' as ket"), 'kd_skpd')->unionAll($urut16);
        $urut18 = DB::table('TRHINLAIN')->where(DB::raw("ISNUMERIC(NO_BUKTI)"), '1')->select('NO_BUKTI as nomor', DB::raw("'Terima lain-lain' as ket"), 'KD_SKPD as kd_skpd')->unionAll($urut17);
        $urut19 = DB::table('TRHOUTLAIN')->where(DB::raw("ISNUMERIC(NO_BUKTI)"), '1')->select('NO_BUKTI as nomor', DB::raw("'Keluar lain-lain' as ket"), 'KD_SKPD as kd_skpd')->unionAll($urut18);
        $urut20 = DB::table('tr_setorpelimpahan_bank_cms')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Drop Uang ke Bidang' as ket"), 'kd_skpd_sumber as kd_skpd')->unionAll($urut19);
        $urut21 = DB::table('tr_setorpelimpahan')->where(DB::raw("ISNUMERIC(no_kas)"), '1')->select('no_kas as nomor', DB::raw("'Drop Uang ke Bidang' as ket"), 'kd_skpd_sumber as kd_skpd')->unionAll($urut20);
        $urut = DB::table(DB::raw("({$urut21->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut21)
            ->first();
        return $urut->nomor;
    }
}

function cair_sp2d($data_sp2d)
{
    $kd_skpd = Auth::user()->kd_skpd;

    $data1 = DB::table('trdspp as a')->join('trskpd as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')->where(['a.no_spp' => $data_sp2d->no_spp, 'a.kd_skpd' => $data_sp2d->kd_skpd])->groupByRaw("LEFT(a.kd_sub_kegiatan,12), nm_kegiatan")->select(DB::raw("'1' as urut"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), 'b.nm_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"));
    $data2 = DB::table('trdspp as a')->where(['a.no_spp' => $data_sp2d->no_spp, 'a.kd_skpd' => $data_sp2d->kd_skpd])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->select(DB::raw("'2' as urut"), 'kd_sub_kegiatan', 'kd_sub_kegiatan as kd_rek', 'a.nm_sub_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data1);
    $data3 = DB::table('trdspp as a')->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['a.no_spp' => $data_sp2d->no_spp, 'a.kd_skpd' => $data_sp2d->kd_skpd])->groupBy('a.kd_rek6', 'b.nm_rek6', 'kd_sub_kegiatan')->select(DB::raw("'3' as urut"), 'kd_sub_kegiatan', 'a.kd_rek6 as kd_rek', 'b.nm_rek6 as nm_rek', DB::raw("SUM(nilai) as nilai"))->unionAll($data2);

    $result = DB::table(DB::raw("({$data3->toSql()}) AS sub"))
        ->mergeBindings($data3)
        ->count();

    $data1 = DB::table('trdspp as a')->join('trskpd as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')->where(['a.no_spp' => $data_sp2d->no_spp, 'a.kd_skpd' => $data_sp2d->kd_skpd])->groupByRaw("LEFT(a.kd_sub_kegiatan,12), nm_kegiatan")->select(DB::raw("'1' as urut"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_sub_kegiatan"), DB::raw("LEFT(a.kd_sub_kegiatan,12) as kd_rek"), 'b.nm_kegiatan as nm_rek', DB::raw("SUM(nilai) as nilai"));
    $data2 = DB::query()->select(DB::raw("'2' as urut"), DB::raw("' ' as kd_sub_kegiatan"), DB::raw("' ' as kd_rek"), DB::raw("'(Rincian Terlampir)' as nm_rek"), DB::raw("0 as nilai"))->unionAll($data1);
    $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
        ->mergeBindings($data2)
        ->orderBy('urut')
        ->orderBy('kd_rek')
        ->get();
    return $data;
}

function cari_kontrak($data)
{
    $hasil = DB::table('trhspp')->select('kontrak')->where(['no_spp' => $data])->first();
    return $hasil->kontrak;
}