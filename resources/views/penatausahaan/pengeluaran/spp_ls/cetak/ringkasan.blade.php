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

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
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
            <h5 style="margin: 2px 0px">(SPP - {{ strtoupper($lcbeban) }})</h5>
        @elseif ($beban == '5')
            <h5 style="margin: 2px 0px">SURAT PERNYATAAN PENGAJUAN SPP - {{ strtoupper($lcbeban) }}</h5>
            <h5 style="margin: 2px 0px">(SPP - {{ strtoupper($lcbeban) }})</h5>
        @else
            <h5 style="margin: 2px 0px">SURAT PERMINTAAN PEMBAYARAN LANGSUNG BARANG DAN JASA</h5>
            <h5 style="margin: 2px 0px">(SPP - {{ strtoupper($lcbeban) }})</h5>
        @endif
        <h5 style="margin: 2px 0px">Nomor : {{ $no_spp }}</h5>
        <h5 style="margin: 2px 0px;text-decoration:underline"><b>RINGKASAN</b></h5>
    </div>
    <div>
        @if ($beban == '4')
            <table class="table table-striped" style="width: 100%" border="1">
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN DPA/DPPA/DPPAL-OPD</td>
                </tr>
                <tr>
                    <td colspan="3">Jumlah dana DPA/DPPA/DPPAL-OPD</td>
                    <td style="text-align: right">{{ rupiah($data_nilai->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN SPD</td>
                </tr>
                <tr>
                    <td style="text-align: center">No. Urut</td>
                    <td style="text-align: center">Nomor SPD</td>
                    <td style="text-align: center">Tanggal SPD</td>
                    <td style="text-align: center">Jumlah Dana</td>
                </tr>
                @foreach ($result as $data)
                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ $data->no_spd }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->tgl_spd)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        <td style="text-align: right">{{ rupiah($data->nilai) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalspd) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">Sisa dana yang belum di SPD-kan</td>
                    <td style="text-align: right">{{ rupiah($blmspd) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN BELANJA</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja UP/GU</td>
                    <td style="text-align: right">{{ rupiah($nilai4->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja TU</td>
                    <td style="text-align: right">{{ rupiah($nilai5->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pembayaran Gaji dan Tunjangan</td>
                    <td style="text-align: right">{{ rupiah($nilai1->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pengadaan Barang dan Jasa</td>
                    <td style="text-align: right">{{ rupiah($nilai3->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pihak Ketiga Lainnya</td>
                    <td style="text-align: right">{{ rupiah($nilai2->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;font-style:italic">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalbelanja) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style:italic">Sisa SPD yang telah, belum dibelanjakan</td>
                    <td style="text-align: right">{{ rupiah($sisaspp) }}</td>
                </tr>
            </table>
        @elseif ($beban == '5')
            @if ($jenis == '1' || $jenis == '2')
                <table class="table table-striped" style="width: 100%;border:1px black solid">
                    <tr>
                        <td colspan="3" style="text-align: center;border-bottom:1px black solid">RINGKASAN KEGIATAN
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 400px">1. Program</td>
                        <td>:</td>
                        <td>{{ $data_spp->nm_program }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">2. Kegiatan</td>
                        <td>:</td>
                        <td>{{ $data_spp->nm_sub_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">3. Nomor dan Tanggal DPA/DPPA/DPPAL-OPD</td>
                        <td>:</td>
                        <td>{{ $data_dpa->no_dpa }} -
                            {{ \Carbon\Carbon::parse($data_dpa->tgl_dpa)->locale('id')->isoFormat('DD MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">4. Nama Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->nmrekan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">5. Bentuk Perusahaan</td>
                        <td>:</td>
                        <td>{{ substr($data_spp->nmrekan, 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">6. Alamat Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->alamat }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">7. Nama Pimpinan Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->pimpinan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">8. Nama dan Nomor Rekening Bank</td>
                        <td>:</td>
                        <td>{{ $data_spp->nama_bank }} - {{ $data_spp->no_rek }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">9. Nomor Kontrak</td>
                        <td>:</td>
                        <td>{{ $data_spp->kontrak }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">10. Kegiatan Lanjutan</td>
                        <td>:</td>
                        <td>{{ $data_spp->lanjut == '1' ? 'Iya' : 'Bukan' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">11. Waktu Pelaksanaan Kegiatan</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data_spp->tgl_mulai)->locale('id')->isoFormat('DD MMMM Y') }} s/d
                            {{ \Carbon\Carbon::parse($data_spp->tgl_akhir)->locale('id')->isoFormat('DD MMMM Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 400px">12. Deskripsi Pekerjaan</td>
                        <td>:</td>
                        <td>
                            <pre>{{ $data_spp->keperluan }}</pre>
                        </td>
                    </tr>
                </table>
            @endif
            <table class="table table-striped" style="width: 100%" border="1">
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN DPA/DPPA/DPPAL-OPD</td>
                </tr>
                <tr>
                    <td colspan="3">Jumlah dana DPA/DPPA/DPPAL-OPD</td>
                    <td style="text-align: right">{{ rupiah($data_nilai->nilai) }} (I)</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN SPD</td>
                </tr>
                <tr>
                    <td style="text-align: center">No. Urut</td>
                    <td style="text-align: center">Nomor SPD</td>
                    <td style="text-align: center">Tanggal SPD</td>
                    <td style="text-align: center">Jumlah Dana</td>
                </tr>
                @foreach ($result as $data)
                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ $data->no_spd }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->tgl_spd)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        <td style="text-align: right">{{ rupiah($data->nilai) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalspd) }} (II)</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">Sisa dana yang belum di SPD-kan
                        (I-II)
                    </td>
                    <td style="text-align: right">{{ rupiah($blmspd) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN BELANJA</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja UP/GU</td>
                    <td style="text-align: right">{{ rupiah($nilai3->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja TU</td>
                    <td style="text-align: right">{{ rupiah($nilai4->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Gaji</td>
                    <td style="text-align: right">{{ rupiah($nilai5->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pengadaan Barang dan Jasa</td>
                    <td style="text-align: right">{{ rupiah($nilai1->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pihak Ketiga Lainnya</td>
                    <td style="text-align: right">{{ rupiah($nilai2->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;font-style:italic">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalbelanja) }} (III)</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style:italic;text-align:right">Sisa SPD yang telah, belum
                        dibelanjakan (II-III)
                    </td>
                    <td style="text-align: right">{{ rupiah($sisaspp) }}</td>
                </tr>
            </table>
        @elseif ($beban == '6')
            @if ($jenis == '6')
                <table class="table table-striped" style="width: 100%;border:1px black solid">
                    <tr>
                        <td colspan="3" style="text-align: center;border-bottom:1px black solid">RINGKASAN KEGIATAN
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 400px">1. Program</td>
                        <td>:</td>
                        <td>{{ $data_spp->nm_program }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">2. Kegiatan</td>
                        <td>:</td>
                        <td>{{ $data_spp->nm_sub_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">3. Nomor dan Tanggal DPA/DPPA/DPPAL-OPD</td>
                        <td>:</td>
                        <td>{{ $data_dpa->no_dpa }} -
                            {{ \Carbon\Carbon::parse($data_dpa->tgl_dpa)->locale('id')->isoFormat('DD MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">4. Nama Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->nmrekan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">5. Bentuk Perusahaan</td>
                        <td>:</td>
                        <td>{{ substr($data_spp->nmrekan, 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">6. Alamat Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->alamat }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">7. Nama Pimpinan Perusahaan</td>
                        <td>:</td>
                        <td>{{ $data_spp->pimpinan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">8. Nama dan Nomor Rekening Bank</td>
                        <td>:</td>
                        <td>{{ $data_spp->nama_bank }} - {{ $data_spp->no_rek }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">9. Nomor Kontrak</td>
                        <td>:</td>
                        <td>{{ $data_spp->kontrak }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">10. Kegiatan Lanjutan</td>
                        <td>:</td>
                        <td>{{ $data_spp->lanjut == '1' ? 'Iya' : 'Bukan' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 400px">11. Waktu Pelaksanaan Kegiatan</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data_spp->tgl_mulai)->locale('id')->isoFormat('DD MMMM Y') }} s/d
                            {{ \Carbon\Carbon::parse($data_spp->tgl_akhir)->locale('id')->isoFormat('DD MMMM Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 400px">12. Deskripsi Pekerjaan</td>
                        <td>:</td>
                        <td>
                            <pre>{{ $data_spp->keperluan }}</pre>
                        </td>
                    </tr>
                </table>
            @endif
            <table class="table table-striped" style="width: 100%" border="1">
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN DPA/DPPA/DPPAL-OPD</td>
                </tr>
                <tr>
                    <td colspan="3">Jumlah dana DPA/DPPA/DPPAL-OPD</td>
                    <td style="text-align: right">{{ rupiah($data_nilai->nilai) }} (I)</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN SPD</td>
                </tr>
                <tr>
                    <td style="text-align: center">No. Urut</td>
                    <td style="text-align: center">Nomor SPD</td>
                    <td style="text-align: center">Tanggal SPD</td>
                    <td style="text-align: center">Jumlah Dana</td>
                </tr>
                @foreach ($result as $data)
                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ $data->no_spd }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->tgl_spd)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        <td style="text-align: right">{{ rupiah($data->nilai) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalspd) }} (II)</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style: italic;text-align:right">Sisa dana yang belum di SPD-kan
                        (I-II)
                    </td>
                    <td style="text-align: right">{{ rupiah($blmspd) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">RINGKASAN BELANJA</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja UP/GU</td>
                    <td style="text-align: right">{{ rupiah($nilai2->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja TU</td>
                    <td style="text-align: right">{{ rupiah($nilai3->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Gaji</td>
                    <td style="text-align: right">{{ rupiah($nilai5->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pengadaan Barang dan Jasa</td>
                    <td style="text-align: right">{{ rupiah($nilai1->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Belanja LS Pihak Ketiga Lainnya</td>
                    <td style="text-align: right">{{ rupiah($nilai4->nilai) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;font-style:italic">JUMLAH</td>
                    <td style="text-align: right">{{ rupiah($totalbelanja) }} (III)</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-style:italic;text-align:right">Sisa SPD yang telah, belum
                        dibelanjakan (II-III)
                    </td>
                    <td style="text-align: right">{{ rupiah($sisaspp) }}</td>
                </tr>
            </table>
        @endif
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width:100%">
            @if ($beban == '4')
                <tr>
                    <td style="margin: 2px 0px;text-align: center;padding-left:500px">
                        {{ $daerah->daerah }},
                        @if ($tanpa == 1)
                            ______________{{ $tahun_anggaran }}
                        @else
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('DD MMMM Y') }}
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
            @elseif ($beban == '6')
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
        </table>
    </div>
</body>

</html>