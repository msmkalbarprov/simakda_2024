<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pengantar</title>
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
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">{{ title() }}</h5>
        <h5 style="margin: 2px 0px">SKPD {{ Str::upper($skpd->nm_skpd) }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ tahun_anggaran() }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN</h5>
        <h5 style="margin: 2px 0px">(SPP-UP)</h5>
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
        <h5 style="margin: 2px 0px">RINGKASAN</h5>
    </div>
    <div style="text-align: left">
        <h5 style="margin: 2px 0px" class="unborder">Berdasarkan Keputusan Gubernur
            {{ $pergub->no_pergub }} Tanggal {{ tanggal($pergub->tgl_pergub) }} Tentang {{ $pergub->tentang }}  untuk OPD {{ Str::upper($skpd->nm_skpd) }}
            sejumlah Rp {{ rupiah($spp->nilai) }}</h5>
        <h5 style="margin: 2px 0px" class="unborder">Terbilang : {{ terbilang($spp->nilai) }}</h5>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
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
