<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK DPT</title>

    <style>
        #rincian1>tbody>tr>td {
            vertical-align: top;
            font-size: 14px
        }

        .rincian>tbody>tr>td {
            font-size: 14px
        }

        #rincian {
            border-collapse: collapse
        }
    </style>
</head>

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
            <td align="left" style="font-size:16px"><strong>{{ nama_skpd($kd_skpd) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td>
                <b>
                    DAFTAR PEMBAYARAN TAGIHAN KKPD <br>
                    BANK ....
                </b>
            </td>
        </tr>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif" border="1" id="rincian">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No KKPD</th>
                <th>Jenis Belanja <br>Barang</th>
                <th>Rincian <br> Pengeluaran</th>
                <th colspan="7">Pembebanan Anggaran</th>
                <th>Jumlah Pembayaran <br>(dalam Rupiah)</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Kode sub kegiatan</th>
                <th>Kode akun</th>
                <th>Kode kelompok</th>
                <th>Kode jenis</th>
                <th>Kode objek</th>
                <th>Kode rincian objek</th>
                <th>Kode sub rincian objek</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($detail_dpt as $detail)
                @php
                    $total += $detail->nilai;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->nm_kkpd }}</td>
                    <td>{{ $detail->no_kkpd }}</td>
                    <td>
                        {{ (($detail->jenis_belanja == 1 ? 'Perjalanan Dinas' : $detail->jenis_belanja == 2) ? 'Belanja Modal' : $detail->jenis_belanja == 3) ? 'Belanja Barang/Jasa' : '' }}
                    </td>
                    <td>{{ $detail->uraian }}</td>
                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 0, 1) }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 1, 1) }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 2, 2) }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 4, 2) }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 6, 2) }}</td>
                    <td>{{ Str::substr($detail->kd_rek6, 8, 4) }}</td>
                    <td style="text-align: right">{{ rupiah($detail->nilai) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="12" style="text-align: center"><b>Total</b></td>
                <td style="text-align: right"><b>{{ rupiah($total) }}</b></td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>

    <div style="padding-top:20px">
        <table class="table rincian" style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">

            <tr>
                <td style="width: 50%"></td>
                <td style="margin: 2px 0px;text-align: center">
                    {{-- {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                    @endif --}}
                    Pontianak, {{ tanggal($dpt->tgl_dpt) }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $ttd->jabatan }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center">
                    <b><u>{{ $ttd->nama }}</u></b> <br>
                    {{ $ttd->pangkat }} <br>
                    NIP. {{ $ttd->nip }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
