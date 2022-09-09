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
        <h3 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h3>
        <h3 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN {{ $jenisspp }}</h3>
        <h4 class="unborder" style="margin: 2px 0px">Nomor : {{ $no_spp }}</h4>
        <h4 class="unborder" style="margin: 2px 0px">Tahun Anggaran : {{ $tahun_anggaran }}</h4>
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
                <td colspan="4">{{ $nama_kegiatan->nm_kegiatan }} / {{ $data_spp->nm_sub_kegiatan }}</td>
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
                        Pontianak,
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ \Carbon\Carbon::parse($spp->tgl_spp)->locale('id')->isoFormat('D MMMM Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:300px">
                        {{ $cari_bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nama }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:300px">NIP. {{ $cari_bendahara->nip }}</td>
                </tr>
            @else
                <tr>
                    <td style="text-align: center;padding-left:100px">Mengetahui/Menyetujui:</td>
                    <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                        Pontianak,
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ \Carbon\Carbon::parse($spp->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:100px;font-weight: bold">
                        {{ $cari_bendahara->jabatan }}
                    </td>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:300px;font-weight: bold">
                        {{ $cari_pa->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:100px;text-decoration:underline">
                        {{ $cari_bendahara->nama }}</td>
                    <td style="text-align: center;padding-left:300px;text-decoration:underline">{{ $cari_pa->nama }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:100px">NIP. {{ $cari_bendahara->nip }}</td>
                    <td style="text-align: center;padding-left:300px">NIP. {{ $cari_pa->nip }}</td>
                </tr>
            @endif
        </table>
    </div>
</body>

</html>
