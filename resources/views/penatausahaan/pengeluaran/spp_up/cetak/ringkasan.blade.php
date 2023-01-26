<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ringkasan</title>
    <style>
        .unborder {
            font-weight: normal;
            text-align: justify
        }

        #rincian>tbody>tr>:first-child {
            padding-left: 20px;
            width: 200px;
        }

        #rincian>tbody>tr>:last-child {
            padding-left: 20px;
        }

        #rincian>tbody>tr>:nth-child(2) {
            padding-left: 100px;
        }

        #rincian>tbody>tr>td {
            font-size: 12px
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
            <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td></tr>
            <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>SKPD {{ $skpd->nm_skpd }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>&nbsp;</strong></td></tr>
    </table>
    <hr>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <b>SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN</b>
            </td>
        </tr>
        <tr>
            <td align="center">
                <b>(SPP-UP)</b>
            </td>
        </tr>
        <tr>
            <td align="center">
                <b>Nomor : {{ $no_spp }}</b>
            </td>
        </tr>
        <tr>
            <td align="center">
                <b>RINGKASAN</b>
            </td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                Berdasarkan Keputusan Gubernur
                    {{ $pergub->no_pergub }} Tanggal {{ tanggal($pergub->tgl_pergub) }} Tentang {{ $pergub->tentang }}  untuk OPD {{ Str::upper($skpd->nm_skpd) }}
                    sejumlah Rp {{ rupiah($spp->nilai) }}<br>
                Terbilang : {{ terbilang($spp->nilai) }}
            </td>
        </tr>
        
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:600px">
                    {{ daerah($kd_skpd) }},
                    @if ($tanpa == 1)
                        ______________{{ tahun_anggaran() }}
                    @else
                        {{ tanggal($spp->tgl_spp) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:600px">
                    {{ $bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px"><b><u>{{ $bendahara->nama }}</u></b></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">{{ $bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">NIP. {{ $bendahara->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
