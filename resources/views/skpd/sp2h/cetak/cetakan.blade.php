<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK SPTB</title>

    <style>
        .angka {
            text-align: right
        }
    </style>
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
            <td align="center" style="font-size:14px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px">{{ nama_skpd($skpd->kd_skpd) }}</td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px">{{ $skpd->alamat }}</td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px">PONTIANAK</td>
            <td align="center" style="font-size:14px">Kode Pos : {{ $skpd->kodepos }}</td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>

    <div style="text-align: center">
        <table style="width: 100%">
            <tr>
                <td><u><b>SURAT PERMINTAAN PENGESAHAN BELANJA (SP2H)</b></u></td>
            </tr>
            <tr>
                <td>
                    <b>Tanggal {{ tanggal($sp2h->tgl_sp2h) }} / Nomor {{ $sp2h->no_sp2h }}</b>
                </td>
            </tr>
        </table>
    </div>

    <br>

    <table style="width: 100%">
        <tr>
            <td>Kepala Dinas Pendidikan dan Kebudayaan Provinsi Kalimantan Barat memohon kepada:<br>
                BENDAHARA UMUM DAERAH SELAKU PPKD <br>agar mengesahkan dan membukukan penerimaan dan belanja BOS
                sejumlah;</td>
        </tr>
    </table>

    <br>

    <table style="border-collapse:collapse;font-family: Open Sans;width:100%">
        <tr>
            <td style="width: 30%">1. Saldo Awal</td>
            <td style="width: 5%">: Rp.</td>
            <td class="angka">{{ rupiah($saldo_awal) }}</td>
            <td style="width: 50%"></td>
        </tr>
        <tr>
            <td>2. Penerimaan</td>
            <td>: Rp.</td>
            <td class="angka">{{ rupiah($terima) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>
        @php
            $total_belanja = $belanja_pegawai + $belanja_barang + $belanja_modal;
        @endphp
        <tr>
            <td>3. Belanja</td>
            <td style="border-bottom: 1px solid black">: Rp.</td>
            <td class="angka" style="border-bottom: 1px solid black">
                {{ rupiah($total_belanja) }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="text-indent:15px">a) Belanja Pegawai</td>
            <td>: Rp.</td>
            <td class="angka">{{ rupiah($belanja_pegawai) }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-indent:15px">b) Belanja Barang dan Jasa</td>
            <td>: Rp.</td>
            <td class="angka">{{ rupiah($belanja_barang) }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-indent:15px">c) Belanja Modal</td>
            <td>: Rp.</td>
            <td class="angka">{{ rupiah($belanja_modal) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>
        <tr>
            <td>4. Pengembalian Dana/Penyetoran</td>
            <td>: Rp.</td>
            <td class="angka">{{ rupiah($pengembalian) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>
        <tr>
            <td>5. Saldo Akhir (1+2-3-4)</td>
            <td style="border-top: 1px solid black">: Rp.</td>
            <td class="angka" style="border-top: 1px solid black">
                {{ rupiah($saldo_awal + $terima - ($total_belanja + $pengembalian)) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>
    </table>

    <br>

    <table style="border-collapse:collapse;font-family: Open Sans;width:100%">
        <tr>
            <td colspan="4">Untuk Semester I dan II Tahun Anggaran {{ tahun_anggaran() }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>
        <tr>
            <td colspan="4">Dasar Pengesahan :</td>
        </tr>
        <tr>
            <td style="vertical-align: top;width:30%">- Nomor dan Tgl. DPA-SKPD</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">Nomor {{ $dpa->no_dpa }} <br> Tanggal
                {{ tanggal($dpa->tgl_dpa) }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top">- Nomor dan Tgl. DPPA-SKPD</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">Nomor {{ $dppa->no_dpa }} <br> Tanggal
                {{ tanggal($dppa->tgl_dpa) }}</td>
        </tr>
        <tr>
            @php
                $urusan = substr($skpd->kd_skpd, 0, 1);
            @endphp
            <td style="vertical-align: top">- Urusan Pemerintahan</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $urusan }} - {{ cari_nama($urusan, 'ms_urusan', 'kd_urusan', 'nm_urusan') }}
            </td>
        </tr>
        <tr>
            @php
                $bidang_urusan = substr($skpd->kd_skpd, 0, 4);
            @endphp
            <td style="vertical-align: top">- Bidang Pemerintahan</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $bidang_urusan }} -
                {{ cari_nama($bidang_urusan, 'ms_bidang_urusan', 'kd_bidang_urusan', 'nm_bidang_urusan') }}
            </td>
        </tr>
        <tr>
            @php
                $organisasi = substr($skpd->kd_skpd, 0, 17);
            @endphp
            <td style="vertical-align: top">- Organisasi</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $organisasi }} -
                {{ cari_nama($organisasi, 'ms_organisasi', 'kd_org', 'nm_org') }}
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">- Sub Unit Organisasi</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $skpd->kd_skpd }} - {{ $skpd->nm_skpd }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <hr>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">Program</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $data_program->prog }} -
                {{ cari_nama($data_program->prog, 'ms_program', 'kd_program', 'nm_program') }}
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">Kegiatan</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $data_program->giat }} -
                {{ cari_nama($data_program->giat, 'ms_kegiatan', 'kd_kegiatan', 'nm_kegiatan') }}
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">Sub Kegiatan</td>
            <td style="vertical-align: top">:</td>
            <td colspan="2" style="vertical-align: top">
                {{ $data_program->subgiat }} -
                {{ cari_nama($data_program->subgiat, 'ms_sub_kegiatan', 'kd_sub_kegiatan', 'nm_sub_kegiatan') }}
            </td>
        </tr>
    </table>

    <br>
    <br>

    <table style="border-collapse:collapse;font-family: Open Sans;width:100%" border="1">
        <thead>
            <tr>
                <th colspan="2">PENERIMAAN</th>
                <th colspan="2">BELANJA</th>
            </tr>
            <tr>
                <th>Kode Rekening</th>
                <th>Jumlah</th>
                <th>Kode Rekening</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_terima = 0;
                $total_belanja = 0;
            @endphp
            @foreach ($detail_sp2h as $detail)
                @php
                    $total_terima += $detail->terima;
                    $total_belanja += $detail->belanja;
                @endphp
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center">{{ $detail->kd_rek6 }}</td>
                    <td class="angka">{{ rupiah($detail->belanja) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tr>
            <td class="angka" colspan="2">Jumlah Penerimaan {{ rupiah($total_terima) }}</td>
            <td class="angka" colspan="2">Jumlah Belanja {{ rupiah($total_belanja) }}</td>
        </tr>
    </table>

    <br>
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="width: 50%"></td>
                <td style="margin: 2px 0px;text-align: center">
                    {{ $daerah->daerah }},{{ tanggal($sp2h->tgl_sp2h) }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $pa_kpa->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td style="text-align: center">
                    <b><u>{{ $pa_kpa->nama }}</u></b> <br>
                    {{ $pa_kpa->pangkat }} <br>
                    NIP. {{ $pa_kpa->nip }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
