<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MANDATORY KESEHATAN</title>
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

        .angka {
            text-align: right
        }
    </style>
</head>

<body onload="window.print()">
    {{-- <body> --}}


    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <TR>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>
        </TR>
        <TR>
            <td align="center"><strong>REKAPITULASI REALISASI ANGGARAN MANDATORY SPENDING-BIDANG KESEHATAN TA
                    {{ tahun_anggaran() }}</strong></td>
        </TR>
    </TABLE>


    <TABLE style="border-collapse:collapse;font-size:14px;font-family:'Open Sans', Helvetica,Arial,sans-serif"
        border="1" width="100%">
        <THEAD>
            <TR>
                <th align="center" width=1%>No.</th>
                <th align="center" width=45%> Komponen Perhitungan</th>
                <th align="center" width=15%>Anggaran (Rp)</th>
                <th align="center" width=15%>Realisasi (Rp)</th>
                <th align="center" width=10%>Persentase (%)</th>
            </TR>
        </THEAD>
        <tbody>
            @foreach ($data as $item)
                @php
                    $nor = $item->nor;
                    $uraian = $item->uraian;
                    $bold = $item->bold;
                    $kode_1 = $item->kode_1;
                    $kd_skpd = $item->kd_skpd;

                    $nilai = DB::select(
                        "SELECT (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd in($kd_skpd) and left(kd_rek6,4) in ($kode_1)) anggaran,
                    (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='2023' and kd_skpd in($kd_skpd) and left(kd_rek6,4)in ($kode_1))
realisasi",
                        [$jns_ang],
                    );
                    foreach ($nilai as $res) {
                        $anggaran = $res->anggaran;
                        $realisasi = $res->realisasi;
                        if ($anggaran == 0) {
                            $persen = 0;
                        } else {
                            $persen = ($realisasi / $anggaran) * 100;
                        }
                    }
                @endphp

                @switch($bold)
                    @case(1)
                        @if ($nor == 1)
                            <tr>
                                <td style="text-align: center"><b>1</b></td>
                                <td><b>{{ $uraian }}</b></td>
                                <td class="angka"><b>{{ rupiah($anggaran) }}</b></td>
                                <td class="angka"><b>{{ rupiah($realisasi) }}</b></td>
                                <td style="text-align: center"><b>{{ rupiah($persen) }}</b></td>
                            </tr>
                        @else
                            @php
                                $nilai = DB::select(
                                    "SELECT (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd in($kd_skpd) and (kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in ($kd_skpd) group by kd_rek6) or left(kd_rek6,4) in ($kode_1)) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in ($kd_skpd) group by kd_sub_kegiatan) ) anggaran,
                                (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)='2023' and kd_skpd in($kd_skpd) and (kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in ($kd_skpd) group by kd_rek6) or left(kd_rek6,4) in ($kode_1)) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_sub_kegiatan))
realisasi",
                                    [$jns_ang],
                                );
                                foreach ($nilai as $res) {
                                    $anggarann1 = $res->anggaran;
                                    $realisasii1 = $res->realisasi;
                                    if ($anggarann1 == 0) {
                                        $persenn1 = 0;
                                    } else {
                                        $persenn1 = ($realisasii1 / $anggarann1) * 100;
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="text-align: center"><b></b></td>
                                <td><b>{{ $uraian }}</b></td>
                                <td class="angka"><b>{{ rupiah($anggarann1) }}</b></td>
                                <td class="angka"><b>{{ rupiah($realisasii1) }}</b></td>
                                <td style="text-align: center"><b>{{ rupiah($persenn1) }}</b></td>
                            </tr>
                        @endif
                    @break

                    @case(2)
                        @if ($nor == 11)
                            @php
                                $nilai = DB::select(
                                    "SELECT (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in ($kd_skpd) group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in ($kd_skpd) group by kd_sub_kegiatan) ) anggaran,
                                (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)='2023' and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_sub_kegiatan))
realisasi",
                                    [$jns_ang],
                                );
                                foreach ($nilai as $res) {
                                    $anggaran = $res->anggaran;
                                    $realisasi = $res->realisasi;
                                    if ($anggaran == 0) {
                                        $persen = 0;
                                    } else {
                                        $persen = ($realisasi / $anggaran) * 100;
                                    }
                                }
                            @endphp

                            <tr>
                                <td style="text-align: center"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}</td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td style="text-align: center"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}</td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @endif
                    @break

                    @case(3)
                        @if ($nor == 12 || $nor == 13 || $nor == 14)
                            @php
                                $nilai = DB::select(
                                    "SELECT (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd=$kd_skpd group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd=$kd_skpd group by kd_sub_kegiatan) ) anggaran,
                                (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)='2023' and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd=$kd_skpd group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd=$kd_skpd group by kd_sub_kegiatan))
