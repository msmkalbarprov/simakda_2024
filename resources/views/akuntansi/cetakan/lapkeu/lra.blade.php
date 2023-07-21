<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA SKPD</title>
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

<body onload="window.print()">
    {{-- <body> --}}

    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>
        </tr>
        @if ($kd_skpd == '')
        @elseif(strlen($kd_skpd) == 17)
            <TR>
                <td align="center"><strong>{{ nama_org($kd_skpd) }}</strong></td>
            </TR>
        @else
            <TR>
                <td align="center"><strong>{{ nama_skpd($kd_skpd) }}</strong></td>
            </TR>
        @endif
        <TR>
            <td align="center"><strong>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA</strong></td>
        </TR>
        @if ($periodebulan = 'bulan')
            <TR>
                <td align="center"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN {{ $nm_bln }}
                        {{ $thn_ang }} </strong></td>
            </TR>
        @else
            <TR>
                <td align="center"><strong>UNTUK PERIODE {{ $tgl_format_oyoy(tanggal1) }} SAMPAI DENGAN
                        {{ $tgl_format_oyoy(tanggal1) }} </strong></td>
            </TR>
        @endif


    </TABLE>

    <table style="font-size:11px;border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0"
        cellpadding="4">
        <thead>
            <tr>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>
                <td bgcolor="#CCCCCC" width="40%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>ANGGARAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>REALISASI</b></td>
                <td bgcolor="#CCCCCC" width="15%" align="center"><b>LEBIH</br>(KURANG)</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>%</b></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
            </tr>
        </tfoot>
        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="15%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%">&nbsp;</td>
        </tr>
        @php
            $no = 0;
            $ang_surplus = $sus->ang_surplus;
            $nil_surplus = $sus->nil_surplus;
            $sisa_surplus = $ang_surplus - $nil_surplus;
            if ($ang_surplus == 0 || $ang_surplus == '') {
                $persen_surplus = 0;
            } else {
                $persen_surplus = ($nil_surplus / $ang_surplus) * 100;
            }
            if ($ang_surplus < 0) {
                $ang_surplus = $ang_surplus * -1;
                $a = '(';
                $b = ')';
            } else {
                $ang_surplus = $ang_surplus;
                $a = '';
                $b = '';
            }

            if ($nil_surplus < 0) {
                $nil_surplus = $nil_surplus * -1;
                $c = '(';
                $d = ')';
            } else {
                $nil_surplus = $nil_surplus;
                $c = '';
                $d = '';
            }
            if ($sisa_surplus < 0) {
                $sisa_surplus = $sisa_surplus * -1;
                $i = '(';
                $j = ')';
            } else {
                $sisa_surplus = $sisa_surplus;
                $i = '';
                $j = '';
            }
        @endphp
        @foreach ($map_lra as $row4)
            @php
                $normal = $row4->cetak;
                $bold = $row4->bold;
                $parent = $row4->parent;
                $nama = $row4->uraian;
                $real_lalu = number_format($row4->lalu, '2', ',', '.');
                $n = $row4->kode_1;
                $n = $n == '-' ? "'-'" : $n;
                $n2 = $row4->kode_2;
                $n2 = $n2 == '-' ? "'-'" : $n2;
                $n3 = $row4->kode_3;
                $n3 = $n3 == '-' ? "'-'" : $n3;
                if ($skpdunit=="unit") {
                    
                    $nilainya = collect(DB::select("SELECT isnull(SUM(case when LEFT(kd_rek6,2) in ('51','52','62') and $parent=1 then b.anggaran*-1 else b.anggaran end),0) as anggaran, ISNULL(SUM($normal),0) as nilai FROM data_realisasi_n_pemda_unit($bulan,'$jns_ang',$thn_ang,'$kd_skpd') b WHERE (left(b.kd_rek6,4) in ($n) or left(b.kd_rek6,6) in ($n2) or left(b.kd_rek6,8) in ($n3))"))->first();
                }else if ($skpdunit=="skpd") {
                    $nilainya = collect(DB::select("SELECT isnull(SUM(case when LEFT(kd_rek6,2) in ('51','52','62') and $parent=1 then b.anggaran*-1 else b.anggaran end),0) as anggaran, ISNULL(SUM($normal),0) as nilai FROM data_realisasi_n_pemda_unit_tgl('$tanggal1','$tanggal2','$jns_ang','$kd_skpd') b WHERE (left(b.kd_rek6,4) in ($n) or left(b.kd_rek6,6) in ($n2) or left(b.kd_rek6,8) in ($n3))"))->first();
                }

                $nilai = $nilainya->nilai;
                $anggaran = $nilainya->anggaran;

                $selisih = $nilai - $anggaran;
                if ($selisih < 0) {
                    $sela = '(';
                    $selisih1 = $selisih * -1;
                    $selb = ')';
                } else {
                    $sela = '';
                    $selisih1 = $selisih;
                    $selb = '';
                }

                if ($anggaran == '' || $anggaran == 0) {
                    $persen = 0;
                } else {
                    $persen = ($nilai / $anggaran) * 100;
                }

                // KHUSUS SURFLUS
                if ($nilai < 0) {
                    $x = '(';
                    $z = ')';
                    $nilai = $nilai * -1;
                    $selisih2 = $nilai - $anggaran;
                    if ($selisih2 < 0) {
                        $x = '(';
                        $selisih2 = $selisih2 * -1;
                        $z = ')';
                    }
                } else {
                    $x = '';
                    $z = '';
                    $nilai = $nilai;
                    $selisih2 = $nilai - $anggaran;
                    if ($selisih2 < 0) {
                        $x = '(';
                        $selisih2 = $selisih2 * -1;
                        $z = ')';
                    }
                }

                $no = $no + 1;
            @endphp

            @if ($bold == 0)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{$no}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right"></td>
                </tr>
            @elseif ($bold == 1)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        {{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($anggaran) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($nilai) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $sela }}{{ rupiah($selisih1) }}{{ $selb }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right">{{ rupiah($persen) }}</td>
                </tr>
            @elseif ($bold == 2)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($anggaran) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($nilai) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $sela }}{{ rupiah($selisih1) }}{{ $selb }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right">{{ rupiah($persen) }}</td>
                </tr>
            @elseif ($bold == 3)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $nama }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($anggaran) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($nilai) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $sela }}{{ rupiah($selisih1) }}{{ $selb }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right">{{ rupiah($persen) }}</td>
                </tr>
            @elseif ($bold == 4)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{ $no }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $nama }}
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($anggaran) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ rupiah($nilai) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $sela }}{{ rupiah($selisih1) }}{{ $selb }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right">{{ rupiah($persen) }}</td>
                </tr>
            @elseif ($bold == 5)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="center">{{ $no }}x</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $nama }}
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ $a }}{{ rupiah($anggaran) }}{{ $b }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"
                        align="right">{{ $c }}{{ rupiah($nilai) }}{{ $d }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $x }}{{ rupiah($selisih2) }}{{ $z }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%"
                        align="right">{{ rupiah($persen_surplus) }}</td>
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
                        align="right">{{ rupiah($nilai_lalu) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="right">{{ $lo0 }}{{ rupiah($real_nilai1) }}{{ $lo00 }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%"
                        align="right">{{ rupiah($persen1) }}</td>
                </tr>
            @endif
        @endforeach


        @if ($jenis_ttd != 0)
            <div style="padding-top:20px">
                <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;"
                            width='50%'>
                            &nbsp;
                        </td>
                        <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;"
                            width='50%'>
                            {{ $daerah->daerah }},
                            {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                        </td>
                        <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                            {{ ucwords(strtolower($tandatangan->jabatan)) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                    </tr>
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;">
                            <b><u>{{ $tandatangan->nama }}</u></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                    </tr>
                    <tr>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                        <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                    </tr>

                </table>
            </div>
        @endif
        {{-- tanda tangan --}}

</body>

</html>
