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
        <table style="width: 100%">
            <tr>
                <td><strong>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</strong></td>
            </tr>
        </table>
        {{-- <h5 style="margin: 2px 0px"><strong>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</strong></h5> --}}
    </div>
    <div>
        <table style="100%">
            <tr>
                <td>Yang bertanda tangan di bawah ini:</td>
            </tr>
        </table>
        {{-- <h5 style="margin: 2px 0px">Yang bertanda tangan di bawah ini:</h5> --}}
        <table style="width: 100%;font-family: Open Sans">
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
        <table style="width: 100%;font-family: Open Sans">
            <tbody>
                <tr>
                    <td colspan="2">Menyatakan dengan sesungguhnya bahwa:</td>
                </tr>
                {{-- <h5 style="margin: 2px 0px">Menyatakan dengan sesungguhnya bahwa:</h5> --}}
                <tr>
                    <td style="padding-left:10px;width: 5%">1.</td>
                    <td style="text-align:justify">
                        <p style="margin: 2px 0px">SPM {{ cari_jenis($beban) }} Nomor: {{ $no_spm }} tanggal
                            {{ tanggal($tgl_spm->tgl_spm) }} yang kami ajukan untuk diterbitkan Surat Perintah Pencairan
                            Dana (SP2D), semua dokumen kelengkapannya sudah kami verifikasi dan sudah lengkap dan benar
                            berdasarkan persyaratan dan ketentuan yang berlaku.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:10px">2.</td>
                    <td style="text-align: justify">
                        <p style="margin: 2px 0px">Bahwa semua dokumen persyaratan dan kelengkapan untuk penerbitan SPM
                            tersebut disimpan sesuai dengan ketentuan yang berlaku pada
                            <u><i>{{ $nama_skpd->nm_skpd }}</i></u> sebagai bukti pertanggungjawaban pelaksanaan
                            kegiatan
                            serta keperluan pemeriksaan oleh aparat pengawas fungsional dan kebutuhan lainnya.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Demikian surat pernyataan ini dibuat dengan sebenar-benarnya, apabila dokumen
                        yang
                        dipersyaratkan tersebut terdapat kekeliruan, kekurangan dan tidak sah sesuai ketentuan yang
                        berlaku akan
                        menjadi tanggungjawab kami sepenuhnya selaku {{ $pa_kpa->jabatan }}</td>
                </tr>
            </tbody>
        </table>
        {{-- <h5 style="margin: 2px 0px">Demikian surat pernyataan ini dibuat dengan sebenar-benarnya, apabila dokumen yang
            dipersyaratkan tersebut terdapat kekeliruan, kekurangan dan tidak sah sesuai ketentuan yang berlaku akan
            menjadi tanggungjawab kami sepenuhnya selaku {{ $pa_kpa->jabatan }}</h5> --}}
    </div>

    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-family: Open Sans; font-size:12px">
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
