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
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></h5>
        <h5 style="margin: 2px 0px"><strong>{{ $nama_skpd->nm_skpd }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>TAHUN ANGGARAN {{ $tahun_anggaran }}</strong></h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px"><strong><u>SURAT PERNYATAAN
                    {{ nama_beban($beban, $data_beban->jenis_beban) }}</u></strong></h5>
        <h5 style="margin: 2px 0px"><strong>Nomor: {{ $no_spm }}</strong></h5>
    </div>
    <div>
        <h5 style="margin: 2px 0px;text-align:justify">Sehubungan dengan Surat Perintah Membayar
            {{ nama_spm($beban, $data_beban->jenis_beban) }}
            Nomor {{ $no_spm }} Tanggal {{ tanggal($data_beban->tgl_spm) }} yang kami ajukan sebesar
            {{ rupiah($data_beban->nilai) }} ({{ terbilang($data_beban->nilai) }})
        </h5>
        <h5 style="margin: 2px 0px;text-align:justify">Untuk Keperluan OPD : {{ $data_beban->nm_skpd }} Tahun Anggaran
            {{ $tahun_anggaran }}</h5>
        <h5 style="margin: 2px 0px;text-align:justify">Dengan ini menyatakan sebenarnya bahwa :</h5>
    </div>
    <div>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="padding-left:10px;width: 5%">1.</td>
                    <td style="text-align:justify">
                        <h5 style="margin: 2px 0px">Jumlah Pembayaran {{ nama_spm1($beban, $data_beban->jenis_beban) }}
                            tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami
                            laksanan sesuai DPA-OPD</h5>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:10px">2.</td>
                    <td style="text-align: justify">
                        <h5 style="margin: 2px 0px">Jumlah Pembayaran {{ nama_spm1($beban, $data_beban->jenis_beban) }}
                            tersebut tidak akan
                            dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                            harus dilaksanakan dengan Pembayaran Langsung
                            {{ nama_beban1($beban, $data_beban->jenis_beban) }}</h5>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 style="margin: 2px 0px">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan
            SPM-{{ nama_beban2($beban, $data_beban->jenis_beban) }}
            OPD kami
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