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
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">{{ $nama_skpd }}</h5>
        @elseif($beban == '5')
            <h5 style="margin: 2px 0px">{{ $nama_skpd }}</h5>
        @elseif ($beban == '6')
            <h5 style="margin: 2px 0px">SKPD {{ $nama_skpd }}</h5>
        @endif
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN LANGSUNG GAJI DAN TUNJANGAN</h5>
            <h5 style="margin: 2px 0px">(SPP - LS {{ strtoupper($lcbeban) }})</h5>
        @elseif ($beban == '5')
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - LS Pihak Ketiga Lainnya</h5>
            <h5 style="margin: 2px 0px">(SPP - {{ strtoupper($lcbeban) }})</h5>
        @else
            <h5 style="margin: 2px 0px">(SPP - LS {{ strtoupper($lcbeban) }})</h5>
        @endif
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
        <h5 style="margin: 2px 0px;text-decoration:underline"><b>RINCIAN</b></h5>
    </div>
    <div style="text-align: left">
        <h5 style="margin: 2px 0px">RENCANA PENGGUNA ANGGARAN</h5>
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">BULAN : {{ bulan($cari_data->bulan) }}</u></h5>
        @else
            <h5></h5>
        @endif
    </div>

    <div>
        <table class="table table-striped" style="width:100%" border="1">
            <tr>
                <th style="text-align: center">No Urut</th>
                <th style="text-align: center">Kode Rekening</th>
                <th style="text-align: center">Uraian</th>
                <th style="text-align: center">Jumlah</th>
            </tr>
            @foreach ($result as $data)
                <tr>
                    @if ($beban == '4')
                        @if ($data->urut == '1')
                            <td style="text-align: center">{{ $data->urut }}</td>
                            <td>{{ $data->kode }}</td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @elseif ($data->urut == '5')
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 21) }}.{{ dotrek(STR::substr($data->kode, 22, 7)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @else
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 22) }}{{ dotrek(STR::substr($data->kode, 22, 7)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @endif
                    @elseif ($beban == '5')
                        @if ($data->urut == '1')
                            <td style="text-align: center">{{ $data->urut }}</td>
                            <td>{{ $data->kode }}</td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @elseif ($data->urut == '7')
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 15) }}.{{ dotrek(STR::substr($data->kode, 16, 13)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @else
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 16) }}{{ dotrek(STR::substr($data->kode, 16, 12)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @endif
                    @elseif ($beban == '6')
                        @if ($data->urut == '1')
                            <td style="text-align: center">{{ $data->urut }}</td>
                            <td>{{ $data->kode }}</td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @elseif ($data->urut == '5')
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 21) }}.{{ dotrek(STR::substr($data->kode, 22, 7)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @else
                            <td></td>
                            <td>{{ Str::substr($data->kode, 0, 22) }}{{ dotrek(STR::substr($data->kode, 22, 7)) }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td style="text-align: right">Rp.{{ rupiah($data->nilai) }}</td>
                        @endif
                    @endif
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: center">JUMLAH</td>
                <td style="text-align: right">Rp.{{ rupiah($total) }}</td>
            </tr>
        </table>
    </div>
    <div>
        <h5>Terbilang : {{ ucwords(terbilang($total)) }}</h5>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            @if ($beban == '4')
                <tr>
                    <td style="margin: 2px 0px;text-align: center;padding-left:500px">
                        {{ $daerah->daerah }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:500px">
                        {{ $cari_bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:500px">{{ $cari_bendahara->nama }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:500px">{{ $cari_bendahara->pangkat }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:500px">NIP. {{ $cari_bendahara->nip }}</td>
                </tr>
            @elseif ($beban == '5')
                @if ($sub_kegiatan == '5.02.00.0.06.62')
                    <tr>
                        <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                            {{ $daerah->daerah }},
                            @if ($tanpa == 1)
                                ______________{{ $tahun_anggaran }}
                            @else
                                {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
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
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">NIP. {{ $cari_bendahara->nip }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="text-align: center">MENGETAHUI :</td>
                        <td style="margin: 2px 0px;text-align: center">
                            {{ $daerah->daerah }},
                            @if ($tanpa == 1)
                                ______________{{ $tahun_anggaran }}
                            @else
                                {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 50px;text-align: center">
                            {{ $cari_pptk->jabatan }}
                        </td>
                        <td style="padding-bottom: 50px;text-align: center">
                            {{ $cari_bendahara->jabatan }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $cari_pptk->nama }}</td>
                        <td style="text-align: center">{{ $cari_bendahara->nama }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $cari_pptk->pangkat }}</td>
                        <td style="text-align: center">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $cari_pptk->nip }}</td>
                        <td style="text-align: center">NIP. {{ $cari_bendahara->nip }}</td>
                    </tr>
                @endif
            @elseif ($beban == '6')
                @if ($sub_kegiatan == '5.02.00.0.06.62')
                    <tr>
                        <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                            {{ $daerah->daerah }},
                            @if ($tanpa == 1)
                                ______________{{ $tahun_anggaran }}
                            @else
                                {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
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
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">NIP. {{ $cari_bendahara->nip }}</td>
                    </tr>
                @else
                    @if ($jumlah_spp > 0)
                        <tr>
                            <td style="margin: 2px 0px;text-align: center;padding-left:300px">
                                {{ $daerah->daerah }},
                                @if ($tanpa == 1)
                                    ______________{{ $tahun_anggaran }}
                                @else
                                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
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
                            <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;padding-left:300px">NIP. {{ $cari_bendahara->nip }}</td>
                        </tr>
                    @else
                        <tr>
                            <td style="text-align: center">MENGETAHUI :</td>
                            <td style="margin: 2px 0px;text-align: center">
                                {{ $daerah->daerah }},
                                @if ($tanpa == 1)
                                    ______________{{ $tahun_anggaran }}
                                @else
                                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 50px;text-align: center">
                                {{ $cari_pptk->jabatan }}
                            </td>
                            <td style="padding-bottom: 50px;text-align: center">
                                {{ $cari_bendahara->jabatan }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">{{ $cari_pptk->nama }}</td>
                            <td style="text-align: center">{{ $cari_bendahara->nama }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">{{ $cari_pptk->pangkat }}</td>
                            <td style="text-align: center">{{ $cari_bendahara->pangkat }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">NIP. {{ $cari_pptk->nip }}</td>
                            <td style="text-align: center">NIP. {{ $cari_bendahara->nip }}</td>
                        </tr>

                    @endif
                @endif
            @endif
        </table>
    </div>
</body>

</html>
