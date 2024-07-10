<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REALISASI KKPD</title>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:16px;text-align:center" width="100%"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="font-size:16px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td style="font-size:16px">
                <strong>
                    REKAPITULASI LAPORAN REALISASI BELANJA MENGGUNAKAN KARTU KREDIT PEMERINTAH DAERAH (KKPD) TA
                    {{ tahun_anggaran() }}
                </strong>
            </td>
        </tr>
        <tr>
            <td style="font-size:16px">
                <strong>
                    @if ($pilihan == '1')
                        BULAN : {{ Str::upper(bulan($bulan)) }}
                    @else
                        PERIODE : {{ Str::upper(tanggal($periode1)) }} S/D {{ Str::upper(tanggal($periode2)) }}
                    @endif
                </strong>
            </td>
        </tr>
    </table>

    <hr>
    <br>

    <table style="font-family: Open Sans;font-size:16px;width:100%;border-collapse:collapse" border="1">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">SKPD</th>
                <th colspan="3">Belanja Barang dan Jasa</th>
                <th colspan="3">Belanja Modal</th>
                <th rowspan="2" style="width: 30%">KETERANGAN</th>
            </tr>
            <tr>
                <th>Anggaran <br> (Rp)</th>
                <th>Realisasi Belanja KKPD <br> (Rp)</th>
                <th>Persentase Realisasi KKPD <br> (Rp)</th>
                <th>Anggaran <br>(Rp)</th>
                <th>Realisasi Belanja KKPD <br>(Rp)</th>
                <th>Persentase Realisasi KKPD <br>(Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($realisasiKkpd as $item)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nm_skpd }}</td>
                    <td style="text-align: right">{{ rupiah($item->anggaran_barjas) }}</td>
                    <td style="text-align: right">{{ rupiah($item->realisasi_barjas) }}</td>
                    <td style="text-align: right">
                        {{ $item->realisasi_barjas == 0 ? rupiah(0) : rupiah(($item->realisasi_barjas / $item->anggaran_barjas) * 100) }}
                        %
                    </td>
                    <td style="text-align: right">{{ rupiah($item->anggaran_modal) }}</td>
                    <td style="text-align: right">{{ rupiah($item->realisasi_modal) }}</td>
                    <td style="text-align: right">
                        {{ $item->realisasi_modal == 0 ? rupiah(0) : rupiah(($item->realisasi_modal / $item->anggaran_modal) * 100) }}
                        %
                    </td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (isset($tanda_tangan))
        <table class="table" style="width:100%;margin-top:50px;font-family: Open Sans;font-size:16px">
            <tr>
                <td style="width: 50%"></td>
                <td style="margin: 2px 0px;text-align: center;width:50%">
                    @if (isset($tanggal))
                        Pontianak, {{ tanggal($tanggal) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="padding-bottom: 50px;text-align: center;width:50%">
                    {{ $tanda_tangan->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="text-align: center;width:50%">
                    <b><u>{{ $tanda_tangan->nama }}</u></b> <br>
                    {{ $tanda_tangan->pangkat }} <br>
                    NIP. {{ $tanda_tangan->nip }}
                </td>
            </tr>
        </table>
    @endif
</body>

</html>
