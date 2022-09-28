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
        <h5 style="margin: 2px 0px"><strong>{{ $skpd->nm_skpd }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>TAHUN ANGGARAN {{ $tahun_anggaran }}</strong></h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px"><strong><u>SURAT PENGANTAR</u></strong></h5>
        @if (in_array($beban, ['2', '3', '4', '5', '6']))
            <h5 style="margin: 2px 0px"><strong>Nomor : {{ $no_spm }}</strong></h5>
        @endif
    </div>
    <div>
        <h5 style="margin: 2px 0px">Kepada Yth.</h5>
        <h5 style="margin: 2px 0px">Kuasa Bendahara Umum Daerah Provinsi Kalimantan Barat</h5>
        @if (in_array($beban, ['1', '2', '3', '4']))
            <h5 style="margin: 2px 0px">OPD : </h5>
        @endif
        <h5 style="margin: 2px 0px">Di <strong><u>Pontianak</u></strong></h5>
        <h5 style="margin-top: 30px;text-align:justify">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat
            {{ nogub($status_anggaran->jns_ang, $kd_skpd) }} tentang Penjabaran APBD Tahun {{ $tahun_anggaran }},
            bersama ini kami mengajukan Surat Perintah Membayar
            @if ($beban == '1')
                (SPM-UP)
            @elseif ($beban == '2')
                (SPM-GU)
            @elseif ($beban == '3')
                (SPM-TU)
            @elseif (in_array($beban, ['4', '5', '6']))
                (SPM-LS)
            @endif
            Nomor {{ $no_spm }} tanggal
            @if ($tanpa == 1)
                ______________{{ $tahun_anggaran }}
            @else
                {{ tanggal($data_beban->tgl_spm) }}
            @endif
            untuk diterbitkan SP2D sebagai berikut:
        </h5>
    </div>
    <div>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 5%">1.</td>
                    <td style="width: 40%">Urusan Pemerintahan</td>
                    <td>:</td>
                    <td>{{ $data_beban->kd_bidang_urusan }} - {{ $data_beban->nm_bidang_urusan }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>OPD</td>
                    <td>:</td>
                    <td>{{ $data_beban->kd_skpd }} - {{ $data_beban->nm_skpd }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tahun Anggaran</td>
                    <td>:</td>
                    <td>{{ $tahun_anggaran }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Dasar Pengeluaran SPD Nomor</td>
                    <td>:</td>
                    <td>{{ $data_beban->no_spd }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Jumlah Sisa Dana SPD</td>
                    <td>:</td>
                    <td>Rp. {{ rupiah($data_beban->spd - $data_beban->spp) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td><i>Terbilang ({{ terbilang($data_beban->spd - $data_beban->spp) }})</i></td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Jumlah dana yang diminta untuk dicairkan</td>
                    <td>:</td>
                    <td>Rp. {{ rupiah($data_beban->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td><i>Terbilang ({{ terbilang($data_beban->nilai) }})</i></td>
                </tr>
                <tr>
                    @if (in_array($beban, ['1', '2', '3', '4', '6']))
                        @if ($beban == '6' && $data_beban->jns_beban == '6')
                            <td>7.</td>
                            <td>Nama Pihak Ketiga</td>
                            <td>:</td>
                            <td>{{ $data_beban->nmrekan }}</td>
                        @else
                            <td>7.</td>
                            <td>Nama Bendahara Pengeluaran</td>
                            <td>:</td>
                            <td>{{ $bendahara->nama }}</td>
                        @endif
                    @elseif ($beban == '5')
                        <td>7.</td>
                        <td>Nama Pihak Ketiga</td>
                        <td>:</td>
                        <td>{{ $data_beban->nmrekan }}</td>
                    @endif

                </tr>
                <tr>
                    <td>8.</td>
                    @if (in_array($beban, ['1', '2', '3', '4', '6']))
                        @if ($beban == '6' && $data_beban->jns_beban == '6')
                            <td>Nama dan Nomor Rekening dan NPWP</td>
                        @else
                            <td>Nama dan Nomor Rekening Bank</td>
                        @endif
                    @elseif ($beban == '5')
                        <td>Nama dan Nomor Rekening dan NPWP</td>
                    @endif
                    <td>:</td>
                    @if ($beban == '1')
                        <td>{{ $data_beban->nama_bank }} - {{ $data_beban->no_rek }}</td>
                    @elseif (in_array($beban, ['2', '3', '4', '6']))
                        @if ($beban == '6' && $data_beban->jns_beban == '6')
                            <td>{{ $data_beban->nama_bank_rek }} / {{ $data_beban->no_rek_rek }} /
                                {{ $data_beban->npwp_rek }}</td>
                        @else
                            <td>{{ cari_bank_spm($kd_skpd) }} - {{ $data_beban->no_rek }}</td>
                        @endif
                    @elseif ($beban == '5')
                        <td>{{ $data_beban->nama_bank_rek }} / {{ $data_beban->no_rek_rek }} /
                            {{ $data_beban->npwp_rek }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>

    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            @if (in_array($beban, ['1', '2', '3', '4', '5', '6']))
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
            @endif
        </table>
    </div>
</body>

</html>