<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REGISTER SP2D BATAL</title>
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
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <table style="border-collapse:collapse;font-family:'Open Sans', Helvetica,Arial,sans-serif; font-size:18px"
        width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: center">
                <b>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}
                </b>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;font-family:'Open Sans', Helvetica,Arial,sans-serif">
                <b>
                    REGISTER SP2D BATAL
                </b>
            </td>
        </tr>
    </table>

    <br><br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif" border="1" id="rincian">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. SP2D</th>
                <th>Tanggal SP2D</th>
                <th>No. SPM</th>
                <th>Tanggal SPM</th>
                <th>No. SPP</th>
                <th>Tanggal SPP</th>
                <th>Keperluan</th>
                <th>Tanggal Batal</th>
                <th>Alasan Batal</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($register_sp2d as $register)
                @php
                    $total += $register->nilai;
                @endphp
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ $register->no_sp2d }}</td>
                    <td>{{ tanggal_indonesia($register->tgl_sp2d) }}</td>
                    <td>{{ $register->no_spm }}</td>
                    <td>{{ tanggal_indonesia($register->tgl_spm) }}</td>
                    <td>{{ $register->no_spp }}</td>
                    <td>{{ tanggal_indonesia($register->tgl_spp) }}</td>
                    <td>{{ $register->keperluan }}</td>
                    <td>{{ tanggal_indonesia($register->tgl_batal) }}</td>
                    <td>{{ $register->ket_batal }}</td>
                    <td class="angka">{{ rupiah($register->nilai) }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: center" colspan="10"><b>Total</b></td>
                <td class="angka"><b>{{ rupiah($total) }}</b></td>
            </tr>
        </tbody>
    </table>

    @if (isset($tanda_tangan))
        <div style="padding-top:20px;padding-left:800px">
            <table class="table" style="width:100%">
                <tr>
                    <td style="margin: 2px 0px;text-align: center">
                        @if (isset($tanggal))
                            Pontianak, {{ tanggal($tanggal) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center">
                        {{ $tanda_tangan->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center"><b><u>{{ $tanda_tangan->nama }}</u></b></td>
                </tr>
                <tr>
                    <td style="text-align: center">{{ $tanda_tangan->pangkat }}</td>
                </tr>
                <tr>
                    <td style="text-align: center">NIP. {{ $tanda_tangan->nip }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>

</html>
