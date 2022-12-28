<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Rincian</title>
    <style>
        table,
        th,
        td {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    {{-- <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        <h5 style="margin: 2px 0px">{{ $skpd->nm_skpd }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr> --}}
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px">
                <strong>
                    {{ $skpd->nm_skpd }}
                </strong>
            </td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px">LAMPIRAN SURAT PERINTAH MEMBAYARAN</h5>
        <h5 style="margin: 2px 0px"><b>(LAMPIRAN SPM)</b></h5>
        <h5 style="margin: 2px 0px"><b>{{ $no_spm }}</b></h5>
    </div>
    <div>
        <table class="table table-striped" style="width:100%" border="1">
            <tr>
                <th style="text-align: center">No Urut</th>
                <th style="text-align: center">Kode Rekening</th>
                <th style="text-align: center">Uraian</th>
                <th style="text-align: center">Jumlah</th>
            </tr>
            @foreach ($data_beban as $data)
                <tr>
                    @if ($data->urut == '1')
                        <td style="text-align: center"><b>{{ $loop->iteration }}</b></td>
                        <td><b>{{ $data->kode }}</b></td>
                        <td><b>{{ $data->nama }}</b></td>
                        <td style="text-align: right"><b>{{ rupiah($data->nilai) }}</b></td>
                    @elseif ($data->urut == '6')
                        <td></td>
                        <td>{{ $data->kode }}</td>
                        <td>{{ $data->nama }}</td>
                        <td style="text-align: right">{{ rupiah($data->nilai) }}</td>
                    @else
                        <td></td>
                        <td><b>{{ $data->kode }}</b></td>
                        <td><b>{{ $data->nama }}</b></td>
                        <td style="text-align: right"><b>{{ rupiah($data->nilai) }}</b></td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: center"><b>JUMLAH</b></td>
                <td style="text-align: right"><b>{{ rupiah($total) }}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <h5 style="margin: 2px 0px">Terbilang : <i>{{ ucwords(terbilang($total)) }}</i></h5>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:500px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ tanggal($data_spm->tgl_spm) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:500px">
                    {{ $pa_kpa->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px"><strong><u>{{ $pa_kpa->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">{{ $pa_kpa->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">NIP. {{ $pa_kpa->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
