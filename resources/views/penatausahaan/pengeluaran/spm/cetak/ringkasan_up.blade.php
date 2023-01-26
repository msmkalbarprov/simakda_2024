<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
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
    <table style="width: 100%;font-family:'Times New Roman', Times, serif;text-align:center;font-size:16px">
        <tr>
            <td><b>SURAT PERINTAH MEMBAYAR UANG PERSEDIAAN</b></td>
        </tr>
        <tr>
            <td><b>(SPM-UP)</b></td>
        </tr>
        <tr>
            <td><b></b></td>
        </tr>
        <tr>
            <td><b>RINGKASAN</b></td>
        </tr>
    </table>

    <table style="width: 100%;font-family:'Times New Roman', Times, serif;text-align:justify;font-size:16px">
        <tr>
            <td>Berdasarkan Keputusan Gubernur
                {{ $pergub->no_pergub }} Tanggal {{ tanggal($pergub->tgl_pergub) }} Tentang {{ $pergub->tentang }} untuk
                OPD
                {{ $data_beban->nm_skpd }} sejumlah Rp
                {{ rupiah($data_beban->nilai) }}</td>
        </tr>
        <tr>
            <td>Terbilang:
                <i>({{ terbilang($data_beban->nilai) }})</i>
            </td>
        </tr>
    </table>

    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:400px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ tanggal($data_beban->tgl_spm) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:400px">
                    {{ $pptk->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:400px"><strong><u>{{ $pptk->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:400px">{{ $pptk->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:400px">NIP. {{ $pptk->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
