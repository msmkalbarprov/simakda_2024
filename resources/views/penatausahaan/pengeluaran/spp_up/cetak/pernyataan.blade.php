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

        #rincian,
        #ttd,
        #cover>tbody>tr>td {
            font-size: 12px;
            text-align: justify;
            padding: 2px 0px;
        }

        #judul>tbody>tr>td {
            font-size: 14px;
        }

        table,
        tr,
        td {
            border-collapse: collapse
        }

        th {
            text-align: center;
            background-color: #CCCCCC
        }
    </style>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <table style="width: 100%" id="cover">
            <tr>
                <td><b>{{ title() }}</b></td>
            </tr>
            <tr>
                <td><b>{{ Str::upper($skpd->nm_skpd) }}</b></td>
            </tr>
            <tr>
                <td><b>TAHUN ANGGARAN {{ tahun_anggaran() }}</b></td>
            </tr>
        </table>
    </div>
    <hr>
    <div style="text-align: center">
        <table style="width: 100%" id="judul">
            <tr>
                <td><b>SURAT PERNYATAAN PENGAJUAN SPP - UP</b></td>
            </tr>
            <tr>
                <td><b>Nomor : {{ $no_spp }}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <table style="width: 100%;margin-top:40px" id="rincian">
            <tr>
                <td colspan="2">Sehubungan dengan Surat Permintaan Pembayaran Uang Persediaan (SPP - UP) Nomor
                    {{ $no_spp }}
                    Tanggal {{ tanggal($spp->tgl_spp) }} yang kami ajukan sebesar {{ rupiah($spp->nilai) }}
                    ({{ terbilang($spp->nilai) }})</td>
            </tr>
            <tr>
                <td colspan="2">Untuk Keperluan OPD : {{ $spp->nm_skpd }} Tahun Anggaran {{ tahun_anggaran() }}</td>
            </tr>
            <tr>
                <td colspan="2">Dengan ini menyatakan sebenarnya bahwa : </td>
            </tr>
            <tr>
                <td style="padding-left: 20px;vertical-align:top">1.</td>
                <td style="vertical-align: top">Jumlah Pembayaran UP tersebut di atas akan dipergunakan untuk keperluan
                    guna membiayai kegiatan yang
                    akan kami laksanan sesuai DPA-OPD</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;vertical-align:top">2.</td>
                <td style="vertical-align: top">Jumlah Pembayaran UP tersebut tidak akan dipergunakan untuk membiayai
                    pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilaksanakan dengan Pembayaran
                    Langsung</td>
            </tr>
            <tr>
                <td colspan="2">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPM-UP
                    OPD kami</td>
            </tr>
        </table>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%" id="ttd">
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
