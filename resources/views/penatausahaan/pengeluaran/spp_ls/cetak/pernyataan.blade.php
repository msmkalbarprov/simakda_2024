<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .unbold {
            font-weight: normal
        }
    </style>
</head>

<body>
    {{-- <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
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
            <td align="left" style="font-size:14px"><strong>{{ $skpd->nm_skpd }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>&nbsp;</strong></td>
        </tr>
    </table> --}}
    <hr>
    {{-- <div style="text-align: center">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - LS {{ strtoupper($lcbeban) }}</h5>
        @else
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - {{ strtoupper($lcbeban) }}</h5>
        @endif
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
    </div> --}}

    <table style="width:100%;text-align:center;font-weight:bold">
        <tr>
            <td>
                <u>
                    @if ($beban == '4')
                        SURAT PERNYATAAN PENGAJUAN SPP - LS {{ strtoupper($lcbeban) }}
                    @else
                        SURAT PERNYATAAN PENGAJUAN SPP - {{ strtoupper($lcbeban) }}
                    @endif
                </u>
            </td>
        </tr>
        <tr>
            <td>Nomor : {{ $no_spp }}</td>
        </tr>
    </table>

    {{-- <div style="text-align: justify">
        <h5 style="margin: 8px 0px" class="unbold">
            @if ($beban == '4' && $beban == '6')
                Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - LS {{ strtoupper($lcbeban) }})
            @elseif ($beban == '5')
                Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - {{ strtoupper($lcbeban) }})
            @endif
            Nomor
            {{ $no_spp }} Tanggal
            {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }} yang kami ajukan sebesar
            {{ rupiah($data->nilai) }} ({{ ucwords(terbilang($data->nilai)) }})
        </h5>
        <h5 style="margin: 8px 0px" class="unbold">Untuk Keperluan OPD : {{ $data->nm_skpd }} Tahun Anggaran
            {{ $tahun_anggaran }}
        </h5>
        <h5 style="margin: 8px 0px" class="unbold">Dengan ini menyatakan sebenarnya bahwa :</h5>
    </div> --}}

    <table style="width: 100%;padding-top:30px;font-family:Arial, Helvetica, sans-serif">
        <tr>
            <td>
                @if ($beban == '4' || $beban == '6')
                    Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - LS {{ strtoupper($lcbeban) }})
                @elseif ($beban == '5')
                    Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - {{ strtoupper($lcbeban) }})
                @endif
                Nomor
                {{ $no_spp }} Tanggal
                {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }} yang kami ajukan
                sebesar
                {{ rupiah($data->nilai) }} ({{ ucwords(terbilang($data->nilai)) }})
            </td>
        </tr>
        <tr>
            <td style="height:20px"></td>
        </tr>
        <tr>
            <td>
                Untuk Keperluan OPD : {{ $data->nm_skpd }} Tahun Anggaran
                {{ $tahun_anggaran }}
            </td>
        </tr>
        <tr>
            <td style="height:20px"></td>
        </tr>
        <tr>
            <td>
                Dengan ini menyatakan sebenarnya bahwa :
            </td>
        </tr>
        <tr>
            <td style="height:20px"></td>
        </tr>
    </table>

    {{-- <div style="text-align: justify">
        <table class="table" style="width:100%">
            <tr>
                <td style="padding-left:40px">1.</td>
                <td>
                    <h5 class="unbold" style="margin: 2px 0px;text-align:justify">Jumlah Pembayaran Langsung (LS)
                        {{ $lcbeban }}
                        tersebut di atas akan
                        dipergunakan untuk
                        keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</h5>
                </td>
            </tr>
            <tr>
                <td style="padding-left:40px">2.</td>
                <td>
                    <h5 class="unbold" style="margin: 2px 0px;text-align:justify">Jumlah Pembayaran Langsung (LS)
                        {{ $lcbeban }}
                        tersebut tidak akan dipergunakan untuk
                        membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                        harus dilaksanakan dengan Pembayaran Langsung
                        @if ($beban == '4')
                            LS-Gaji
                        @else
                            LS-Barang dan Jasa
                        @endif
                    </h5>
                </td>
            </tr>
        </table>
    </div> --}}

    <table style="width: 100%;font-family:Arial, Helvetica, sans-serif">
        <tr>
            <td style="vertical-align: top;padding-left:40px">
                1.
            </td>
            <td style="vertical-align: top">
                Jumlah Pembayaran Langsung (LS)
                {{ $lcbeban }}
                tersebut di atas akan
                dipergunakan untuk
                keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;padding-left:40px">
                2.
            </td>
            <td style="vertical-align: top">
                Jumlah Pembayaran Langsung (LS)
                {{ $lcbeban }}
                tersebut tidak akan dipergunakan untuk
                membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                harus dilaksanakan dengan Pembayaran Langsung
                @if ($beban == '4')
                    LS-Gaji
                @else
                    LS-Barang dan Jasa
                @endif
            </td>
        </tr>
    </table>

    {{-- <div>
        <h5 style="margin: 8px 0px" class="unbold">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan
            pengajuan SPP-LS
            {{ $lcbeban }} OPD kami</h5>
    </div> --}}

    <table style="width: 100%;font-family:Arial, Helvetica, sans-serif">
        <tr>
            <td>
                Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan
                pengajuan SPP-LS
                {{ $lcbeban }} OPD kami
            </td>
        </tr>
    </table>

    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-family:Arial, Helvetica, sans-serif">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:500px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:500px">
                    {{ $cari_bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px"><b><u>{{ $cari_bendahara->nama }}</u></b></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">{{ $cari_bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">NIP. {{ $cari_bendahara->nip }}</td>
            </tr>

        </table>
    </div>
</body>

</html>
