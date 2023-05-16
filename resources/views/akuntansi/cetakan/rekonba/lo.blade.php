<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekon BA LO</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #rincian>tbody>tr>td {
            vertical-align: top
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: 1px solid black
        }
    </style>
</head>

<body>
    {{-- <body> --}}

    <table style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1"
        align="center">
        <tr>
            <td rowspan="4" align="center" style="border-right:hidden; border-bottom: hidden;">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PEMERINTAH PROVINSI
                    KALIMANTAN BARAT </strong></td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="font-size:16px; border-bottom: hidden;"><strong>BADAN KEUANGAN DAN
                    ASET DAERAH</strong>
        </tr>
        <tr>
            <td colspan="3" align="center" style="font-size:12px; border-bottom: hidden;"><strong>Jalan Ahmad Yani
                    Telepon (0561) 736541 Email: bkad@kalbarprov.go.id Website: bkad.kalbarprov.go.id</strong>
        </tr>
        <tr>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PONTIANAK</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4" align="right">Kode Pos: 78124 &nbsp; &nbsp;</td>
        </tr>
    </table>
    <hr valign="top" color="black" size="3px" width="100%">


    {{-- isi --}}
    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td rowspan="2" align="right" width="10%" height="50">&nbsp;</td>
            <td colspan="3" align="center" style="font-size:14px"><b>Laporan Operasional Tahun Anggaran
                    {{ $thn_ang }}</b></td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="font-size:14px"><b>Periode {{ tgl_format_oyoy($periode1) }} -
                    {{ tgl_format_oyoy($periode2) }}</b></td>
        </tr>
        <tr>
            <td colspan="5" align="justify" style="font-size:12px">
                <br>
                SKPD : {{ $kd_skpd }} - {{ nama_skpd($kd_skpd) }}
                <br>
                <br>
            </td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center"
        border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <td rowspan="2" bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>
                <td colspan="2" bgcolor="#CCCCCC" width="60%" align="center"><b>LAPORAN OPERASIONAL TA.
                        {{ $thn_ang }}</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>KETERANGAN</b></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" width="35%" align="center"><b>Uraian</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>Nilai</b></td>
            </tr>
        </thead>
        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%">&nbsp;</td>
        </tr>
        @php
            $no = 0;
        @endphp
        @foreach ($sql as $loquery)
            @php
                $nama = $loquery->uraian;
                $bold = $loquery->bold;
                
                $n1 = $loquery->kode_1ja;
                $n1 = $n1 == '-' ? "'-'" : $n1;
                $n2 = $loquery->kode;
                $n2 = $n2 == '-' ? "'-'" : $n2;
                $n3 = $loquery->kode_1;
                $n3 = $n3 == '-' ? "'-'" : $n3;
                $n4 = $loquery->kode_2;
                $n4 = $n4 == '-' ? "'-'" : $n4;
                $n5 = $loquery->kode_3;
                $n5 = $n5 == '-' ? "'-'" : $n5;
                $cetak_a = $loquery->cetak;
                $k1 = $loquery->kurangi_1;
                $k1 = $k1 == '-' ? "'-'" : $k1;
                $k2 = $loquery->kurangi;
                $k2 = $k2 == '-' ? "'-'" : $k2;
                $cetak_k = $loquery->c_kurangi;
                
                $nilainya = collect(
                    DB::select("select isnull(sum(nilai_a-nilai_b),0) nilai
                    from(SELECT SUM($cetak_a) as nilai_a,0 nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE (left(kd_rek6,1) in ($n1) or left(kd_rek6,2) in ($n2) or left(kd_rek6,4) in ($n3) or left(kd_rek6,6) in ($n4) or left(kd_rek6,8) in ($n5))
                    and tgl_voucher between '$periode1' and '$periode2' and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'

                    union all

                    SELECT 0  nilai_a,SUM($cetak_k) nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE (left(kd_rek6,1) in ($k1) or left(kd_rek6,2) in ($k2))
                    and tgl_voucher between '$periode1' and '$periode2' and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd') a "),
                )->first();
                
                $nilai = $nilainya->nilai;
                
                $nilainya_lalu = collect(
                    DB::select("select sum(nilai_a-nilai_b) nilai
                    from(SELECT isnull(SUM(kredit-debet),0) as nilai_a,0 nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE (left(kd_rek6,1) in ($n1) or left(kd_rek6,2) in ($n2) or left(kd_rek6,4) in ($n3) or left(kd_rek6,6) in ($n4) or left(kd_rek6,8) in ($n5))
                    and year(tgl_voucher)=$thn_ang1 and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'

                    union all

                    SELECT 0  nilai_a,SUM($cetak_k) nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE (left(kd_rek6,1) in ($k1) or left(kd_rek6,2) in ($k2))
                    and year(tgl_voucher)=$thn_ang1 and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd') a "),
                )->first();
                
                $nilai_lalu = $nilainya_lalu->nilai;
                $real_nilai = $nilai - $nilai_lalu;
                if ($real_nilai < 0) {
                    $lo0 = '(';
                    $real_nilai1 = $real_nilai * -1;
                    $lo00 = ')';
                } else {
                    $lo0 = '';
                    $real_nilai1 = $real_nilai;
                    $lo00 = '';
                }
                
                if ($nilai_lalu == '' || $nilai_lalu == 0) {
                    $persen1 = 0;
                } else {
                    $persen1 = ($real_nilai / $nilai_lalu) * 100;
                }
                
                $no = $no + 1;
            @endphp

            @if ($bold == 0)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        {{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                </tr>
            @elseif ($bold == 1 || $bold == 2)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        {{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                </tr>
            @else
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        {{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($nilai) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                </tr>
            @endif
        @endforeach
    </table>
    {{-- isi --}}

    <div style="padding-top:20px">
        <table style="border-collapse:collapse;font-family: Arial; font-size:14px" width="100%" align="center"
            border="0" cellspacing="0" cellpadding="0">
            <tr>
            <tr>
                <td colspan="4" align="right">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right">Paraf .......................
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="4" align="right">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right">&nbsp; &nbsp;</td>
            </tr>
        </table>
    </div>

    {{-- tanda tangan --}}

</body>

</html>
