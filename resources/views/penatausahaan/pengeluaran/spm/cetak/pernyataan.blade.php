<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pengantar</title>
    <style>
        h5 {
            font-weight: normal
        }
    </style>
</head>

<body>
    {{-- <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></h5>
        <h5 style="margin: 2px 0px"><strong>{{ $nama_skpd->nm_skpd }}</strong></h5>
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
                    {{ $nama_skpd->nm_skpd }}
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
        <table style="width:100%">
            <tr>
                <td><strong><u>SURAT PERNYATAAN
                            {{ Str::upper(nama_beban($beban, $data_beban->jenis_beban)) }}</u></strong></td>
            </tr>
            <tr>
                <td><strong>Nomor: {{ $no_spm }}</strong></td>
            </tr>
        </table>
        {{-- <h5 style="margin: 2px 0px"><strong><u>SURAT PERNYATAAN
                    {{ Str::upper(nama_beban($beban, $data_beban->jenis_beban)) }}</u></strong></h5> --}}
        {{-- <h5 style="margin: 2px 0px"><strong>Nomor: {{ $no_spm }}</strong></h5> --}}
    </div>
    <div>
        <table style="width: 100%;text-align:justify">
            <tr>
                <td>Sehubungan dengan Surat Perintah Membayar
                    {{ nama_spm($beban, $data_beban->jenis_beban) }}
                    Nomor {{ $no_spm }} Tanggal {{ tanggal($data_beban->tgl_spm) }} yang kami ajukan sebesar
                    {{ rupiah($data_beban->nilai) }} ({{ terbilang($data_beban->nilai) }})</td>
            </tr>
            <tr>
                <td>Untuk Keperluan OPD : {{ $data_beban->nm_skpd }} Tahun Anggaran
                    {{ $tahun_anggaran }}</td>
            </tr>
            <tr>
                <td>Dengan ini menyatakan sebenarnya bahwa :</td>
            </tr>
        </table>
        {{-- <h5 style="margin: 2px 0px;text-align:justify">Sehubungan dengan Surat Perintah Membayar
            {{ nama_spm($beban, $data_beban->jenis_beban) }}
            Nomor {{ $no_spm }} Tanggal {{ tanggal($data_beban->tgl_spm) }} yang kami ajukan sebesar
            {{ rupiah($data_beban->nilai) }} ({{ terbilang($data_beban->nilai) }})
        </h5> --}}
        {{-- <h5 style="margin: 2px 0px;text-align:justify">Untuk Keperluan OPD : {{ $data_beban->nm_skpd }} Tahun Anggaran
            {{ $tahun_anggaran }}</h5> --}}
        {{-- <h5 style="margin: 2px 0px;text-align:justify">Dengan ini menyatakan sebenarnya bahwa :</h5> --}}
    </div>
    <div>
        <table style="width: 100%">
            <tr>
                <td style="padding-left:10px;width:5%">1.</td>
                <td style="text-align:justify">
                    Jumlah Pembayaran {{ nama_spm1($beban, $data_beban->jenis_beban) }}
                    tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami
                    laksanan sesuai DPA-OPD
                </td>
            </tr>
            <tr>
                <td style="padding-left:10px">2.</td>
                <td style="text-align: justify">
                    Jumlah Pembayaran {{ nama_spm1($beban, $data_beban->jenis_beban) }}
                    tersebut tidak akan
                    dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                    harus dilaksanakan dengan Pembayaran Langsung
                    {{ nama_beban1($beban, $data_beban->jenis_beban) }}
                </td>
            </tr>
            <tr>
                <td colspan="2">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan
                    SPM-{{ nama_beban2($beban, $data_beban->jenis_beban) }}
                    OPD kami</td>
            </tr>
        </table>
        {{-- <h5 style="margin: 2px 0px">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan
            SPM-{{ nama_beban2($beban, $data_beban->jenis_beban) }}
            OPD kami
        </h5> --}}
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
                    {{ $pa_kpa->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px"><strong><u>{{ $pa_kpa->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">{{ $pa_kpa->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">NIP. {{ $pa_kpa->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
