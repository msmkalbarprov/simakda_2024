<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pernyataan</title>
    <style>
       
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
    <div style="text-align: center">
        <table style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px" id="judul">
            <tr>
                <td><b>SURAT PERNYATAAN PENGAJUAN SPP - UP</b></td>
            </tr>
            <tr>
                <td><b>Nomor : {{ $no_spp }}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <table style="width: 100%;margin-top:40px;border-collapse:collapse;font-family: Open Sans; font-size:12px" id="rincian">
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
    <div style="padding-top:20px;">
        <table class="table" style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px" id="ttd">
            <tr>
                <td width='50%'></td>
                <td width='50%' style="margin: 2px 0px;text-align: center">
                    {{ daerah($kd_skpd) }},
                    @if ($tanpa == 1)
                        ______________{{ tahun_anggaran() }}
                    @else
                        {{ tanggal($spp->tgl_spp) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width='50%'></td>
                <td width='50%' style="padding-bottom: 50px;text-align: center">
                    {{ $bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td width='50%'></td>
                <td width='50%' style="text-align: center"><b><u>{{ $bendahara->nama }}</u></b></td>
            </tr>
            <tr>
                <td width='50%'></td>
                <td width='50%' style="text-align: center">{{ $bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td width='50%'></td>
                <td width='50%' style="text-align: center">NIP. {{ $bendahara->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
