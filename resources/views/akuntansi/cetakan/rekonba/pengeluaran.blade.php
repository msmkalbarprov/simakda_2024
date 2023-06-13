<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekon BA Pengeluaran</title>
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
            <td colspan="3" align="center" style="font-size:14px"><b>Realisasi Pengeluaran Tahun Anggaran
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
                <td rowspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>URAIAN</b></td>
                <td colspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>REALISASI TRIWULAN
                        {{ $tw }} TA {{ $thn_ang }}</b></td>
                <!--<td rowspan="2" bgcolor="#CCCCCC" width="20%" align="center"><b>SISA LEBIH/(KURANG)</b></td>-->
                <td rowspan="2" bgcolor="#CCCCCC" width="15%" align="center"><b>KETERANGAN</b></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" width="15%" align="center"><b>Akuntansi</b></td>
                <td bgcolor="#CCCCCC" width="15%" align="center"><b>SKPD</b></td>
            </tr>
        </thead>

        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;">&nbsp;</td>
            <!--<td style="vertical-align:top;border-top: none;border-bottom: none;" >&nbsp;</td>-->
            <td style="vertical-align:top;border-top: none;border-bottom: none;">&nbsp;</td>
        </tr>
        @php
            
            $no = 0;
        @endphp
        @foreach ($sql as $rowsql)
            @php
                $nomor = $rowsql->nomor;
                $jns = $rowsql->jns;
                $nama = $rowsql->nama;
                $nilai = $rowsql->nilai;
                
                if ($nilai < 0) {
                    $a = '(&nbsp;';
                    $b = '&nbsp;)';
                    $nilai2 = number_format($nilai * -1, '2', ',', '.');
                } else {
                    $a = '';
                    $b = '';
                    $nilai2 = number_format($nilai, '2', ',', '.');
                }
                
            @endphp



            @if ($jns == 0)
                @if (
                    $nomor == 1 ||
                        $nomor == 2 ||
                        $nomor == 3 ||
                        $nomor == 4 ||
                        $nomor == 5 ||
                        $nomor == 6 ||
                        $nomor == 7 ||
                        $nomor == 8 ||
                        $nomor == 9 ||
                        $nomor == 10 ||
                        $nomor == 11 ||
                        $nomor == 12)
                    <tr>
                        <td align="center"><b>{{ $nomor }}</b></td>
                        <td align="left"><b>{{ $nama }}</b></td>
                        <td align="right"><b>{!! $a !!}{{ $nilai2 }}{!! $b !!}</b>
                        </td>
                        <td align="right"><b></b></td>
                        <!--<td align="right">0</td>-->
                        <td></td>
                    </tr>
                @else
                    <tr>
                        <td align="center"><b>{{ $nomor }}</b></td>
                        <td align="left"><b>${{ nama }}</b></td>
                        <td align="right">{!! $a !!}{{ $nilai2 }}{!! $b !!}</td>
                        <td align="right"></td>
                        <!--<td align="right">0</td>-->
                        <td></td>
                    </tr>
                @endif
            @else
                <tr>
                    <td align="center"></td>
                    <td align="left">{{ $nama }}</td>
                    <td align="right">{!! $a !!}{{ $nilai2 }}{!! $b !!}</td>
                    <td align="right"></td>
                    <!--<td align="right">0</td>-->
                    <td></td>
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
