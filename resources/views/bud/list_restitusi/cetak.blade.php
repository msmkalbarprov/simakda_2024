<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LIST RESTITUSI</title>

    <style>
        table>thead>tr>th {
            background-color: #CCCCCC
        }
    </style>
</head>

<body>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td>{{ $header->nm_pemda }}</td>
        </tr>
        <tr>
            <td>
                <hr>
            </td>
        </tr>
        <tr>
            <td>SURAT TANDA SETORAN</td>
        </tr>
        <tr>
            <td>
                <hr>
            </td>
        </tr>
        <tr>
            <td>(STS)</td>
        </tr>
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td style="width: 10%">No STS</td>
            <td style="width: 40%">: {{ $restitusi->no_sts }}</td>
            <td style="width: 10%">Bank</td>
            <td style="width: 40%">: {{ $restitusi->nm_bank }}</td>
        </tr>
        <tr>
            <td>OPD</td>
            <td>: {{ $restitusi->nm_skpd }}</td>
            <td>No Rekening</td>
            <td>: {{ $restitusi->rek_bank }}</td>
        </tr>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td style="width: 30%">Harap diterima uang sebesar <br>(dengan huruf)</td>
            <td style="width: 70%">:</td>
        </tr>
    </table>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td>Dengan rincian penerimaan sebagai berikut</td>
        </tr>
    </table>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;border-collapse:collapse"
        border="1">
        <thead>
            <tr>
                <th>No</th>
                <th colspan="5">Kode Rekening</th>
                <th>Uraian Rincian Objek</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($rincian as $item)
                @php
                    $total += $item->rupiah;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ substr($item->kd_rek6, 0, 1) }}</td>
                    <td>{{ substr($item->kd_rek6, 1, 1) }}</td>
                    <td>{{ substr($item->kd_rek6, 2, 1) }}</td>
                    <td>{{ substr($item->kd_rek6, 3, 2) }}</td>
                    <td>{{ substr($item->kd_rek6, 5, 2) }}</td>
                    <td>{{ $item->nm_rek6 }}</td>
                    <td style="text-align: right">{{ rupiah($item->rupiah) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align: right">Jumlah</td>
                <td style="text-align: right">{{ rupiah($total) }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td>Uang tersebut diterima pada tanggal {{ tanggal($restitusi->tgl_sts) }}</td>
        </tr>
    </table>

    <br>
    <br>
    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td style="width: 50%"><b>Mengetahui<br>Pengguna Anggaran/Kuasa Pengguna Anggaran</b></td>
            <td style="width: 50%"><b>Bendahara Penerimaan<br>Bendahara Penerimaan Pembantu</b></td>
        </tr>
        <tr>
            <td style="height: 50px"></td>
            <td style="height: 50px"></td>
        </tr>
        <tr>
            <td style="width: 50%"><b>Nama Lengkap<br>NIP.</b></td>
            <td style="width: 50%"><b>Nama Lengkap<br>NIP.</b></td>
        </tr>
    </table>

</body>

</html>
