<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sub Rincian Objek 77</title>
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

        table,
        tr,
        td {
            font-family: 'Open Sans', Helvetica, Arial, sans-serif;
            font-size: '18px'
        }
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <br><br><br>
    <div style="page-break-after:always;">
        <table style="width: 100%;text-align:center">
            <tr>
                <td>
                    <b>CETAK BUKU RINCIAN OBJEK</b> <br>
                    <b>KEGIATAN {{ strtoupper($nm_subkegiatan) }}</b> <br>
                    <b>{{ strtoupper(tanggal($tanggal1)) }} s/d {{ strtoupper(tanggal($tanggal2)) }}</b>
                </td>
            </tr>
        </table>
    </div>

    @foreach ($rekening as $item)
        <div style="page-break-after:always;">
            <table style="width: 100%">
                <tr>
                    <td style="text-align: center">
                        <b>PEMERINTAH {{ $header->nm_pemda }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <b>BUKU PEMBANTU RINCIAN OBJEK</b>
                    </td>
                </tr>
                <tr>
                    <td style="height: 30px"></td>
                </tr>
            </table>

            <table style="width:100%">
                <tr>
                    <td>OPD</td>
                    <td>: {{ $skpd->kd_skpd }} {{ $skpd->nm_skpd }}</td>
                </tr>
                <tr>
                    <td>Sub Kegiatan</td>
                    <td>: {{ $kd_subkegiatan }} {{ $nm_subkegiatan }}</td>
                </tr>
                <tr>
                    <td>Rekening</td>
                    <td>: {{ $item->kd_rek6 }} {{ nama_rekening($item->kd_rek6) }}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>: {{ tanggal($tanggal1) }} s/d {{ tanggal($tanggal2) }}</td>
                </tr>
            </table>
            <br><br>

            <table style="width: 100%" border="1">
                <tr>
                    <td rowspan="2" style="text-align: center" colspan="2"><b>Nomor dan Tanggal BKU</b></td>
                    <td colspan="4" style="text-align: center"><b>Pengeluaran (Rp)</b></td>
                </tr>
                <tr>
                    <td style="text-align: center"><b>LS</b></td>
                    <td style="text-align: center"><b>UP/GU</b></td>
                    <td style="text-align: center"><b>TU</b></td>
                    <td style="text-align: center"><b>JUMLAH</b></td>
                </tr>


                @php
                    $data = DB::select(
                        "SELECT ISNULL(a.no_bukti,'') as no_bukti
												,b.tgl_bukti
												,ISNULL(a.no_sp2d,'') as no_sp2d
												,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
												FROM trdtransout a
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan=?
												and a.kd_rek6=?
												AND b.kd_skpd=?
												and b.tgl_bukti>=?
												and b.tgl_bukti<=?
												GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
												ORDER BY b.tgl_bukti,a.no_bukti",
                        [$kd_subkegiatan, $item->kd_rek6, $skpd->kd_skpd, $tanggal1, $tanggal2],
                    );
                @endphp

                @php
                    $jumls = 0;
                    $jumup = 0;
                    $jumgu = 0;
                    $jml = 0;
                @endphp
                @foreach ($data as $detail)
                    @php
                        $cetak1 = empty($detail->no_bukti) || $detail->no_bukti == null ? '&nbsp;' : $detail->no_bukti;
                        $cetak2 = empty($detail->no_sp2d) || $detail->no_sp2d == null ? '&nbsp;' : $detail->no_sp2d;
                        $cetak3 = empty($detail->ls) || $detail->ls == null ? '&nbsp;' : $detail->ls;
                        $cetak4 = empty($detail->up) || $detail->up == null ? 0 : $detail->up;
                        $cetak5 = empty($detail->gu) || $detail->gu == null ? 0 : $detail->gu;
                        $cetak6 = empty($detail->tgl_bukti) || $detail->tgl_bukti == null ? 0 : $detail->tgl_bukti;
                        
                        $jumls = $jumls + $cetak3;
                        $jumup = $jumup + $cetak4;
                        $jumgu = $jumgu + $cetak5;
                        $jml = $jml + $cetak3 + $cetak4 + $cetak5;
                    @endphp
                    <tr>
                        <td style="border-bottom:hidden;border-right:hidden;">
                            <b>{{ $cetak1 }}</b>
                        </td>
                        <td style="border-bottom:hidden;border-left:hidden;" class="angka">
                            {{ tanggal($cetak6) }}
                        </td>
                        <td rowspan="2" class="angka">
                            {{ rupiah($cetak3) }}
                        </td>
                        <td rowspan="2" class="angka">{{ rupiah($cetak4) }}</td>
                        <td rowspan="2" class="angka">{{ rupiah($cetak5) }}</td>
                        <td rowspan="2" class="angka">{{ rupiah($cetak3 + $cetak4 + $cetak5) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">SP2D: {{ $cetak2 }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><i><b>Jumlah</b></i></td>
                    <td class="angka">{{ rupiah($jumls) }}</td>
                    <td class="angka">{{ rupiah($jumup) }}</td>
                    <td class="angka">{{ rupiah($jumgu) }}</td>
                    <td class="angka">{{ rupiah($jml) }}</td>
                </tr>

                @php
                    $data_lalu = DB::select(
                        "SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
												FROM trdtransout a
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan=?
												and a.kd_rek6=?
												AND b.kd_skpd=?
												and b.tgl_bukti<?",
                        [$kd_subkegiatan, $item->kd_rek6, $skpd->kd_skpd, $tanggal1],
                    );
                    
                    foreach ($data_lalu as $value) {
                        $lalu_up = $value->lalu_up;
                        $lalu_gu = $value->lalu_gu;
                        $lalu_ls = $value->lalu_ls;
                    }
                    
                    $jml_lalu = $lalu_up + $lalu_gu + $lalu_ls;
                    $tot = $jumup + $lalu_up;
                    $tot1 = $jumgu + $lalu_gu;
                    $tot2 = $jumls + $lalu_ls;
                    $total = $tot + $tot1 + $tot2;
                @endphp

                <tr>
                    <td colspan="2"><i><b>Jumlah s/d periode lalu </i></b></td>
                    <td class="angka">{{ rupiah($lalu_ls) }}</td>
                    <td class="angka">{{ rupiah($lalu_up) }}</td>
                    <td class="angka">{{ rupiah($lalu_gu) }}</td>
                    <td class="angka">{{ rupiah($jml_lalu) }}</td>
                </tr>
                <tr>
                    <td colspan="2"><i><b>Jumlah s/d periode ini </i></b></td>
                    <td class="angka">{{ rupiah($tot2) }}</td>
                    <td class="angka">{{ rupiah($tot) }}</td>
                    <td class="angka">{{ rupiah($tot1) }}</td>
                    <td class="angka">{{ rupiah($total) }}</td>
                </tr>
            </table>
            <br><br>
            <table style="width: 100%;text-align:center">
                <tr>
                    <td style="width: 50%">Mengetahui,</td>
                    <td>Pontianak, {{ tanggal($tanggal_ttd) }}</td>
                </tr>
                <tr>
                    <td>{{ $cari_pa_kpa->jabatan }}</td>
                    <td>{{ $cari_bendahara->jabatan }}</td>
                </tr>
                <tr>
                    <td style="height: 20px"></td>
                    <td style="height: 20px"></td>
                </tr>
                <tr>
                    <td><u><b>{{ $cari_pa_kpa->nama }}</b></u> <br> {{ $cari_pa_kpa->pangkat }} <br>
                        NIP. {{ $cari_pa_kpa->nip }}</td>
                    <td><u><b>{{ $cari_bendahara->nama }}</b></u> <br> {{ $cari_bendahara->pangkat }} <br>
                        NIP. {{ $cari_bendahara->nip }}</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>

</html>
