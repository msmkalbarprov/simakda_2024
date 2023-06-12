<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MANDATORY INFRASTRUKTUR</title>
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
            <td align="center"><strong>REKAPITULASI REALISASI ANGGARAN MANDATORY SPENDING-BIDANG INFRASTRUKTUR PELAYANAN
                    PUBLIK TA
                    {{ tahun_anggaran() }}</strong></td>
        </TR>
        <tr>
            <td style="height: 10px"></td>
        </tr>
        <tr>
            <td><b>A. PERHITUNGAN BELANJA BAGI HASI DAN/ATAU TRANSFER KEPADA DAERAH/DESA</b></td>
        </tr>
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
            @foreach ($data as $row)
                @php
                    $nor = $row->nor;
                    $uraian = $row->uraian;
                    $bold = $row->bold;
                    $kode_1 = $row->kode_1;
                    $kode_2 = $row->kode_2;
                    $kode_3 = $row->kode_3;
                    $kode_4 = $row->kode_4;

                    $nilai = DB::select(
                        "SELECT (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=?  and (left(kd_rek6,4) in ($kode_1) or left(kd_rek6,6) in ($kode_2) or left(kd_rek6,8) in ($kode_3) or left(kd_rek6,12) in ($kode_4))
                    ) anggaran,
                    (select isnull(sum(kredit-debet),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='2023'
                        and (left(kd_rek6,4) in ($kode_1) or left(kd_rek6,6) in ($kode_2) or left(kd_rek6,8) in ($kode_3) or left(kd_rek6,12) in ($kode_4)))
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
                        @elseif ($nor == 6)
                            <tr>
                                <td style="text-align: center"><b>2</b></td>
                                <td><b>{{ $uraian }}</b></td>
                                <td class="angka"><b>{{ rupiah($anggaran) }}</b></td>
                                <td class="angka"><b>{{ rupiah($realisasi) }}</b></td>
                                <td style="text-align: center"><b>{{ rupiah($persen) }}</b></td>
                            </tr>
                        @endif
                    @break

                    @case(2)
                        <tr>
                            <td style="text-align: center"></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}</td>
                            <td class="angka">{{ rupiah($anggaran) }}</td>
                            <td class="angka">{{ rupiah($realisasi) }}</td>
                            <td style="text-align: center">{{ rupiah($persen) }}</td>
                        </tr>
                    @break

                    @case(3)
                        <tr>
                            <td style="text-align: center"></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                            </td>
                            <td class="angka">{{ rupiah($anggaran) }}</td>
                            <td class="angka">{{ rupiah($realisasi) }}</td>
                            <td style="text-align: center">{{ rupiah($persen) }}</td>
                        </tr>
                    @break

                    @case(4)
                        @if ($nor == 5)
                            @php
                                $anggaran_pene = $anggaran;
                                $realisasi_pene = $realisasi;
                            @endphp
                            <tr>
                                <td style="text-align: center"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                                </td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @else
                            @php
                                $anggaran_peng = $anggaran;
                                $realisasi_peng = $realisasi;
                            @endphp
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
                $ang_3 = $anggaran_pene - $anggaran_peng;
                $real_3 = $realisasi_pene - $realisasi_peng;
                if ($ang_3 == 0) {
                    $persen_3a = 0;
                } else {
                    $persen_3a = ($real_3 / $ang_3) * 100;
                }
            @endphp

            <tr>
                <td style="text-align: center"><b>3</b></td>
                <td><b>{{ $uraian }}</b></td>
                <td class="angka"><b>{{ rupiah($ang_3) }}</b></td>
                <td class="angka"><b>{{ rupiah($real_3) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen_3a) }}</b></td>
            </tr>
        </tbody>
    </TABLE>

    <br>

    <table>
        <tr>
            <td><b>B. PERHITUNGAN BELANJA INFRASTRUKTUR DAERAH</b></td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-size:14px;font-family:'Open Sans', Helvetica,Arial,sans-serif"
        border="1" width="100%">
        <thead>
            <th align="center" width=1%>No.</th>
            <th align="center" width=45%> Komponen Perhitungan</th>
            <th align="center" width=15%>Anggaran (Rp)</th>
            <th align="center" width=15%>Realisasi (Rp)</th>
            <th align="center" width=10%>Persentase (%)</th>
        </thead>
        <tbody>
            @php
                $map = DB::select('SELECT nor,uraian,bold,kode_1,kode_2,kode_3,kode_4 from map_inB_mandatory_rekap_oyoy order by nor');
            @endphp

            @foreach ($map as $row)
                @php
                    $nor = $row->nor;
                    $uraian = $row->uraian;
                    $bold = $row->bold;
                    $kode_1 = $row->kode_1;
                    $kode_2 = $row->kode_2;
                    $kode_3 = $row->kode_3;
                    $kode_4 = $row->kode_4;

                    $nilai = DB::select(
                        "SELECT sum(nilai)anggaran,sum(realisasi)realisasi
                        from
                        (SELECT a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6,
                        sum(nilai) as nilai
                         FROM trdrka a  inner join map_in_mandatory_oyoy b on left(a.kd_skpd,22)=left(b.kd_skpd,22) and left(a.kd_sub_kegiatan,15)=left(b.kd_sub_kegiatan,15) and left(a.kd_rek6,12)=left(b.kd_rek6,12)
                         where jns_ang=? and (left(b.kd_rek6,4)in($kode_1) or left(b.kd_rek6,6)in($kode_2) or left(b.kd_rek6,8)in($kode_3) or left(b.kd_rek6,12)in($kode_4))
                         group by a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6)
a
                         left join
                         (select a.kd_unit kd_skpd,a.kd_sub_kegiatan,a.kd_rek6,sum(debet-kredit)realisasi from trdju_pkd a inner join trhju_pkd b
                         on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                         where YEAR(tgl_voucher)='2023' and month(tgl_voucher)<=12
                         group by a.kd_unit,a.kd_sub_kegiatan,a.kd_rek6)b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6",
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
                                <td>{{ $uraian }}</td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @elseif ($nor == 9)
                            <tr>
                                <td style="text-align: center"><b>2</b></td>
                                <td>{{ $uraian }}</td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @elseif ($nor == 12)
                            @php
                                $anggaranB = $anggaran;
                                $realisasiB = $realisasi;
                            @endphp
                            <tr>
                                <td style="text-align: center"><b>3</b></td>
                                <td><b>{{ $uraian }}</b></td>
                                <td class="angka"><b>{{ rupiah($anggaran) }}</b></td>
                                <td class="angka"><b>{{ rupiah($realisasi) }}</b></td>
                                <td style="text-align: center"><b>{{ rupiah($persen) }}</b></td>
                            </tr>
                        @else
                            <tr>
                                <td style="text-align: center"></td>
                                <td>{{ $uraian }}</td>
                                <td class="angka">{{ rupiah($anggaran) }}</td>
                                <td class="angka">{{ rupiah($realisasi) }}</td>
                                <td style="text-align: center">{{ rupiah($persen) }}</td>
                            </tr>
                        @endif
                    @break

                    @case(2)
                        <tr>
                            <td style="text-align: center"></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}</td>
                            <td class="angka">{{ rupiah($anggaran) }}</td>
                            <td class="angka">{{ rupiah($realisasi) }}</td>
                            <td style="text-align: center">{{ rupiah($persen) }}</td>
                        </tr>
                    @break

                    @case(3)
                        <tr>
                            <td style="text-align: center"></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                            </td>
                            <td class="angka">{{ rupiah($anggaran) }}</td>
                            <td class="angka">{{ rupiah($realisasi) }}</td>
                            <td style="text-align: center">{{ rupiah($persen) }}</td>
                        </tr>
                    @break

                    @case(4)
                        <tr>
                            <td style="text-align: center"></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $uraian }}
                            </td>
                            <td class="angka">{{ rupiah($anggaran) }}</td>
                            <td class="angka">{{ rupiah($realisasi) }}</td>
                            <td style="text-align: center">{{ rupiah($persen) }}</td>
                        </tr>
                    @break

                    @default
                @endswitch
            @endforeach

            @php
                if ($ang_3 == 0) {
                    $anggaran4 = 0;
                } else {
                    $anggaran4 = ($anggaranB / $ang_3) * 100;
                }
                // $realisasi4 = $realisasiB/$realisasiA *100;
                if ($real_3 == 0) {
                    $realisasi4 = 0;
                } else {
                    $realisasi4 = ($realisasiB / $real_3) * 100;
                }
                // $persen4 = $realisasi4/$anggaran4 *100;
                if ($anggaran4 == 0) {
                    $persen4 = 0;
                } else {
                    $persen4 = ($realisasi4 / $anggaran4) * 100;
                }
            @endphp

            <tr>
                <td align="center"><b>4</b></td>
                <td align="left"><b>Persentase Belanja Infrastruktur terhadap Transfer ke Daerah yang
                        Penggunaannya Bersifat Umum dalam Rancangan Anggaran Pendapatan Belanja Daerah Tahun Anggaran
                        2022</b></td>
                <td class="angka"><b>{{ rupiah($anggaran4) }}</b></td>
                <td class="angka"><b>{{ rupiah($realisasi4) }}</b></td>
                <td style="text-align: center"><b>{{ rupiah($persen4) }}</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>
