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
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        <h5 style="margin: 2px 0px">{{ $data->nm_skpd }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - LS {{ strtoupper($lcbeban) }}</h5>
        @else
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - {{ strtoupper($lcbeban) }}</h5>
        @endif
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
    </div>

    <div style="text-align: justify">
        <h5 style="margin: 8px 0px">
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
        <h5 style="margin: 8px 0px">Untuk Keperluan OPD : {{ $data->nm_skpd }} Tahun Anggaran {{ $tahun_anggaran }}
        </h5>
        <h5 style="margin: 8px 0px">Dengan ini menyatakan sebenarnya bahwa :</h5>
    </div>

    <div style="text-align: justify">
        <table>
            <tr>
                <td style="padding-left:40px">1.</td>
                <td>Jumlah Pembayaran Langsung (LS) {{ $lcbeban }} tersebut di atas akan dipergunakan untuk
                    keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
            </tr>
            <tr>
                <td style="padding-left:40px">2.</td>
                <td>Jumlah Pembayaran Langsung (LS) {{ $lcbeban }} tersebut tidak akan dipergunakan untuk
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
    </div>

    <div>
        <h5 style="margin: 8px 0px">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-LS
            {{ $lcbeban }} OPD kami</h5>
    </div>

    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table>
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:950px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:950px">
                    {{ $cari_bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:950px">{{ $cari_bendahara->nama }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:950px">{{ $cari_bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:950px">NIP. {{ $cari_bendahara->nip }}</td>
            </tr>

        </table>
    </div>
</body>

</html>
