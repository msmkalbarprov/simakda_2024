<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pengantar</title>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        <h5 style="margin: 2px 0px">{{ $cari_data->nm_skpd }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN LANGSUNG GAJI DAN TUNJANGAN</h5>
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">(SPP - {{ strtoupper($lcbeban) }})</h5>
        @elseif ($beban == '5')
            <h5 style="margin: 2px 0px">(SPP - LS {{ strtoupper($lcbeban) }})</h5>
        @else
            <h5 style="margin: 2px 0px">(SPP - LS {{ strtoupper($lcbeban) }})</h5>
        @endif
        <h5 style="margin: 2px 0px">SURAT PENGANTAR</h5>
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
    </div>
    <div style="text-align: left">
        <h5 style="margin: 2px 0px">Kepada Yth:</h5>
        <h5 style="margin: 2px 0px">{{ $peng }}</h5>
        <h5 style="margin: 2px 0px">OPD : {{ $cari_data->nm_skpd }}</h5>
        <h5 style="margin: 2px 0px">Di <u>{{ strtoupper($daerah->daerah) }}</u></h5>
    </div>
    <div style="text-align: left">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat {{ $nogub }}
                tentang Penjabaran APBD Tahun Anggaran {{ $tahun_anggaran }}. Bersama ini kami mengajukan Surat
                Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:</h5>
        @elseif ($beban == '5')
            @if ($kd_skpd == '1.03.01.01')
                <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat tentang
                    {{ $nogub }} tentang Perubahan Peraturan Gubernur Kalimantan Barat No. 84 Tahun 2015 tentang
                    Penjabaran APBD Tahun Anggaran {{ $tahun_anggaran }}. Bersama ini kami mengajukan Surat Permintaan
                    Pembayaran Langsung Pihak Ketiga Lainnya sebagai berikut:</h5>
            @else
                <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat
                    {{ $nogub }} tentang Perubahan atas Peraturan Gubernur Nomor 155 Tahun 2020 tanggal 30
                    Desember 2020 tentang Penjabaran APBD Tahun Anggaran {{ $tahun_anggaran }}. Bersama ini kami
                    mengajukan Surat Permintaan Pembayaran Langsung Pihak Ketiga Lainnya sebagai berikut:</h5>
            @endif
        @else
            @if ($kd_skpd == '1.03.01.01')
                <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat
                    {{ $nogub }} tentang Perubahan Peraturan Gubernur Kalimantan Barat No. 84 Tahun 2015 tentang
                    Penjabaran APBD Tahun Anggaran {{ $tahun_anggaran }}. Bersama ini kami mengajukan Surat Permintaan
                    Pembayaran Langsung Barang dan Jasa sebagai berikut:</h5>
            @else
                <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat
                    {{ $nogub }} tentang Perubahan atas Peraturan Gubernur Nomor 155 Tahun 2020 tanggal 30
                    Desember 2020 tentang Penjabaran APBD Tahun Anggaran {{ $tahun_anggaran }}. Bersama ini kami
                    mengajukan Surat Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:</h5>
            @endif
        @endif
    </div>
    <div>
        <table>
            {{-- Urusan Pemerintahan --}}
            <tr>
                <td>a. Urusan Pemerintahan</td>
                <td>:</td>
                <td>{{ $cari_data->kd_bidang_urusan }} - {{ $cari_data->nm_bidang_urusan }}</td>
            </tr>
            {{-- OPD --}}
            <tr>
                <td>b. OPD</td>
                <td>:</td>
                <td>{{ $cari_data->kd_skpd }} - {{ $cari_data->nm_skpd }}</td>
            </tr>
            {{-- Tahun Anggaran --}}
            <tr>
                <td>c. Tahun Anggaran</td>
                <td>:</td>
                <td>{{ $tahun_anggaran }}</td>
            </tr>
            {{-- Dasar Pengeluaran SPD --}}
            <tr>
                <td>d. Dasar Pengeluaran SPD</td>
                <td>:</td>
                <td>{{ $cari_data->no_spd }}</td>
            </tr>
            {{-- Jumlah Sisa Dana SPD --}}
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
            {{-- Untuk Keperluan Bulan --}}
            <tr>
                <td>f. Untuk Keperluan Bulan</td>
                <td>:</td>
                <td>{{ bulan($cari_data->bulan) }}</td>
            </tr>
            {{-- Jumlah Pembayaran yang Diminta --}}
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
            {{-- Nama Bendahara Pengeluaran --}}
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
            {{-- Nama Nomor Rekening Bank dan NPWP --}}
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
    {{-- tanda tangan --}}
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
                    <td style="text-align: center;padding-left:600px">{{ $cari_bendahara->nama }}</td>
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
                        <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nama }}</td>
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
                            <td style="text-align: center;padding-left:300px">{{ $cari_bendahara->nama }}</td>
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
    </div>
</body>

</html>
