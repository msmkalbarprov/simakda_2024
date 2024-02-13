<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Selisih LRA & LO</title>
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

    @if ($periodebulan == 'periode')
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0"
            cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>
            </tr>
            <TR>
                <td align="center"><strong>SELISIH LRA & LO</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PERIODE {{ tgl_format_oyoy($tgl1) }} s/d {{ tgl_format_oyoy($tgl2) }}
                    </strong></td>
            </TR>
            @if ($skpdunit == 'keseluruhan')
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                        <br>

                        <br>
                        <br>

                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                        <br>
                        SKPD : $kd_skpd - $nm_skpd
                        <br>
                        <br>

                    </td>
                </tr>
            @endif
        </TABLE>
    @else
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0"
            cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>
            </tr>
            <TR>
                <td align="center"><strong>SELISIH LRA & LO</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PER {{ $nm_bln }} {{ $thn_ang }} DAN {{ $thn_ang1 }} </strong>
                </td>
            </TR>
            @if ($skpdunit == 'keseluruhan')
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                        <br>

                        <br>
                        <br>

                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                        <br>
                        SKPD : {{ $kd_skpd }} - {{ nama_skpd($kd_skpd) }}
                        <br>
                        <br>

                    </td>
                </tr>
            @endif
        </TABLE>
    @endif
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0"
        cellpadding="4">
        <thead>
            <tr>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Kode Rekening LRA</b></td>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Nama Rekening LRA</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Realisasi LRA</b></td>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Kode Rekening LO</b></td>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Nama Rekening LO</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Realisasi LO</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Selisih</b></td>
                @if ($skpdunit == 'keseluruhan' && $cetak == 1)
                    <td bgcolor="#CCCCCC" width="20%" align="center"><b>Rincian</b></td>
                @else
                @endif
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
                <td style="border-top: none;"></td>
                @if ($skpdunit == 'keseluruhan' && $cetak == 1)
                    <td style="border-top: none;"></td>
                @else
                @endif
            </tr>
        </tfoot>

        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="25%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="25%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;
            </td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;
            </td>
            @if ($skpdunit == 'keseluruhan' && $cetak == 1)
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">
                    &nbsp;</td>
            @else
            @endif

        </tr>
        @php
            $no = 0;
            $tlra = 0;
            $tlo = 0;
        @endphp
        @foreach ($query as $res)
            @php
                $kd_rek6 = $res->kd_rek6;
                $nm_rek6 = $res->nm_rek6;
                $kd_lo = $res->kd_lo;
                $lra = $res->lra;
                $lo = $res->lo;
                $selisih = $lra - $lo;

                if ($lra < 0) {
                    $alra = '(';
                    $lrares = $lra * -1;
                    $blra = ')';
                } else {
                    $alra = '';
                    $lrares = $lra;
                    $blra = '';
                }

                if ($lo < 0) {
                    $alo = '(';
                    $lores = $lo * -1;
                    $blo = ')';
                } else {
                    $alo = '';
                    $lores = $lo;
                    $blo = '';
                }

                if ($selisih < 0) {
                    $as = '(';
                    $selisihres = $selisih * -1;
                    $bs = ')';
                } else {
                    $as = '';
                    $selisihres = $selisih;
                    $bs = '';
                }

                $leng = strlen($kd_rek6);
            @endphp
            @if ($leng == 4)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center"><b>{{ $kd_rek6 }}</b></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        <b>{{ $nm_rek6 }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="15%"align="right">
                        <b>{{ $alra }}{{ rupiah($lrares) }}{{ $blra }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center"><b>{{ $kd_lo }}</b></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        <b>{{ nama_rek3($kd_lo) }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">
                        <b>{{ $alo }}{{ rupiah($lores) }}{{ $blo }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">
                        <b>{{ $as }}{{ rupiah($selisihres) }}{{ $bs }}</b>
                    </td>
                </tr>
                @php
                    $tlra = $tlra + $lra;
                    $tlo = $tlo + $lo;

                @endphp
            @elseif ($leng == 6)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center"><b>{{ $kd_rek6 }}</b></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        <b>{{ $nm_rek6 }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="15%"align="right">
                        <b>{{ $alra }}{{ rupiah($lrares) }}{{ $blra }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center"><b>{{ $kd_lo }}</b></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        <b>{{ nama_rek4($kd_lo)}}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">
                        <b>{{ $alo }}{{ rupiah($lores) }}{{ $blo }}</b>
                    </td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">
                        <b>{{ $as }}{{ rupiah($selisihres) }}{{ $bs }}</b>
                    </td>
                </tr>
            @else
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center">{{ $kd_rek6 }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        {{ $nm_rek6 }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="15%"align="right">{{ $alra }}{{ rupiah($lrares) }}{{ $blra }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"
                        align="center">{{ $kd_lo }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">
                        {{ nama_rekening(substr($kd_lo,0,12)) }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">{{ $alo }}{{ rupiah($lores) }}{{ $blo }}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                        width="20%"align="right">{{ $as }}{{ rupiah($selisihres) }}{{ $bs }}
                    </td>
                    @if ($skpdunit == 'keseluruhan' && $cetak == 1)
                        <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                            width="20%"align="right"><button type="button" href="javascript:void(0);"
                                onclick="rinci('{{ $kd_rek6 }}','{{ $kd_lo }}','{{ $dcetak }}','{{ $dcetak2 }}', '{{ $bulan_asli }}','{{ $periodebulan }}')">Rinci</button>
                        </td>
                    @else
                    @endif
                </tr>
            @endif
        @endforeach

        @php
            if ($tlra < 0) {
                $clra = '(';
                $tlrares = $tlra * -1;
                $dlra = ')';
            } else {
                $clra = '';
                $tlrares = $tlra;
                $dlra = '';
            }
            if ($tlo < 0) {
                $clo = '(';
                $tlores = $tlo * -1;
                $dlo = ')';
            } else {
                $clo = '';
                $tlores = $tlo;
                $dlo = '';
            }

            $tselisih = $tlra - $tlo;
            if ($tselisih < 0) {
                $cselisih = '(';
                $tselisihres = $tselisih * -1;
                $dselisih = ')';
            } else {
                $cselisih = '';
                $tselisihres = $tselisih;
                $dselisih = '';
            }
        @endphp
        <tr>
            <td colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                width="60%">TOTAL</td>
            <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                width="15%"align="right">{{ $clra }}{{ rupiah($tlrares) }}{{ $dlra }}</td>
            <td colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                width="60%"></td>
            <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                width="20%"align="right">{{ $clo }}{{ rupiah($tlores) }}{{ $dlo }}</td>
            <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;"
                width="20%"align="right">{{ $cselisih }}{{ rupiah($tselisihres) }}{{ $dselisih }}
            </td>
        </tr>
    </TABLE>

</body>

</html>
<script type="text/javascript">
    function rinci(kd_rek6, kd_lo, dcetak, dcetak2, bulan_asli, periodebulan) {
        let url = new URL("{{ route('laporan_akuntansi.lralo_rinci') }}");
        let searchParams = url.searchParams;
        searchParams.append("kd_rek6", kd_rek6);
        searchParams.append("kd_lo", kd_lo);
        searchParams.append("dcetak", dcetak);
        searchParams.append("dcetak2", dcetak2);
        searchParams.append("bulan", bulan_asli);
        searchParams.append("periodebulan", periodebulan);
        window.open(url.toString(), "_blank");
    }
</script>
