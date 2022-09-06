<?php


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
