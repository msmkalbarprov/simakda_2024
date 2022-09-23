<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table,
        td,
        th {
            border-collapse: collapse
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
    <div style="text-align: center;margin-top:20px;margin-bottom:20px">
        <h3 style="margin: 2px 0px"><strong>Daftar Lampiran SP2D Nomor: {{ $no_sp2d }}</strong></h3>
        <h3 style="margin: 2px 0px"><strong>Tanggal : {{ tanggal($sp2d->tgl_sp2d) }}</strong></h3>
        <div style="clear: both"></div>
    </div>
    <div>
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
                @php
                    $no = 1;
                @endphp
                @foreach ($data_sp2d as $item)
                    <tr>
                        @if (Str::length($item->kd_rek) >= 12)
                            <td class="center">{{ $no++ }}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ $item->kd_sub_kegiatan }} {{ dotrek($item->kd_rek) }}</td>
                        <td>{{ $item->nm_rek }}</td>
                        <td style="text-align: right">{{ rupiah($item->nilai) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right"><strong>JUMLAH</strong></td>
                    <td style="text-align: right"><b>{{ rupiah($total->nilai) }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:600px">
                    Pontianak, {{ tanggal($sp2d->tgl_sp2d) }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">
                    {{ $ttd1->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:600px">
                    Kuasa Bendahara Umum Daerah
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px"><strong><u>{{ $ttd1->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">{{ $ttd1->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:600px">NIP. {{ $ttd1->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
