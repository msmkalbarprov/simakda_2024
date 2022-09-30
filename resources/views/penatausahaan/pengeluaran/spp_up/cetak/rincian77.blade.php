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
    <div style="text-align: center">
        <h3 style="margin: 2px 0px">{{ title() }}</h3>
        <h3 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN {{ $jenisspp }}</h3>
        <h4 class="unborder" style="margin: 2px 0px">Nomor : {{ $no_spp }}</h4>
        <h4 class="unborder" style="margin: 2px 0px">Tahun Anggaran : {{ tahun_anggaran() }}</h4>
        <h3 style="margin:10px 0px">RINCIAN RENCANA PENGGUNAAN</h3>
    </div>
    <br>
    <div>
        <table class="table table-bordered" style="width: 100%" border="1">
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
    <div>
        <h5 class="unborder">Terbilang: ## <span
                style="font-style:italic">({{ ucwords(terbilang($data_spp->nilaisub)) }})</span> ##</h5>
    </div>

    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            @if ($sub_kegiatan == '5.02.00.0.06.62')
                <tr>
                    <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                        {{ daerah($kd_skpd) }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ tanggal($spp->tgl_spp) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:300px">
                        {{ $bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:300px">{{ $bendahara->nama }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:300px">NIP. {{ $bendahara->nip }}</td>
                </tr>
            @else
                <tr>
                    <td style="text-align: center;padding-left:100px">Mengetahui/Menyetujui:</td>
                    <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                        {{ daerah($kd_skpd) }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ tanggal($spp->tgl_spp) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:100px;font-weight: bold">
                        {{ $bendahara->jabatan }}
                    </td>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:300px;font-weight: bold">
                        {{ $pa->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:100px;text-decoration:underline">
                        {{ $bendahara->nama }}</td>
                    <td style="text-align: center;padding-left:300px;text-decoration:underline">{{ $pa->nama }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:100px">NIP. {{ $bendahara->nip }}</td>
                    <td style="text-align: center;padding-left:300px">NIP. {{ $pa->nip }}</td>
                </tr>
            @endif
        </table>
    </div>
</body>

</html>
