<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .unborder {
            font-weight: normal
        }

        table,
        tr,
        td {
            border-collapse: collapse
        }
    </style>
</head>

<body>
    <table class="table" style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:14px">
        <tr>
            <td align="center"><b>
                {{ title() }} <br>
                SURAT PERMINTAAN PEMBAYARAN {{ $jenisspp }} <br></b>
                Nomor : {{ $no_spp }} <br>
                Tahun Anggaran : {{ tahun_anggaran() }}<br><br>
                <b>RINCIAN RENCANA PENGGUNAAN </b>
            </td>
        </tr>
    </table>
    
    <br>
    <div>
        <table class="table table-bordered" style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px" border="1">
            <tr>
                <td style="font-weight: bold;text-align:center">No</td>
                <td style="font-weight: bold;text-align:center">Kode Rekening</td>
                <td style="font-weight: bold;text-align:center">Uraian</td>
                <td style="font-weight: bold;text-align:center">Nilai Rupiah</td>
            </tr>
            <tr>
                <td colspan="4">{{ isset($nama_kegiatan->nm_kegiatan) ? $nama_kegiatan->nm_kegiatan : '' }} /
                    {{ isset($data_spp->nm_sub_kegiatan) ? $data_spp->nm_sub_kegiatan : '' }}</td>
            </tr>
            @foreach ($data_spp_rinci as $item)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kd_rek6 }}</td>
                    <td>{{ $item->nm_rek6 }}</td>
                    <td style="text-align: right">{{ rupiah($item->nilaispp) }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align:right" colspan="3">Total</td>
                <td style="text-align:right">{{ rupiah($data_spp->nilaisub) }}</td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table  style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px">
            <tr>
                <td>
                    Terbilang: ## <span
                style="font-style:italic">({{ ucwords(terbilang($data_spp->nilaisub)) }})</span> ##
                </td>
            </tr>
        
        </table>
    </div>

    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:12px">
            @if ($sub_kegiatan == '5.02.00.0.06.62')
                <tr>
                    <td width='50%' style="margin: 2px 0px;text-align: center;paddin:300px">
                        {{ daerah($kd_skpd) }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ tanggal($spp->tgl_spp) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width='50%' style="padding-bottom: 50px;text-align: center">
                        {{ $bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td width='50%' style="text-align: center">{{ $bendahara->nama }}</td>
                </tr>
                <tr>
                    <td width='50%' style="text-align: center">NIP. {{ $bendahara->nip }}</td>
                </tr>
            @else
                <tr>
                    <td width='50%' style="text-align: center">Mengetahui/Menyetujui:</td>
                    <td width='50%' style="margin: 2px 0px;text-align: center">
                        {{ daerah($kd_skpd) }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ tanggal($spp->tgl_spp) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width='50%' style="padding-bottom: 50px;text-align: center;font-weight: bold">
                        {{ $pa->jabatan }}
                    </td>
                    <td width='50%' style="padding-bottom: 50px;text-align: center;font-weight: bold">
                        {{ $bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td width='50%' style="text-align: center;text-decoration:underline">
                        {{ $pa->nama }}</td>
                    <td width='50%' style="text-align: center;text-decoration:underline">{{ $bendahara->nama }}
                    </td>
                </tr>
                <tr>
                    <td width='50%' style="text-align: center">NIP. {{ $pa->nip }}</td>
                    <td width='50%' style="text-align: center">NIP. {{ $bendahara->nip }}</td>
                </tr>
            @endif
        </table>
    </div>
</body>

</html>
