<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOREKSI NOMINAL</title>
    <style>
        .row1 {
            width: 80%;
            padding-left: 30px;
        }

        .row2 {
            border: 1px solid black;
            width: 10%;
        }

        .row3 {
            border: 1px solid black;
            width: 10%
        }

        .row4 {
            width: 80%;
            padding-left: 40px;
        }

        .row5 {
            padding-left: 50px
        }

        .row6 {
            padding-left: 60px
        }

        table,
        th,
        td {
            font-weight: normal;
            font-size: 12px;
            font-family: 'Open Sans', sans-serif;
        }

        h3,
        h4 {
            font-family: 'Open Sans', sans-serif;
        }

        .judul1 {
            padding-left: 10px
        }

        .judul2 {
            padding-left: 30px
        }

        .rincian>tbody>tr>td {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:16px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px">
                <strong>
                    SKPD {{ nama_skpd(Auth::user()->kd_skpd) }}
                </strong>
            </td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td style="font-size: 16px"><b>JURNAL KOREKSI</b></td>
        </tr>
        <tr>
            <td style="font-size: 16px">PERIODE {{ tanggal($periode1) }} s/d {{ tanggal($periode2) }}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;border-collapse:collapse;"
        border="1">
        <thead>
            <tr>
                <th bgcolor="#CCCCCC" rowspan="2"><b>Tanggal</b></th>
                <th bgcolor="#CCCCCC" rowspan="2"><b>Nomor<br>Bukti</b></th>
                <th bgcolor="#CCCCCC" rowspan="2"><b>Kode<br>Sub Kegiatan</b></th>
                <th colspan="6" bgcolor="#CCCCCC" rowspan="2"><b>Kode<br>Rekening</b></th>
                <th bgcolor="#CCCCCC" rowspan="2"><b>Uraian</b></th>
                <th bgcolor="#CCCCCC" rowspan="2"><b>ref</b></th>
                <th bgcolor="#CCCCCC" colspan="2"><b>Jumlah Rp</b></th>
            </tr>
            <tr>
                <th bgcolor="#CCCCCC"><b>Debit</b></th>
                <th bgcolor="#CCCCCC"><b>Kredit</b></th>
            </tr>
            <tr>
                <th bgcolor="#CCCCCC" width="15%">1</th>
                <th bgcolor="#CCCCCC" width="10%">2</th>
                <th bgcolor="#CCCCCC" width="10%">3</th>
                <th colspan="6" bgcolor="#CCCCCC" width="15%">4</th>
                <th bgcolor="#CCCCCC" width="35%">5</th>
                <th bgcolor="#CCCCCC" width="3%"></th>
                <th bgcolor="#CCCCCC" width="10%">6</th>
                <th bgcolor="#CCCCCC" width="10%">7</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cnovoc = '';
                $lcno = 0;
            @endphp
            @foreach ($data as $item)
                @if ($cnovoc == $item->no_bukti)
                    <tr>
                        <td style="border-bottom:none;border-top:none;">&nbsp;</td>
                        <td style="border-bottom:none;border-top:none;">&nbsp;</td>
                        <td style="border-bottom:none;">{{ $item->kd_sub_kegiatan }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 0, 1) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 1, 1) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 2, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 4, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 6, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 8, 4) }}</td>
                        <td style="border-bottom:none;">{{ $item->nm_rek6 }}</td>
                        <td style="border-bottom:none;"></td>
                        @if ($item->nilai < 0)
                            <td style="border-bottom:none;"></td>
                            <td style="border-bottom:none;" align="right">{{ rupiah($item->nilai * -1) }}</td>
                        @else
                            <td style="border-bottom:none;" align="right">{{ rupiah($item->nilai) }}</td>
                            <td style="border-bottom:none;"></td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td style="border-bottom:none" align="center">{{ tanggal($item->tgl_bukti) }}</td>
                        <td style="border-bottom:none" align="center">{{ $item->no_bukti }}</td>
                        <td style="border-bottom:none;">{{ $item->kd_sub_kegiatan }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 0, 1) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 1, 1) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 2, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 4, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 6, 2) }}</td>
                        <td style="border-bottom:none;">{{ substr($item->kd_rek6, 8, 4) }}</td>
                        <td style="border-bottom:none;">{{ $item->nm_rek6 }}</td>
                        <td style="border-bottom:none;"></td>
                        @if ($item->nilai < 0)
                            <td style="border-bottom:none;"></td>
                            <td style="border-bottom:none;" align="right">{{ rupiah($item->nilai * -1) }}</td>
                        @else
                            <td style="border-bottom:none;" align="right">{{ rupiah($item->nilai) }}</td>
                            <td style="border-bottom:none;"></td>
                        @endif
                    </tr>
                @endif
                @php
                    $cnovoc = $item->no_bukti;
                @endphp
            @endforeach
            <tr>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
                <td style="border-top:none"></td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif" class="rincian">
        <tbody>
            <tr>
                <td style="width: 50%;text-align:center">Mengetahui,</td>
                <td style="text-align: center;width:50%">
                    Pontianak, {{ tanggal($tgl_ttd) }}
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;width: 50%">
                    {{ $pa_kpa->jabatan }}
                </td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $ppk->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%;text-align: center">
                    <b><u>{{ $pa_kpa->nama }}</u></b>
                    <br>
                    {{ $pa_kpa->pangkat }}
                    <br>
                    NIP. {{ $pa_kpa->nip }}
                </td>
                <td style="text-align: center">
                    <b><u>{{ $ppk->nama }}</u></b>
                    <br>
                    {{ $ppk->pangkat }}
                    <br>
                    NIP. {{ $ppk->nip }}
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
