<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK SPTB</title>
</head>

<body>
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
            <td align="left" style="font-size:14px"><strong>SKPD {{ nama_skpd($kd_skpd) }}</strong></td>
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
        <table style="width: 100%">
            <tr>
                <td><u><b>SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</b></u></td>
            </tr>
        </table>
    </div>

    <div style="text-align: left;padding-top:20px">
        <table style="width: 100%">
            <tr>
                <td>1. OPD</td>
                <td>:</td>
                <td>{{ $kd_skpd }} - {{ nama_skpd($kd_skpd) }}</td>
            </tr>
            <tr>
                <td>2. Satuan Kerja</td>
                <td>:</td>
                <td>{{ $kd_skpd }} - {{ nama_skpd($kd_skpd) }}</td>
            </tr>
            <tr>
                <td>3. Tanggal/NO. DPA</td>
                <td>:</td>
                <td>{{ tanggal($dpa->tgl_dpa) }} dan {{ $dpa->no_dpa }}</td>
            </tr>
            <tr>
                <td>4. Tahun Anggaran</td>
                <td>:</td>
                <td>{{ tahun_anggaran() }}</td>
            </tr>
            <tr>
                <td>4. Tahun Anggaran</td>
                <td>:</td>
                <td>{{ tahun_anggaran() }}</td>
            </tr>
            <tr>
                <td>5. Jumlah Belanja</td>
                <td>:</td>
                <td>{{ rupiah($jumlah_belanja->nilai) }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align: left">

    </div>

    {{-- <div>
        <table>

            <tr>
                <td>a. Urusan Pemerintahan</td>
                <td>:</td>
                <td>{{ $cari_data->kd_bidang_urusan }} - {{ $cari_data->nm_bidang_urusan }}</td>
            </tr>

            <tr>
                <td>b. OPD</td>
                <td>:</td>
                <td>{{ $cari_data->kd_skpd }} - {{ $cari_data->nm_skpd }}</td>
            </tr>

            <tr>
                <td>c. Tahun Anggaran</td>
                <td>:</td>
                <td>{{ $tahun_anggaran }}</td>
            </tr>

            <tr>
                <td>d. Dasar Pengeluaran SPD</td>
                <td>:</td>
                <td>{{ $cari_data->no_spd }}</td>
            </tr>

            <tr>
                <td>e. Jumlah Sisa Dana SPD</td>
                <td>:</td>
                <td>Rp. {{ rupiah($cari_data->spd - $cari_data->spp) }}</td>
            </tr>
            <tr>
                <td style="text-align: center">(terbilang)</td>
                <td></td>
                <td style="font-style: italic">({{ ucwords(terbilang($cari_data->spd - $cari_data->spp)) }})</td>
            </tr>

            <tr>
                <td>f. Untuk Keperluan Bulan</td>
                <td>:</td>
                <td>{{ bulan($cari_data->bulan) }}</td>
            </tr>

            <tr>
                <td>g. Jumlah Pembayaran yang Diminta</td>
                <td>:</td>
                <td>Rp. {{ rupiah($cari_data->nilai) }}</td>
            </tr>
            <tr>
                <td style="text-align: center">(terbilang)</td>
                <td></td>
                <td style="font-style: italic">({{ ucwords(terbilang($cari_data->nilai)) }})</td>
            </tr>

            @if ($beban == '4')
                <tr>
                    <td>h. Nama {{ ucwords($cari_bendahara->jabatan) }}</td>
                    <td>:</td>
                    <td>{{ $cari_bendahara->nama }}</td>
                </tr>
            @elseif ($beban == '5')
                @if ($jenis == '3')
                    <tr>
                        <td>h. Nama Pihak Ketiga</td>
                        <td>:</td>
                        <td>{{ $cari_data->nmrekan }}</td>
                    </tr>
                @else
                    <tr>
                        <td>h. Nama Bendahara Pengeluaran</td>
                        <td>:</td>
                        <td>{{ $cari_bendahara->nama }}</td>
                    </tr>
                @endif
            @else
                @if ($jenis == '3')
                    <tr>
                        <td>h. Nama Pihak Ketiga</td>
                        <td>:</td>
                        <td>{{ $cari_data->nmrekan }}</td>
                    </tr>
                @else
                    <tr>
                        <td>h. Nama Bendahara Pengeluaran</td>
                        <td>:</td>
                        <td>{{ $cari_bendahara->nama }}</td>
                    </tr>
                @endif
            @endif

            <tr>
                @if ($beban == '4')
                    <td>i. Nama, Nomor Rekening Bank dan NPWP</td>
                    <td>:</td>
                    <td>{{ $bank->nama }} / {{ $cari_data->no_rek }} / {{ $cari_data->npwp }}</td>
                @elseif ($beban == '5')
                    @if ($jenis == '3')
                        <td>i. Nama, Nomor Rekening Bank dan NPWP</td>
                        <td>:</td>
                        <td>{{ $bank->nama }} / {{ $cari_data->no_rek }} / {{ $cari_data->npwp }}</td>
                    @else
                        <td>i. Nama, Nomor Rekening Bank</td>
                        <td>:</td>
                        <td>{{ $bank->nama }} / {{ $cari_data->no_rek }}</td>
                    @endif
                @else
                    <td>i. Nama, Nomor Rekening Bank</td>
                    <td>:</td>
                    <td>{{ $bank->nama }} / {{ $cari_data->no_rek }}</td>
                @endif

            </tr>
        </table>
    </div>
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            @if ($beban == '4')
                <tr>
                    <td style="margin: 2px 0px;text-align: center;padding-left:600px">
                        {{ $daerah->daerah }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center;padding-left:600px">
                        {{ $cari_bendahara->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:600px"><b><u>{{ $cari_bendahara->nama }}</u></b></td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:600px">{{ $cari_bendahara->pangkat }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;padding-left:600px">NIP. {{ $cari_bendahara->nip }}</td>
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
                        <td style="text-align: center;padding-left:300px"><b><u>{{ $cari_bendahara->nama }}</u></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nip }}</td>
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
                        <td style="text-align: center"><u><b>{{ $cari_pptk->nama }}</b></u></td>
                        <td style="text-align: center"><u><b>{{ $cari_bendahara->nama }}</b></u></td>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $cari_pptk->pangkat }}</td>
                        <td style="text-align: center">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center">NIP. {{ $cari_pptk->nip }}</td>
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
                        <td style="text-align: center;padding-left:300px"><b><u>{{ $cari_bendahara->nama }}</u></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nip }}</td>
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
                            <td style="text-align: center;padding-left:300px"><u><b>{{ $cari_bendahara->nama }}</b></u>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->pangkat }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nip }}</td>
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
                            <td style="text-align: center">NIP. {{ $cari_pptk->nip }}</td>
                            <td style="text-align: center">NIP. {{ $cari_bendahara->nip }}</td>
                        </tr>
                    @endif
                @endif
            @endif
        </table>
    </div> --}}
</body>

</html>
