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

        #rincian>tbody>tr>:first-child {
            padding-left: 20px;
            width: 200px;
        }

        #rincian>tbody>tr>:last-child {
            padding-left: 20px;
        }

        #rincian>tbody>tr>:nth-child(2) {
            padding-left: 100px;
        }

        #rincian>tbody>tr>td {
            font-size: 12px
        }
    </style>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">{{ title() }}</h5>
        <h5 style="margin: 2px 0px">SKPD {{ $spp->nm_skpd }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ tahun_anggaran() }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN</h5>
        <h5 style="margin: 2px 0px">(SPP-UP)</h5>
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
        <h5 style="margin: 2px 0px">SURAT PENGANTAR</h5>
    </div>
    <div style="text-align: left">
        <h5 style="margin: 2px 0px" class="unborder">Kepada Yth:</h5>
        <h5 style="margin: 2px 0px" class="unborder">{{ $peng }}</h5>
        <h5 style="margin: 2px 0px" class="unborder">OPD :</h5>
        <h5 style="margin: 2px 0px" class="unborder">Di Tempat</h5>
    </div>
    <div style="text-align: left">
        <h5 style="margin: 2px 0px" class="unborder">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat
            {{ $pergub->no_pergub }} Tanggal {{$pergub->tgl_pergub}} Tentang {{$pergub->tentang}}, bersama ini kami mengajukan Surat Permintaan
            Pembayaran UP sebagai berikut:</h5>
    </div>
    <div>
        <table id="rincian" style="width:100%">
            {{-- Urusan Pemerintahan --}}
            <tr>
                <td>a. Urusan Pemerintahan</td>
                <td>:</td>
                <td>{{ $spp->kd_bidang_urusan }} - {{ $spp->nm_bidang_urusan }}</td>
            </tr>
            {{-- OPD --}}
            <tr>
                <td>b. OPD</td>
                <td>:</td>
                <td>{{ $spp->kd_skpd }} - {{ $spp->nm_skpd }}</td>
            </tr>
            {{-- Tahun Anggaran --}}
            <tr>
                <td>c. Tahun Anggaran</td>
                <td>:</td>
                <td>{{ tahun_anggaran() }}</td>
            </tr>
            {{-- Dasar Pengeluaran SPD --}}
            <tr>
                <td>d. Dasar Pengeluaran SPD</td>
                <td>:</td>
                <td>{{ $spp->no_spd }}</td>
            </tr>
            {{-- Jumlah Sisa Dana SPD --}}
            <tr>
                <td>e. Jumlah Sisa Dana SPD</td>
                <td>:</td>
                <td>Rp. {{ rupiah($spp->spd - $spp->spp) }}</td>
            </tr>
            <tr>
                <td style="text-align: center"></td>
                <td></td>
                <td style="font-style: italic">({{ ucwords(terbilang($spp->spd - $spp->spp)) }})</td>
            </tr>
            {{-- Untuk Keperluan Bulan --}}
            <tr>
                <td>f. Untuk Keperluan Bulan</td>
                <td>:</td>
                <td>{{ bulan($spp->bulan) }}</td>
            </tr>
            {{-- Jumlah Pembayaran yang Diminta --}}
            <tr>
                <td>g. Jumlah Pembayaran yang Diminta</td>
                <td>:</td>
                <td>Rp. {{ rupiah($spp->nilai) }}</td>
            </tr>
            <tr>
                <td style="text-align: center"></td>
                <td></td>
                <td style="font-style: italic">({{ ucwords(terbilang($spp->nilai)) }})</td>
            </tr>
            {{-- Nama Bendahara Pengeluaran --}}
            <tr>
                <td>h. Nama {{ ucwords($bendahara->jabatan) }}</td>
                <td>:</td>
                <td>{{ $bendahara->nama }}</td>
            </tr>
            {{-- Nama Nomor Rekening Bank dan NPWP --}}
            <tr>
                <td>i. Nama dan Nomor Rekening Bank</td>
                <td>:</td>
                <td>{{ bank($spp->bank) }} / {{ $spp->no_rek }} / {{ $spp->npwp }}</td>
            </tr>
        </table>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
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
