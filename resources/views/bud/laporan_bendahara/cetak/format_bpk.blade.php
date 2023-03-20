<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REGISTER SP2D FORMAT BPK</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #header3>th {
            background-color: #CCCCCC;
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: hidden
        }

        .atas {
            border-top: hidden
        }

        .angka {
            text-align: right
        }

        .rincian>tbody>tr>td {
            font-size: 14px
        }
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:16px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px">
                <strong>
                    BADAN KEUANGAN DAN ASET DAERAH
                </strong>
            </td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>
    <br>

    <table style="text-align: center;width:100%">
        <tr>
            <td><b>REGISTER SP2D</b></td>
        </tr>
    </table>

    <br>
    <br>
    <table style="font-family: Open Sans;width:100%" border="1">
        <thead>
            <tr>
                <th rowspan="2">SKPD</th>
                <th rowspan="2">Jenis</th>
                <th rowspan="2">Kelompok</th>
                <th rowspan="2">Objek</th>
                <th rowspan="2">Rincian Objek</th>
                <th rowspan="2">Sub Rincian Objek</th>
                <th rowspan="2">No. SP2D</th>
                <th rowspan="2">Tgl. SP2D</th>
                <th rowspan="2">Keperluan</th>
                <th rowspan="2">Rekanan</th>
                <th rowspan="2">NPWP</th>
                <th rowspan="2">Nilai SP2D</th>
                <th colspan="5">Potongan Pajak</th>
            </tr>
            <tr>
                <th>PPH 21</th>
                <th>PPH 22</th>
                <th>PPH 23</th>
                <th>PPH 4 AYAT 2</th>
                <th>PPN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_awal as $data)
                <tr>
                    <td>{{ $data->nm_skpd }}</td>
                    <td>{{ $data->jenis }}</td>
                    <td>{{ $data->kelompok }}</td>
                    <td>{{ $data->objek }}</td>
                    <td>{{ $data->rincianobjek }}</td>
                    <td>{{ $data->subrincianobjek }}</td>
                    <td>{{ $data->no_sp2d }}</td>
                    <td>{{ $data->tgl_sp2d }}</td>
                    <td>{{ $data->keperluan }}</td>
                    <td>{{ $data->nmrekan }}</td>
                    <td>{{ npwp($data->npwp) }}</td>
                    <td class="angka">{{ rupiah($data->nilai_sp2d) }}</td>
                    <td class="angka">{{ rupiah($data->pph21) }}</td>
                    <td class="angka">{{ rupiah($data->pph22) }}</td>
                    <td class="angka">{{ rupiah($data->pph23) }}</td>
                    <td class="angka">{{ rupiah($data->pph4ayat2) }}</td>
                    <td class="angka">{{ rupiah($data->ppn) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <br>



    @if (isset($tanda_tangan))
        <div style="padding-top:20px;padding-left:500px">
            <table class="table rincian" style="width:100%">
                <tr>
                    <td style="margin: 2px 0px;text-align: center;font-size:16px">
                        @if (isset($tanggal))
                            Pontianak, {{ tanggal($tanggal) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;font-size:16px">
                        {{-- {{ $tanda_tangan->jabatan }} --}}
                        Kepala Bidang Perbendaharaan <br> Badan Keuangan dan Aset Daerah <br> Provinsi Kalimantan Barat
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;font-size:16px"><b><u>{{ $tanda_tangan->nama }}</u></b></td>
                </tr>
                <tr>
                    <td style="text-align: center;font-size:16px">{{ $tanda_tangan->pangkat }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;font-size:16px">NIP. {{ $tanda_tangan->nip }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>

</html>
