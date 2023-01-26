<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    {{-- <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></h5>
        <h5 style="margin: 2px 0px"><strong>{{ $data_beban->nm_skpd }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>TAHUN ANGGARAN {{ $tahun_anggaran }}</strong></h5>
        <div style="clear: both"></div>
    </div>
    <hr> --}}
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px">
                <strong>
                    {{ $data_beban->nm_skpd }}
                </strong>
            </td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px"><strong>SURAT PERINTAH MEMBAYAR UANG PERSEDIAAN</strong></h5>
        <h5 style="margin: 2px 0px"><strong>(SPM-UP)</strong></h5>
        <h5 style="margin: 2px 0px"><strong>Nomor : {{ $no_spm }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>RINGKASAN</strong></h5>
    </div>
    <div>
        <h5 style="margin: 8px 0px;font-weight:normal;text-align:justify">Berdasarkan Keputusan Gubernur
            {{ $pergub->no_pergub }} Tanggal {{ tanggal($pergub->tgl_pergub) }} Tentang {{ $pergub->tentang }}  untuk OPD {{ $data_beban->nm_skpd }} sejumlah Rp
            {{ rupiah($data_beban->nilai) }}</h5>
        <h5 style="margin: 8px 0px;font-weight:normal;text-align:justify">Terbilang:
            <i>({{ terbilang($data_beban->nilai) }})</i>
        </h5>
    </div>
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:600px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ tanggal($data_beban->tgl_spm) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:600px">
                    {{ $pa->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px"><strong><u>{{ $pa->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">{{ $pa->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">NIP. {{ $pa->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
