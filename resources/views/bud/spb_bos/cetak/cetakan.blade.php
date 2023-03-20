<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK SPB</title>

    <style>
        .angka {
            text-align: right
        }

        #rincian_spb>tbody>tr>td,
        #rincian_belanja>tbody>tr>td,
        #ttd>tbody>tr>td {
            width: 25%;
            font-size: 14px
        }

        #rincian_spb tr td:first-child,
        #rincian_belanja tr td:first-child,
        #ttd tr td:first-child {
            border-left: 1px solid black
        }

        #rincian_spb tr td:last-child,
        #rincian_belanja tr td:last-child,
        #ttd tr td:last-child {
            border-right: 1px solid black
        }

        #rincian_spb tr td:nth-child(3) {
            border-left: 1px solid black
        }

        #rincian_spb,
        #rincian_belanja,
        #ttd {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%" style="border:1px solid black;border-right:hidden">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:14px;border:1px solid black;border-bottom:hidden" width="93%">&nbsp;
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px;border:1px solid black;border-bottom:hidden" width="93%">
                <strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px;border-right:1px solid black"><b>BADAN KEUANGAN DAN ASET DAERAH</b>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px">{{ $skpd->alamat }}</td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px;border:1px solid black;border-top:hidden"><strong>&nbsp;</strong>
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px;width:100%" id="rincian_spb">
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: center" colspan="2"><b>SURAT PENGESAHAN BELANJA (SPB)</b></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: center" colspan="2"><b>{{ $skpd->nm_skpd }}</b></td>
        </tr>
        <tr>
            <td style="width: 25%;height:20px"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Nomor SP2B {{ ucwords(strtolower($skpd->nm_skpd)) }}</td>
            <td>: {{ $spb->no_sp2b }}</td>
            <td>Nama BUD / KUASA BUD</td>
            <td>: {{ $bud->nama }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ tanggal($spb->tgl_sp2b) }}</td>
            <td>Tanggal</td>
            <td>: {{ tanggal($spb->tgl_spb) }}</td>
        </tr>
        <tr>
            <td>Kode Organisasi</td>
            <td>: {{ Str::substr($skpd->kd_skpd, 0, 17) }}</td>
            <td>Nomor</td>
            <td>: {{ $spb->no_spb }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Tahun Anggaran</td>
            <td>: {{ tahun_anggaran() }}</td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px;width:100%" id="rincian_belanja">
        <tr>
            <td>Telah disahkan belanja sejumlah :</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Belanja</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Belanja Pegawai</td>
            <td style="text-align: right">Rp {{ rupiah($belanja_pegawai) }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Belanja Barang dan Jasa</td>
            <td style="text-align: right">Rp {{ rupiah($belanja_barang) }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Belanja Modal</td>
            <td style="text-align: right">Rp {{ rupiah($belanja_modal) }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Pengembalian Dana</td>
            <td style="text-align: right">Rp {{ rupiah($kembali) }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Saldo Akhir</td>
            <td style="text-align: right">Rp
                {{ rupiah($belanja_pegawai + $belanja_barang + $belanja_modal + $kembali) }}</td>
            <td></td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px;width:100%" id="ttd">
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: center;width:50%" colspan="2">{{ $daerah->daerah }},
                {{ tanggal($spb->tgl_spb) }} <br>
                KUASA BENDAHARA UMUM DAERAH</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: center;height:80px" colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: center;width:50%" colspan="2"><b><u>{{ $bud->nama }}</u></b> <br>
                {{ $bud->pangkat }} <br> NIP. {{ $bud->nip }}</td>
        </tr>
    </table>
</body>

</html>
