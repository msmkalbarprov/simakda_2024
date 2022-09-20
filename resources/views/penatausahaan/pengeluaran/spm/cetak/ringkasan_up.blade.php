<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></h5>
        <h5 style="margin: 2px 0px"><strong>{{ $data_beban->nm_skpd }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>TAHUN ANGGARAN {{ $tahun_anggaran }}</strong></h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px"><strong>SURAT PERINTAH MEMBAYAR UANG PERSEDIAAN</strong></h5>
        <h5 style="margin: 2px 0px"><strong>(SPM-UP)</strong></h5>
        <h5 style="margin: 2px 0px"><strong>Nomor : {{ $no_spm }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>RINGKASAN</strong></h5>
    </div>
    <div>
        <h5 style="margin: 8px 0px;font-weight:normal;text-align:justify">Berdasarkan Keputusan Gubernur
            {{ $nogub }} tentang Uang Persediaan untuk OPD {{ $data_beban->nm_skpd }} sejumlah Rp
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
                    {{ $pptk->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px"><strong><u>{{ $pptk->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">{{ $pptk->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">NIP. {{ $pptk->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
