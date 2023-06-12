<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MANDATORY RINCI</title>
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

        th {
            background: #CCCCCC
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
            <td align="center">
                <b>
                    @if ($bidang == 1)
                        DINAS PENDIDIKAN
                    @elseif ($bidang == 2)
                        DINAS KESEHATAN
                    @elseif($bidang == 3)
                        INFRASTRUKTUR
                    @endif
                </b>
            </td>
        </TR>
    </TABLE>


    <TABLE style="border-collapse:collapse;font-size:14px;font-family:'Open Sans', Helvetica,Arial,sans-serif"
        border="1" width="100%" id="rincian">
        <THEAD>
            <tr>
                <th>Kode SKPD</th>
                <th>Nama SKPD</th>
                <th>Kode Sub Kegiatan</th>
                <th>Nama Sub Kegiatan</th>
                <th>Kode Rekening</th>
                <th>Nama Rekening</th>
                <th>Anggaran</th>
                <th>Realisasi</th>
            </tr>
        </THEAD>
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @php
                $no = 0;
                $tang = 0;
                $treal = 0;
            @endphp
            @foreach ($data as $item)
                @php
                    $kd_skpd = $item->kd_skpd;
                    $nm_skpd = $item->nm_skpd;
                    $kd_sub_kegiatan = $item->kd_sub_kegiatan;
                    $nm_sub_kegiatan = $item->nm_sub_kegiatan;
                    $kd_rek = $item->kd_rek6;
                    $nm_rek = $item->nm_rek6;
                    $anggaran = $item->anggaran;
                    $realisasi = $item->realisasi;

                    if ($anggaran < 0) {
                        $mina1 = '(';
                        $anggaranres = $anggaran * -1;
                        $mina2 = ')';
                    } else {
                        $mina1 = '';
                        $anggaranres = $anggaran;
                        $mina2 = '';
                    }

                    if ($realisasi < 0) {
                        $minr1 = '(';
                        $realisasires = $realisasi * -1;
                        $minr2 = ')';
                    } else {
                        $minr1 = '';
                        $realisasires = $realisasi;
                        $minr2 = '';
                    }

                    $anggaranres = rupiah($anggaranres);
                    $realisasires = rupiah($realisasires);
                @endphp

                <tr>
                    <td>{{ $kd_skpd }}</td>
                    <td>{{ $nm_skpd }}</td>
                    <td>{{ $kd_sub_kegiatan }}</td>
                    <td>{{ $nm_sub_kegiatan }}</td>
                    <td>{{ $kd_rek }}</td>
                    <td>{{ $nm_rek }}</td>
                    <td class="angka">{{ $mina1 }} {{ $anggaranres }} {{ $mina2 }}</td>
                    <td class="angka">{{ $minr1 }} {{ $realisasires }} {{ $minr2 }}</td>
                </tr>

                @php
                    $tang = $tang + $anggaran;
                    $treal = $treal + $realisasi;
                    if ($tang < 0) {
                        $mina5 = '(';
                        $tangres = $tang * -1;
                        $mina6 = ')';
                    } else {
                        $mina5 = '';
                        $tangres = $tang;
                        $mina6 = '';
                    }

                    if ($treal < 0) {
                        $min7 = '(';
                        $trealres = $treal * -1;
                        $min8 = ')';
                    } else {
                        $min7 = '';
                        $trealres = $treal;
                        $min8 = '';
                    }

                    $tangres = rupiah($tangres);
                    $trealres = rupiah($trealres);
                @endphp
            @endforeach
            <TR>
                <td colspan="6">TOTAL</td>
                <td class="angka">{{ $mina5 }}{{ $tangres }}{{ $mina6 }}</td>
                <td class="angka">{{ $min7 }}{{ $trealres }}{{ $min8 }}</td>
            </TR>
        </tbody>
    </TABLE>

</body>

</html>
