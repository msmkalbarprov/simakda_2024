<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAMPIRAN SP2D</title>
    <style>
        table,
        td,
        th {
            border-collapse: collapse;
            font-family: 'Open Sans', Helvetica, Arial, sans-serif
        }

        .center {
            text-align: center;
            border: 1px solid black;
        }

        .border {
            border-left: 1px solid black;
            border-bottom: 1px solid black
        }

        .bottom {
            border-bottom: 1px solid black;
        }

        .bottom1 {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
        }

        .kanan {
            border-right: 1px solid black;
        }

        .kiri {
            border-left: 1px solid black;
        }

        #rincian>thead>tr>th {
            border: 1px solid black;
            text-align: center
        }

        #rincian>tbody>tr>td {
            border: 1px solid black;
        }

        #potongan>thead>tr>th {
            /* border: 1px solid black; */
            text-align: center;
            border-left: 1px solid black;
            border-right: 1px solid black;
        }

        #potongan>tbody>tr>td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table style="text-align: center;width:100%">
        <tr>
            <td style="font-size:20px"><b>Daftar Lampiran SP2D Nomor: {{ $no_sp2d }}</b></td>
        </tr>
        <tr>
            <td style="font-size:20px"><b>Tanggal : {{ tanggal($sp2d->tgl_sp2d) }}</b></td>
        </tr>
    </table>
    <br>
    <br>
    <table id="rincian" style="width: 100%" style="border: 1px solid black">
        <thead>
            <tr>
                <th><strong>NO</strong></th>
                <th><strong>KODE KEGIATAN/SUB KEGIATAN</strong></th>
                <th><strong>URAIAN</strong></th>
                <th><strong>JUMLAH (Rp)</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="center">1</td>
                <td class="center">2</td>
                <td class="center">3</td>
                <td class="center">4</td>
            </tr>
            @foreach ($data_sp2d as $item)
                @if ($item->no == '3')
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td>{{ dotrek($item->kd_rek) }}</td>
                        <td>{{ $item->nm_rek }}</td>
                        <td style="text-align: right">{{ rupiah($item->nilai) }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="center"><b>{{ $loop->iteration }}</b></td>
                        <td><b>{{ $item->kd_rek }}</b></td>
                        <td><b>{{ $item->nm_rek }}</b></td>
                        <td style="text-align: right"><b>{{ rupiah($item->nilai) }}</b></td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right"><strong>JUMLAH</strong></td>
                <td style="text-align: right">{{ rupiah($total->nilai) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="width: 50%"></td>
                <td style="margin: 2px 0px;text-align: center">
                    Pontianak, {{ tanggal($sp2d->tgl_sp2d) }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="text-align: center">
                    {{ $ttd1->jabatan2 }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $ttd1->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="text-align: center">
                    <strong><u>{{ $ttd1->nama }}</u></strong> <br> {{ $ttd1->pangkat }}
                    <br> NIP. {{ $ttd1->nip }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
