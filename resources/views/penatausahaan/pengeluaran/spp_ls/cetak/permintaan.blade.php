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
        <h5 style="margin: 2px 0px">{{ $nama_skpd }}</h5>
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN {{ strtoupper($lcbeban) }}</h5>
        <h5 style="margin: 2px 0px">(SPP - LS {{ strtoupper($lcbeban) }})</h5>
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
    </div>

    <div style="text-align: left">
        <table class="table" style="width: 100%;border:1px black solid">
            {{-- OPD --}}
            <tr>
                <td>1. OPD</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $nama_skpd }}</td>
            </tr>
            {{-- Unit Kerja --}}
            <tr>
                <td>2. Unit Kerja</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $nama_skpd }}</td>
            </tr>
            {{-- Alamat --}}
            <tr>
                <td>3. Alamat</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $alamat_skpd }}</td>
            </tr>
            {{-- Nomor dan Tanggal DPA/DPPA/DPPAL-OPD --}}
            <tr>
                <td>4. Nomor dan Tanggal DPA/DPPA/DPPAL-OPD</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $dpa->no_dpa }} / {{ $dpa->tgl_dpa }}</td>
            </tr>
            {{-- Tahun Anggaran --}}
            <tr>
                <td>5. Tahun Anggaran</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $tahun_anggaran }}</td>
            </tr>
            {{-- Bulan --}}
            <tr>
                <td>6. Bulan</td>
                <td style="padding-left:200px">:</td>
                <td>{{ bulan($data_spp->bulan) }} {{ $tahun_anggaran }}</td>
            </tr>
            {{-- Urusan Pemerintahan --}}
            <tr>
                <td>7. Urusan Pemerintahan</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $data_spp->kd_bidang_urusan }} {{ $data_spp->nm_bidang_urusan }}</td>
            </tr>
            {{-- Nama Program --}}
            <tr>
                <td>8. Nama Program</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $nama_program }}</td>
            </tr>
            {{-- Nama Kegiatan --}}
            <tr>
                <td>9. Nama Kegiatan</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $nama_kegiatan }}</td>
            </tr>
            {{-- Nama Sub Kegiatan --}}
            <tr>
                <td>10. Nama Sub Kegiatan</td>
                <td style="padding-left:200px">:</td>
                <td>{{ $data_spp->nm_sub_kegiatan }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center">
        <h5 style="margin:2px 0px">Kepada Yth:</h5>
        <h5 style="margin:2px 0px">Pengguna Anggaran/Kuasa Pengguna Anggaran</h5>
        <h5 style="margin:2px 0px">{{ $nama_skpd }}</h5>
        <h5 style="margin:2px 0px">di {{ $daerah->daerah }}</h5>
    </div>

    <div style="text-align: left">
        <h5 style="margin: 2px 0px">Dengan memperhatikan Peraturan Gubernur Kalimantan Barat {{ $nogub }}
            tentang Penjabaran APBD Tahun
            Anggaran {{ $tahun_anggaran }}, bersama ini kami mengajukan Surat Permintaan Pembayaran Langsung Barang dan
            Jasa sebagai berikut:</h5>
    </div>

    <div style="text-align: left">
        <table class="table" style="width: 100%;border:1px black solid">
            {{-- Jumlah Pembayaran Yang Diminta --}}
            <tr>
                <td style="width:400px">a. Jumlah Pembayaran Yang Diminta</td>
                <td>:</td>
                <td>Rp.{{ rupiah($data_spp->nilai) }}</td>
            </tr>
            {{-- Terbilang --}}
            <tr>
                <td style="width:400px;text-align:center">(terbilang)</td>
                <td>:</td>
                <td style="font-style: italic">{{ ucwords(terbilang($data_spp->nilai)) }}</td>
            </tr>
            {{-- Untuk Keperluan --}}
            <tr>
                <td style="width:400px">b. Untuk Keperluan</td>
                <td>:</td>
                <td>{{ $data_spp->keperluan }}</td>
            </tr>
            {{-- Nama Bendahara --}}
            @if ($beban == '6' && $jenis == '6')
                <tr>
                    <td style="width: 400px">c. Nama Pihak Ketiga</td>
                    <td>:</td>
                    <td>{{ $data_spp->nmrekan }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">d. Dasar Bendahara Pengeluaran</td>
                    <td>:</td>
                    <td>{{ $no_spd }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">e. Alamat</td>
                    <td>:</td>
                    <td>{{ $data_spp->alamat }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">f. Nama dan Nomor Rekening</td>
                    <td>:</td>
                    <td>{{ $data_spp->nama_bank_rek }} / {{ $data_spp->no_rek_rek }}</td>
                </tr>
            @else
                <tr>
                    <td style="width: 400px">c. Nama Bendahara</td>
                    <td>:</td>
                    <td>{{ $cari_bendahara->nama }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">d. Dasar Bendahara Pengeluaran</td>
                    <td>:</td>
                    <td>{{ $no_spd }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">e. Alamat</td>
                    <td>:</td>
                    <td>{{ $data_spp->alamat }}</td>
                </tr>
                <tr>
                    <td style="width: 400px">f. Nama dan Nomor Rekening</td>
                    <td>:</td>
                    <td>{{ $nama_bank->nama }} / {{ $data_spp->no_rek }}</td>
                </tr>
            @endif
        </table>
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            @if ($beban == '4' || $sub_kegiatan == '5.02.00.0.06.62')
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
            @elseif ($beban == '2')
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
                @if ($jumlah_spp > 0 || $cari_pptk->jabatan == '')
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
                        <td style="text-align: center">{{ $cari_pptk->nip }}</td>
                        <td style="text-align: center">NIP. {{ $cari_bendahara->nip }}</td>
                    </tr>
                @endif
            @endif
        </table>
    </div>
</body>

</html>
