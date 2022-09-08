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
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN {{ $jenisspp }}</h5>
        <h5 class="unborder" style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
        <h5 class="unborder" style="margin: 2px 0px">Tahun Anggaran : {{ $tahun_anggaran }}</h5>
        <h5 style="margin:10px 0px">RINCIAN RENCANA PENGGUNAAN</h5>
    </div>
    <br>
    <div>
        <table class="table table-bordered" style="width: 100%" border="1">
            <tr>
                <td style="font-weight: bold">No</td>
                <td style="font-weight: bold">Kode Rekening</td>
                <td style="font-weight: bold">Uraian</td>
                <td style="font-weight: bold">Nilai Rupiah</td>
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
</body>

</html>
