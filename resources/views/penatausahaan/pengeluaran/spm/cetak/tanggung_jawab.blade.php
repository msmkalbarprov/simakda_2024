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
        <h5 style="margin: 2px 0px"><strong>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</strong></h5>
    </div>
    <div>
        <h5 style="margin: 2px 0px">Yang bertanda tangan di bawah ini:</h5>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $pa_kpa->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $pa_kpa->nip }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $pa_kpa->jabatan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h5 style="margin: 2px 0px">Menyatakan dengan sesungguhnya bahwa:</h5>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="padding-left:10px;width: 5%">1.</td>
                    <td style="text-align:justify">
                        <h5 style="margin: 2px 0px">SPM {{ cari_jenis($beban) }} Nomor: {{ $no_spm }} tanggal
                            {{ tanggal($tgl_spm->tgl_spm) }} yang kami ajukan untuk diterbitkan Surat Perintah Pencairan
                            Dana (SP2D), semua dokumen kelengkapannya sudah kami verifikasi dan sudah lengkap dan benar
                            berdasarkan persyaratan dan ketentuan yang berlaku.</h5>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:10px">2.</td>
                    <td style="text-align: justify">
                        <h5 style="margin: 2px 0px">Bahwa semua dokumen persyaratan dan kelengkapan untuk penerbitan SPM
                            tersebut disimpan sesuai dengan ketentuan yang berlaku pada
                            <u><i>{{ $nama_skpd->nm_skpd }}</i></u> sebagai bukti pertanggungjawaban pelaksanaan
                            kegiatan
                            serta keperluan pemeriksaan oleh aparat pengawas fungsional dan kebutuhan lainnya.
                        </h5>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 style="margin: 2px 0px">Demikian surat pernyataan ini dibuat dengan sebenar-benarnya, apabila dokumen yang
            dipersyaratkan tersebut terdapat kekeliruan, kekurangan dan tidak sah sesuai ketentuan yang berlaku akan
            menjadi tanggungjawab kami sepenuhnya selaku {{ cari_pengguna($kd_skpd) }}</h5>
    </div>

    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
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
