<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table,
        tr,
        td,
        th {
            border-collapse: collapse
        }

        .center {
            text-align: center
        }

        .border {
            border: 1px solid black;
        }

        .border1 {
            border-bottom: 1px solid black;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            text-align: justify
        }
    </style>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></h5>
        <h5 style="margin: 2px 0px"><strong>{{ $data_skpd->nm_skpd }}</strong></h5>
        <h5 style="margin: 2px 0px"><strong>TAHUN ANGGARAN {{ $tahun_anggaran }}</strong></h5>
        <div style="clear: both"></div>
    </div>
    <hr>

    <div>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="center">SURAT PERINTAH MEMBAYAR {{ judul_ringkasan($beban) }}</td>
                </tr>
                <tr>
                    @if ($beban == '2')
                        <td class="center">(SPM - GU)</td>
                    @elseif ($beban == '3')
                        <td class="center">(SPM - TU)</td>
                    @elseif ($beban == '4')
                        <td class="center">(SPM - LS {{ strtoupper(judul_ringkasan_ls($no_spm, $beban)) }})</td>
                    @endif
                </tr>
                <tr>
                    <td class="center"><strong>Nomor : {{ $no_spm }}</strong></td>
                </tr>
                <tr>
                    <td class="center"><strong><u>RINGKASAN</u></strong></td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;border:1px solid black">
            <tbody>
                @if (($beban == '6' && $jenis == '6') || (($beban == '5' && $jenis == '1') || $jenis == '2'))
                    <tr>
                        <td colspan="5" class="center border">RINGKASAN KEGIATAN</td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Program</td>
                        <td colspan="2">: {{ $beban6->nm_program }}</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Sub Kegiatan</td>
                        <td colspan="2">: {{ $beban6->nm_sub_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Nomor dan Tanggal DPA/DPPA/DPPAL-OPD</td>
                        <td colspan="2">: {{ $dpa->no_dpa }} / {{ $dpa->tgl_dpa }}</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Nama Perusahaan</td>
                        <td colspan="2">: {{ $beban6->nmrekan }}</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Bentuk Perusahaan</td>
                        <td colspan="2">: {{ Str::substr($beban6->nmrekan, 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Alamat Perusahaan</td>
                        <td colspan="2">: {{ $beban6->alamat }}</td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>Nama Pimpinan Perusahaan</td>
                        <td colspan="2">: {{ $beban6->pimpinan }}</td>
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td>Nama dan Nomor Rekening Bank</td>
                        <td colspan="2">: {{ $beban6->nama_bank }} - {{ $beban6->no_rek }}</td>
                    </tr>
                    <tr>
                        <td>9.</td>
                        <td>Nomor Kontrak</td>
                        <td colspan="2">: {{ $beban6->kontrak }}</td>
                    </tr>
                    <tr>
                        <td>10.</td>
                        <td>Uraian dan Volume Kerja</td>
                        <td colspan="2">: Ya/Bukan</td>
                    </tr>
                    <tr>
                        <td>11.</td>
                        <td>Cara Pembayaran</td>
                        <td colspan="2">: {{ cara_bayar($no_spm) }}</td>
                    </tr>
                    <tr>
                        <td>12.</td>
                        <td>Kegiatan Lanjutan</td>
                        <td colspan="2">: {{ $beban6->lanjut == '1' ? 'Iya' : 'Bukan' }}</td>
                    </tr>
                    <tr>
                        <td>13.</td>
                        <td>Waktu Pelaksanaan Kegiatan</td>
                        <td colspan="2">: {{ tanggal($beban6->tgl_mulai) }} s/d {{ tanggal($beban6->tgl_akhir) }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Deskripsi Pekerjaan</td>
                        <td>
                            <pre>: {{ $beban6->keperluan }}</pre>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td colspan="5" class="center border"><strong>RINGKASAN DPA/DPPA/DPPAL-OPD</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">Jumlah dana DPA/DPPA/DPPAL-OPD</td>
                    <td style="text-align: right">{{ rupiah($nilai_ang->nilai) }}</td>
                    <td style="text-align: right">(I)</td>
                </tr>
                <tr>
                    <td colspan="5" class="center border"><strong>RINGKASAN SPD</strong></td>
                </tr>
                <tr>
                    <td class="center border">No. Urut</td>
                    <td class="center border">Nomor SPD</td>
                    <td class="center border">Tanggal SPD</td>
                    <td class="center border" colspan="2">Jumlah Dana</td>
                </tr>
                @foreach ($data_beban as $data1)
                    <tr>
                        <td class="center border">{{ $loop->iteration }}</td>
                        <td class="border">{{ $data1->no_spd }}</td>
                        <td class="border">{{ tanggal($data1->tgl_spd) }}</td>
                        <td class="border1" style="text-align: right">{{ rupiah($data1->nilai) }}</td>
                        <td class="border1"></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right" class="border"><i>JUMLAH</i></td>
                    <td class="border1" style="text-align: right">{{ rupiah($total_spd) }}</td>
                    <td class="border1" style="text-align: right">(II)</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right" class="border"><i>Sisa dana yang belum di SPD-kan
                            (I-II)</i></td>
                    <td style="text-align: right">{{ rupiah($nilai_ang->nilai - $total_spd) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 20px" colspan="3" class="border"></td>
                    <td class="border" colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="5" class="center border"><strong>RINGKASAN BELANJA</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja UP</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_up($kd_skpd, $beban, $no_spp->no_spp, $tgl_spp->tgl_spp, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja GU</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_gu($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja TU</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_tu($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja LS GAJI</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_lsgaji($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja LS Pengadaan Barang dan Jasa</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_barang($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" class="border">SP2D Belanja LS Pihak Ketiga Lainnya</td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sp2dbelanja_pihak($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right" class="border"><i>JUMLAH</i></td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ total_belanja($kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right"><i>Sisa SPD yang telah diterbitkan, belum
                            dibelanjakan
                            (II-III) </i></td>
                    <td style="text-align: right;border-top:1px solid black;border-left:1px solid black">
                        {{ sisa_spd($total_spd, $kd_skpd, $no_spp->no_spp, $tgl_spp->tgl_spp, $beban, $kd_sub_kegiatan) }}
                    </td>
                    <td class="border1" style="text-align: right">(III)</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="padding-top: 30px">
        <table style="width: 100%">
            <tr>
                <td style="text-align: center">MENGETAHUI :</td>
                <td style="margin: 2px 0px;text-align: center">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ tanggal($tgl_spm->tgl_spm) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $pptk->jabatan }}
                </td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center"><strong><u>{{ $pptk->nama }}</u></strong></td>
                <td style="text-align: center"><strong><u>{{ $bendahara->nama }}</u></strong></td>
            </tr>
            <tr>
                <td style="text-align: center">{{ $pptk->pangkat }}</td>
                <td style="text-align: center">{{ $bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center">NIP. {{ $pptk->nip }}</td>
                <td style="text-align: center">NIP. {{ $bendahara->nip }}</td>
            </tr>
        </table>
    </div>
</body>

</html>