realisasi",
                                    [$jns_ang],
                                );
                                foreach ($nilai as $res) {
                                    $anggaran = $res->anggaran;
                                    $realisasi = $res->realisasi;
                                    if ($anggaran == 0) {
                                        $persen = 0;
                                    } else {
                                        $persen = ($realisasi / $anggaran) * 100;
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="text-align: center"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                                </td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>

                            @php
                                $nilair = DB::select(
                                    "SELECT concat(kd_sub_kegiatan,' - ',(select nm_sub_kegiatan from ms_sub_kegiatan where a.kd_sub_kegiatan=kd_sub_kegiatan))uraian, isnull(sum(anggaran),0)anggaran, isnull(sum(realisasi),0)realisasi from(
                                select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) anggaran,0 realisasi from trdrka where jns_ang=? and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_sub_kegiatan)
                                group by kd_skpd,kd_sub_kegiatan,kd_rek6
                                union all
                                select kd_skpd,kd_sub_kegiatan,kd_rek6,0 anggaran,sum(debet-kredit) realisasi from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)='2023' and kd_skpd in($kd_skpd) and kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_rek6) and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy where kd_skpd in($kd_skpd) group by kd_sub_kegiatan)
                                group by kd_skpd,kd_sub_kegiatan,kd_rek6) a
                                group by kd_sub_kegiatan",
                                    [$jns_ang],
                                );
                            @endphp

                            @foreach ($nilair as $res)
                                @php
                                    $uraiann = $res->uraian;
                                    $anggaran = $res->anggaran;
                                    $realisasi = $res->realisasi;
                                    if ($anggaran == 0) {
                                        $persen = 0;
                                    } else {
                                        $persen = ($realisasi / $anggaran) * 100;
                                    }
                                @endphp
                                <tr>
                                    <td style="text-align: center"></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraiann }}
                                    </td>
                                    <td class="angka">{{ rupiah($anggaran) }}</td>
                                    <td class="angka">{{ rupiah($realisasi) }}</td>
                                    <td style="text-align: center">{{ rupiah($persen) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="text-align: center"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                                </td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @endif
                    @break

                    @default
                @endswitch
            @endforeach
            @php
                $no2 = DB::select(
                    "SELECT
                        (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd in(select kd_skpd from map_kes_mandatory_oyoy group by kd_skpd) and
                        (kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy  group by kd_rek6) or left(kd_rek6,4)in('5101','5102','5105','5106','5201','5202','5203','5204','5205','5206','5402'))
                        and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy group by kd_sub_kegiatan))
anggaran,
                        (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where year(tgl_voucher)='2023' and kd_skpd in(select kd_skpd from map_kes_mandatory_oyoy group by kd_skpd) and
                        (kd_rek6 in (select kd_rek6 from map_kes_mandatory_oyoy  group by kd_rek6) or left(kd_rek6,4)in('5101','5102','5105','5106','5201','5202','5203','5204','5205','5206','5402'))
                        and kd_sub_kegiatan in (select kd_sub_kegiatan from map_kes_mandatory_oyoy group by kd_sub_kegiatan) ) realisasi",
                    [$jns_ang],
                );

                foreach ($no2 as $ris) {
                    $anggaran2 = $ris->anggaran;
                    $realisasi2 = $ris->realisasi;
                    if ($anggaran2 == 0) {
                        $persen2 = 0;
                    } else {
                        $persen2 = ($realisasi2 / $anggaran2) * 100;
                    }
                }

                $no3 = DB::select(
                    "SELECT sum(anggaran)anggaran,sum(realisasi)realisasi from
                    (SELECT
                    sum(nilai) as anggaran,0 realisasi
                     FROM trdrka where LEFT(kd_rek6,1) ='5'
                     and jns_ang=?
                     union all
                    SELECT 0 anggaran, SUM(debet-kredit) as realisasi FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
                    WHERE MONTH(tgl_voucher)<=12 and YEAR(tgl_voucher)=2023 and LEFT(kd_rek6,1) ='5')a",
                    [$jns_ang],
                );

                foreach ($no3 as $riw) {
                    $anggaran3 = $riw->anggaran;
                    $realisasi3 = $riw->realisasi;
                    if ($anggaran3 == 0) {
                        $persen3 = 0;
                    } else {
                        $persen3 = ($realisasi3 / $anggaran3) * 100;
                    }
                }

                $no4 = DB::select(
                    "SELECT sum(anggaran)anggaran,sum(realisasi)realisasi from
                    (SELECT
                    sum(nilai) as anggaran,0 realisasi
                     FROM trdrka where LEFT(kd_rek6,8) ='51010101'
                     and jns_ang=?
                     union all
                    SELECT 0 anggaran, SUM(debet-kredit) as realisasi FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
                    WHERE MONTH(tgl_voucher)<=12 and YEAR(tgl_voucher)=2023 and LEFT(kd_rek6,8) ='51010101')a",
                    [$jns_ang],
                );

                foreach ($no4 as $rew) {
                    $anggaran4 = $rew->anggaran;
                    $realisasi4 = $rew->realisasi;
                    if ($anggaran4 == 0) {
                        $persen4 = 0;
                    } else {
                        $persen4 = ($realisasi4 / $anggaran4) * 100;
                    }
                }
            @endphp
            <tr>
                <td style="text-align: center"><b>2</b></td>
                <td><b>Anggaran Kesehatan (a+b)</b></td>
                <td class="angka"><b>{{ rupiah($anggaran2) }}</b></td>
                <td class="angka"><b>{{ rupiah($realisasi2) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen2) }}</b></td>
            </tr>

            <tr>
                <td style="text-align: center"><b>3</b></td>
                <td><b>Total Belanja Daerah</b></td>
                <td class="angka"><b>{{ rupiah($anggaran3) }}</b></td>
                <td class="angka"><b>{{ rupiah($realisasi3) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen3) }}</b></td>
            </tr>

            <tr>
                <td style="text-align: center"><b>4</b></td>
                <td><b>Gaji ASN</b></td>
                <td class="angka"><b>{{ rupiah($anggaran4) }}</b></td>
                <td class="angka"><b>{{ rupiah($realisasi4) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen4) }}</b></td>
            </tr>

            @php
                $anggaran5 = $anggaran3 - $anggaran4;
                $realisasi5 = $realisasi3 - $realisasi4;
                $persen5 = ($realisasi5 / $anggaran5) * 100;
            @endphp

            <tr>
                <td style="text-align: center"><b>5</b></td>
                <td><b>Total Belanja Daerah di luar Gaji ASN (3-4)</b></td>
                <td class="angka"><b>{{ rupiah($anggaran5) }}</b></td>
                <td class="angka"><b>{{ rupiah($realisasi5) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen5) }}</b></td>
            </tr>

            @php
                $rasio_ang = ($anggaran2 / $anggaran5) * 100;
                $rasio_real = ($realisasi2 / $realisasi5) * 100;
                $rasio_persen = ($rasio_real / $rasio_ang) * 100;
            @endphp

            <tr>
                <td style="text-align: center"><b></b></td>
                <td><b>Rasio Anggaran Kesehatan (2:5) x 100%</b></td>
                <td class="angka"><b>{{ rupiah($rasio_ang) }}</b></td>
                <td class="angka"><b>{{ rupiah($rasio_real) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($rasio_persen) }}</b></td>
            </tr>
        </tbody>
    </TABLE>

</body>

</html>
