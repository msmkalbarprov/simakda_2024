<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KELENGKAPAN SP2D</title>
    <style>
        table {
            width: 100%
        }

        table,
        td,
        th {
            border-collapse: collapse;
            font-family: 'Open Sans', Helvetica, Arial, sans-serif;
            vertical-align: top
        }

        .a1 {
            padding-left: 20px;
        }

        .a2 {
            padding-left: 30px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="text-align: center">
                <b>
                    @if ($beban == '1')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-UP
                    @elseif ($beban == '2')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-GU
                    @elseif ($beban == '3')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-TU
                    @elseif ($beban == '4')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-LS <br>
                        @if ($jenis == '1')
                            (Untuk Gaji Induk, Gaji Terusan, Kekurangan Gaji)
                        @elseif ($jenis == '3')
                            (Untuk Tambahan Penghasilan)
                        @elseif ($jenis == '5')
                            (Untuk Honorarium Tenaga Kontrak)
                        @elseif ($jenis == '6')
                            (Untuk Pengadaan Barang dan Jasa/Konstruksi/Konsultansi)
                        @elseif ($jenis == '7')
                            (Untuk Pengadaan Konsumsi)
                        @elseif ($jenis == '8')
                            (Untuk Sewa Rumah Jabatan/Gedung untuk Kantor/G
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            (Untuk Pengadaan Sertifikat Tanah)
                        @elseif ($jenis == '10')
                            (Untuk Pengadaan Tanah)
                        @elseif ($jenis == '11')
                            (Untuk Hibah Barang dan Jasa pada Pihak Ketiga)
                        @elseif ($jenis == '12')
                            (Untuk LS Bantuan Sosial pada Pihak Ketiga)
                        @elseif ($jenis == '13')
                            (Untuk Hibah Uang Pada Pihak Ketiga)
                        @elseif ($jenis == '14')
                            (Untuk Bantuan Keuangan Pada Kabupaten/Kota)
                        @elseif ($jenis == '15')
                            (Untuk Bagi Hasil Pajak dan Bukan Pajak)
                        @elseif ($jenis == '16')
                            (Untuk Hibah Konstruksi Pada Pihak Ketiga)
                        @else
                        @endif
                    @elseif ($beban == '5')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-LS <br>
                        @if ($jenis == '1')
                            (Untuk Gaji Induk, Gaji Terusan, Kekurangan Gaji)
                        @elseif ($jenis == '3')
                            (Untuk Tambahan Penghasilan)
                        @elseif ($jenis == '4')
                            (Untuk Honorarium PNS)
                        @elseif ($jenis == '5')
                            (Untuk Honorarium Tenaga Kontrak)
                        @elseif ($jenis == '6')
                            (Untuk Pengadaan Barang dan Jasa/Konstruksi/Konsultansi)
                        @elseif ($jenis == '7')
                            (Untuk Pengadaan Konsumsi)
                        @elseif ($jenis == '8')
                            (Untuk Sewa Rumah Jabatan/Gedung untuk Kantor/G
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            (Untuk Pengadaan Sertifikat Tanah)
                        @elseif ($jenis == '10')
                            (Untuk Pengadaan Tanah)
                        @elseif ($jenis == '11')
                            (Untuk Hibah Barang dan Jasa pada Pihak Ketiga)
                        @elseif ($jenis == '12')
                            (Untuk LS Bantuan Sosial pada Pihak Ketiga)
                        @elseif ($jenis == '13')
                            (Untuk Hibah Uang Pada Pihak Ketiga)
                        @elseif ($jenis == '14')
                            (Untuk Bantuan Keuangan Pada Kabupaten/Kota)
                        @elseif ($jenis == '15')
                            (Untuk Bagi Hasil Pajak dan Bukan Pajak)
                        @elseif ($jenis == '16')
                            (Untuk Hibah Konstruksi Pada Pihak Ketiga)
                        @elseif ($jenis == '98')
                            (Untuk Pengadaan Barang dan Jasa/Konstruksi/Konsultansi)
                        @elseif ($jenis == '99')
                            (Untuk Pengeluaran Pembiayaan)
                        @endif
                    @elseif ($beban == '6')
                        Verifikasi Kelengkapan Dokumen Penerbitan SP2D-LS <br>
                        @if ($jenis == '1')
                            (Untuk Gaji Induk, Gaji Terusan, Kekurangan Gaji)
                        @elseif ($jenis == '3')
                            (Untuk Tambahan Penghasilan)
                        @elseif ($jenis == '4')
                            (Untuk Honorarium PNS)
                        @elseif ($jenis == '5')
                            (Untuk Honorarium Tenaga Kontrak)
                        @elseif ($jenis == '6')
                            Barang dan Jasa termasuk Hibah / Bantuan Sosial Dalam Bentuk Barang Dan Jasa
                        @elseif ($jenis == '7')
                            (Untuk Pengadaan Konsumsi)
                        @elseif ($jenis == '8')
                            (Untuk Sewa Rumah Jabatan/Gedung untuk Kantor/G
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            (Untuk Pengadaan Sertifikat Tanah)
                        @elseif ($jenis == '10')
                            (Untuk Pengadaan Tanah)
                        @elseif ($jenis == '11')
                            (Untuk Hibah Barang dan Jasa pada Pihak Ketiga)
                        @elseif ($jenis == '12')
                            (Untuk LS Bantuan Sosial pada Pihak Ketiga)
                        @elseif ($jenis == '13')
                            (Untuk Hibah Uang Pada Pihak Ketiga)
                        @elseif ($jenis == '14')
                            (Untuk Bantuan Keuangan Pada Kabupaten/Kota)
                        @elseif ($jenis == '15')
                            (Untuk Bagi Hasil Pajak dan Bukan Pajak)
                        @elseif ($jenis == '16')
                            (Untuk Hibah Konstruksi Pada Pihak Ketiga)
                        @elseif ($jenis == '98')
                            (Belanja Operasional KDH/WKDH dan Pimpinan DPRD)
                        @elseif ($jenis == '99')
                            (Untuk Honorarium PNS)
                        @endif
                    @else
                    @endif
                </b>
            </td>
        </tr>
    </table>

    <br>
    <br>

    <table>
        <tbody>
            <tr>
                <td>SKPD/SATEKER</td>
                <td>:</td>
                <td>{{ $sp2d->nm_skpd }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>PROVINSI KALIMANTAN BARAT</td>
            </tr>
            <tr>
                <td colspan="3">A. PENERIMAAN
                    @if (in_array($beban, ['1']))
                        SPM
                    @elseif ($beban == '2')
                        SPM
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif ($beban == '4')
                        SPM-LS Gaji dan Tunjangan
                    @elseif ($beban == '5')
                        SPM-LS
                    @elseif ($beban == '6')
                        @if ($jenis == '1')
                            SPM-LS Gaji Induk, Gaji Terusan, Kekurangan Gaji
                        @elseif ($jenis == '3')
                            SPM-LS Tambahan Penghasilan
                        @elseif ($jenis == '4')
                            SPM-LS Honorarium PNS
                        @elseif ($jenis == '5')
                            SPM-LS Honorarium Tenaga Kontrak
                        @elseif ($jenis == '6')
                            SPM-LS Barang dan Jasa termasuk Hibah / Bantuan Sosial Dalam Bentuk Barang Dan Jasa
                        @elseif ($jenis == '7')
                            SPM-LS Pengadaan Konsumsi
                        @elseif ($jenis == '8')
                            SPM-LS Sewa Rumah Jabatan/Gedung untuk Kantor/
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            SPM-LS Pengadaan Sertifikat Tanah
                        @elseif ($jenis == '10')
                            SPM-LS Pengadaan Tanah
                        @elseif ($jenis == '11')
                            SPM-LS Hibah Barang dan Jasa pada Pihak Ketiga
                        @elseif ($jenis == '12')
                            SPM-LS LS Bantuan Sosial pada Pihak Ketiga
                        @elseif ($jenis == '13')
                            SPM-LS Hibah Uang Pada Pihak Ketiga
                        @elseif ($jenis == '14')
                            SPM-LS Bantuan Keuangan Pada Kabupaten/Kota
                        @elseif ($jenis == '15')
                            SPM-LS Bagi Hasil Pajak dan Bukan Pajak
                        @elseif ($jenis == '16')
                            SPM-LS Hibah Konstruksi Pada Pihak Ketiga
                        @elseif ($jenis == '98')
                            (Belanja Operasional KDH/WKDH dan Pimpinan DPRD)
                        @elseif ($jenis == '99')
                            SPM-LS Honorarium PNS
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">1. Nomor dan Tanggal
                    @if (in_array($beban, ['1']))
                        SPM
                    @elseif ($beban == '2')
                        SPM-GU
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif (in_array($beban, ['5', '6', '4']))
                        SPM-LS
                    @endif
                </td>
                <td>:</td>
                <td>{{ $sp2d->no_spm }} dan {{ tanggal($sp2d->tgl_spm) }}</td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">2. Tanggal Terima
                    @if (in_array($beban, ['1']))
                        SPM
                    @elseif ($beban == '2')
                        SPM-GU
                    @elseif ($beban == '3')
                        SPM-TU
                    @elseif (in_array($beban, ['5', '6', '4']))
                        SPM-LS
                    @endif
                </td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top:20px">
                    B. Kelengkapan dan persyaratan
                    @if ($beban == '1')
                        SP2D-UP
                    @elseif ($beban == '2')
                        SP2D-GU
                    @elseif ($beban == '3')
                        SP2D-TU
                    @elseif ($beban == '4')
                        SP2D-LS Gaji dan Tunjangan
                    @elseif ($beban == '5')
                        SP2D-LS
                        @if ($jenis == '1')
                            Gaji Induk, Gaji Terusan, Kekurangan Gaji
                        @elseif ($jenis == '3')
                            Tambahan Penghasilan
                        @elseif ($jenis == '4')
                            Honorarium PNS
                        @elseif ($jenis == '5')
                            Honorarium Tenaga Kontrak
                        @elseif ($jenis == '6')
                            Pengadaan Barang dan Jasa/Konstruksi/Konsultansi
                        @elseif ($jenis == '7')
                            Pengadaan Konsumsi
                        @elseif ($jenis == '8')
                            Sewa Rumah Jabatan/Gedung untuk Kantor/
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            Pengadaan Sertifikat Tanah
                        @elseif ($jenis == '10')
                            Pengadaan Tanah
                        @elseif ($jenis == '11')
                            Hibah Barang dan Jasa pada Pihak Ketiga
                        @elseif ($jenis == '12')
                            LS Bantuan Sosial pada Pihak Ketiga
                        @elseif ($jenis == '13')
                            Hibah Uang Pada Pihak Ketiga
                        @elseif ($jenis == '14')
                            Bantuan Keuangan Pada Kabupaten/Kota
                        @elseif ($jenis == '15')
                            Bagi Hasil Pajak dan Bukan Pajak
                        @elseif ($jenis == '16')
                            Hibah Konstruksi Pada Pihak Ketiga
                        @elseif ($jenis == '98')
                            Pengadaan Barang dan Jasa/Konstruksi/Konsultansi
                        @elseif ($jenis == '99')
                            Pengeluaran Pembiayaan
                        @endif
                    @elseif ($beban == '6')
                        SP2D-LS
                        @if ($jenis == '1')
                            Gaji Induk, Gaji Terusan, Kekurangan Gaji
                        @elseif ($jenis == '3')
                            Tambahan Penghasilan
                        @elseif ($jenis == '4')
                            Honorarium PNS
                        @elseif ($jenis == '5')
                            Honorarium Tenaga Kontrak
                        @elseif ($jenis == '6')
                            Barang dan Jasa termasuk Hibah / Bantuan Sosial Dalam Bentuk Barang Dan Jasa
                        @elseif ($jenis == '7')
                            Pengadaan Konsumsi
                        @elseif ($jenis == '8')
                            Sewa Rumah Jabatan/Gedung untuk Kantor/
                            Pertemuan/Tempat
                            Pertemuan/Tempat Penginapan/Kendaraan)</h3>
                        @elseif ($jenis == '9')
                            Pengadaan Sertifikat Tanah
                        @elseif ($jenis == '10')
                            Pengadaan Tanah
                        @elseif ($jenis == '11')
                            Hibah Barang dan Jasa pada Pihak Ketiga
                        @elseif ($jenis == '12')
                            LS Bantuan Sosial pada Pihak Ketiga
                        @elseif ($jenis == '13')
                            Hibah Uang Pada Pihak Ketiga
                        @elseif ($jenis == '14')
                            Bantuan Keuangan Pada Kabupaten/Kota
                        @elseif ($jenis == '15')
                            Bagi Hasil Pajak dan Bukan Pajak
                        @elseif ($jenis == '16')
                            Hibah Konstruksi Pada Pihak Ketiga
                        @elseif ($jenis == '98')
                            Belanja Operasional KDH/WKDH dan Pimpinan DPRD
                        @elseif ($jenis == '99')
                            Honorarium PNS
                        @endif
                    @else
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <th></th>
            <th style="font-weight: normal">Ada</th>
            <th style="font-weight: normal">Tidak</th>
        </thead>
        <tbody>
            @include('penatausahaan.pengeluaran.sp2d.cetak.kelengkapan_persyaratan_baru')
        </tbody>
    </table>

    <br>
    <br>

    {{-- <table>
        <tbody>
            <tr>
                <td class="a1" style="width: 40%">3. Tanggal Pengembalian SPM</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td class="a1" style="width: 40%">4. Tanggal Terima Kembali SPM</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
        </tbody>
    </table> --}}

    <table>
        <tbody>
            <tr>
                <td>C. Tanggal pengembalian hasil koreksi</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td style="padding-left: 24px">Keterangan</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
            <tr>
                <td>D. Tanggal terima kembali</td>
                <td>:</td>
                <td>........................................................................</td>
            </tr>
        </tbody>
    </table>

    <br><br><br>

    <table class="table" style="width: 100%">
        <tr>
            <td style="text-align: center">Diperiksa dan diteruskan oleh:</td>
            <td style="margin: 2px 0px;text-align: center">
                Diverifikasi / dikerjakan oleh:
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 50px;text-align: center">
                {{ $ttd1->jabatan }}
            </td>
            <td style="padding-bottom: 50px;text-align: center">
                {{-- {{ $ttd2->jabatan }} --}} Petugas Pelaksana
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <u><b>{{ $ttd1->nama }}</b></u> <br>
                {{ $ttd1->pangkat }} <br>
                NIP. {{ $ttd1->nip }}
            </td>
            <td style="text-align: center">
                <u><b>{{ $ttd2->nama }}</b></u> <br>
                {{ $ttd2->pangkat }} <br>
                NIP. {{ $ttd2->nip }}
            </td>
        </tr>
    </table>
</body>

</html>
