<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #rincian td {
            padding: 4px;
        }
    </style>
</head>

<body>
    <div style="font-size: 12px; text-align: center;">
        PEMERINTAH PROVINSI KALIMANTAN BARAT<br />
        PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH<br />
        NOMOR {{ $nospd }}<br />
        TENTANG<br />
        SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH<br />
        TAHUN ANGGARAN {{ tahun_anggaran() }}<br /><br />
        PPKD SELAKU BUD<br />
    </div>
    <table style="margin-top: 16px; padding-left: 40px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; width: 13%;"><b>Menimbang</b></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top; text-align: justify;">Bahwa untuk melaksanakan Anggaran {{ $jenis }} sub kegiatan Tahun Anggaran {{ tahun_anggaran() }} berdasarkan DPA-SKPD dan anggaran kas yang telah ditetapkan, perlu disediakan pendanaan dengan menerbitkan Surat Penyediaan Dana (SPD);</td>
            </tr>
            <tr>
                <td style="vertical-align: top;"><b>Mengingat</b></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">
                    <ol style="margin: 0px;">
                    @for ($tes = 1; $tes <= $total_ingat; $tes++)
                        <li>{{ ${'konfig'}->{'ingat'.$tes} }}</li>
                        @endfor
                        <li>{{ $konfig->ingat_akhir }} {{ $no_dpa->no_dpa }}  Tahun Anggaran {{ tahun_anggaran() }}</li>
                    </ol>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
        <div style="text-align: center;"><strong>M E M U T U S K A N :</strong></div>
        <div>
            <p style="text-indent: 24px;">{{ $konfig->memutuskan }}</p>
        </div>
        <table id="rincian" style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 3%;">1.</td>
                    <td style="width: 35%;">Dasar penyediaan dana:</td>
                    <td style="width: 2%;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>DPA-SKPD</td>
                    <td>:</td>
                    <td>{{ $no_dpa->no_dpa }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Ditujukan kepada SKPD</td>
                    <td>:</td>
                    <td>{{ $data->nm_skpd }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Kepala SKPD</td>
                    <td>:</td>
                    <td>{{ $kepala_skpd->nama }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Jumlah Penyediaan Dana</td>
                    <td>:</td>
                    <td>Rp{{ number_format($data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>({{ terbilang($data->total) }})</i></td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Untuk Kebutuhan</td>
                    <td>:</td>
                    <td>{{ $tambahanbln }} Bulan {{ getMonths()[$data->bulan_awal] }} s/d {{ getMonths()[$data->bulan_akhir] }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Ikhtisar penyediaan dana :</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>a. Jumlah dana DPA-SKPD</td>
                    <td>:</td>
                    <td>Rp{{ number_format($total_anggaran, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>({{ terbilang($total_anggaran) }})</i></td>
                </tr>
                <tr>
                    <td></td>
                    <td>b. Akumulasi SPD sebelumnya</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>c. Jumlah dana yang di-SPD-kan saat ini</td>
                    <td>:</td>
                    <td>Rp{{ number_format($data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>({{ terbilang($data->total) }})</i></td>
                </tr>
                <tr>
                    <td></td>
                    <td>d. Sisa jumlah dana DPA-SKPD yang belum di-SPD-kan</td>
                    <td>:</td>
                    <td>Rp{{ number_format($total_anggaran - $data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>({{ terbilang($total_anggaran - $data->total, 2, ',', '.'    ) }})</i></td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Ketentuan-ketentuan lain</td>
                    <td>:</td>
                    <td>{{ $data->klain }}</td>
                </tr>
            </tbody>
        </table>
        <div>
            <div style="float: right; text-align: center; padding-right: 128px; padding-top: 32px;">
                Ditetapkan di Pontianak<br />
                Pada tanggal {{ tanggal($data->tgl_spd) }}<br />
                PPKD SELAKU BUD,<br />
                <div style="margin-bottom: 48px;"></div>
                <u>{{ $ttd->nama }}</u><br />
                NIP. {{ $ttd->nip }}
                <div style="margin-bottom: 48px;"></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div>
            Tembusan disampaikan kepada:<br />
            1. Inspektur *)<br />
            2. Arsip<br />
            *) Coret yang tidak perlu
        </div>
    </div>
</body>

</html>