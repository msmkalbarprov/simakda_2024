<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KELENGKAPAN SP2D</title>
    <style>
        table {
            width: 100%
        }

        table,
        td,
        th {
            border-collapse: collapse;
            font-family: 'Open Sans', Helvetica, Arial, sans-serif;
            vertical-align: top
        }

        .a1 {
            padding-left: 20px;
        }

        .a2 {
            padding-left: 30px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="text-align: center"><b>LEMBAR VERIFIKASI KELENGKAPAN DOKUMEN PENERBITAN DANA (SP2D-UP)</b></td>
        </tr>
    </table>

    <br>
    <br>

    <table>
        <tbody>
            <tr>
                <td>SKPD/SATEKER</td>
                <td>:</td>
                <td>{{ $sp2d->nm_skpd }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>PROVINSI KALIMANTAN BARAT</td>
            </tr>
            <tr>
                <td colspan="3">A. PENERIMAAN
                    @if (in_array($beban, ['1', '4']))
                        SPM
                    @elseif ($beban == '2')
                        SPM
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif (in_array($beban, ['5', '6']))
                        SPM-LS
                    @endif
                </td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">1. Nomor dan Tanggal
                    @if (in_array($beban, ['1', '4']))
                        SPM
                    @elseif ($beban == '2')
                        SPM-GU
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif (in_array($beban, ['5', '6']))
                        SPM-LS
                    @endif
                </td>
                <td>:</td>
                <td>{{ $sp2d->no_spm }} dan {{ tanggal($sp2d->tgl_spm) }}</td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">2. Tanggal Terima
                    @if (in_array($beban, ['1', '4']))
                        SPM
                    @elseif ($beban == '2')
                        SPM-GU
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif (in_array($beban, ['5', '6']))
                        SPM-LS
                    @endif
                </td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top:20px">B. KELENGKAPAN PERSYARATAN</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <th></th>
            <th style="font-weight: normal">Ada</th>
            <th style="font-weight: normal">Tidak</th>
        </thead>
        <tbody>
            @include('penatausahaan.pengeluaran.sp2d.cetak.kelengkapan_persyaratan')
        </tbody>
    </table>

    <br>
    <br>

    <table>
        <tbody>
            <tr>
                <td class="a1" style="width: 40%">3. Tanggal Pengembalian SPM</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">4. Tanggal Terima Kembali SPM</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
        </tbody>
    </table>

    <br><br><br>

    <table class="table" style="width: 100%">
        <tr>
            <td style="text-align: center">Diperiksa dan diteruskan oleh</td>
            <td style="margin: 2px 0px;text-align: center">
                Diteliti dan dikerjakan oleh
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 50px;text-align: center">
                {{ $ttd1->jabatan }}
            </td>
            <td style="padding-bottom: 50px;text-align: center">
                {{ $ttd2->jabatan }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <u><b>{{ $ttd1->nama }}</b></u> <br>
                {{ $ttd1->pangkat }} <br>
                NIP. {{ $ttd1->nip }}
            </td>
            <td style="text-align: center">
                <u><b>{{ $ttd2->nama }}</b></u> <br>
                {{ $ttd2->pangkat }} <br>
                NIP. {{ $ttd2->nip }}
            </td>
        </tr>
    </table>
</body>

</html>
