<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK SP2BP BLUD</title>

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

        table,
        th,
        td {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <table style="width: 100%" id="tabel1" border="1" style="border-collapse:collapse">
        <tr>
            <td style="width: 50%;text-align:center" colspan="2">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td style="width: 50%;text-align:center" colspan="2">
                <b>SURAT PENGESAHAN<br />
                    PENDAPATAN, BELANJA,<br> DAN PEMBIAYAAN BLUD</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border-top:hidden" colspan="2">
                PEMERINTAH PROVINSI KALIMANTAN BARAT
            </td>
            <td style="border-top: hidden">
                Nama BUD / KUASA BUD
            </td>
            <td style="border-left:hidden;border-top:hidden">: {{ $bud->nama }}</td>
        </tr>
        <tr>
            <td style="text-align: center;border-top:hidden" colspan="2">
                <b>BADAN KEUANGAN DAN ASET DAERAH</b>
            </td>
            <td style="border-top: hidden">
                Tanggal
            </td>
            <td style="border-left:hidden;border-top:hidden">
                : {{ tanggal($sp2bp->tgl_sp2bp) }}
            </td>
        </tr>
        <tr>
            <td style="width: 20%;vertical-align:top">
                Nomor
            </td>
            <td style="vertical-align: top;border-left:hidden">
                : {{ $sp2bp->no_sp3b }}
            </td>
            <td style="border-top:hidden;vertical-align:top">
                Nomor
            </td>
            <td style="border-left:hidden;border-top:hidden;vertical-align:top">
                : {{ $sp2bp->no_sp2bp }}
            </td>
        </tr>
        <tr>
            <td style="width: 20%;vertical-align:top;border-top:hidden">
                Tanggal
            </td>
            <td style="vertical-align: top;border-left:hidden;border-top:hidden">
                : {{ tanggal($sp2bp->tgl_sp3b) }}
            </td>
            <td style="border-top:hidden;vertical-align:top;border-top:hidden">
                Tahun Anggaran
            </td>
            <td style="border-left:hidden;border-top:hidden;vertical-align:top">: {{ tahun_anggaran() }}</td>
        </tr>
        <tr>
            <td style="width: 20%;vertical-align:top;border-top:hidden">
                Kode SKPD
            </td>
            <td style="vertical-align: top;border-left:hidden;border-top:hidden">
                : {{ $skpd->kd_skpd }}
            </td>
            <td style="border-top:hidden" colspan="2"></td>
        </tr>
        <tr>
            <td style="width: 20%;vertical-align:top;border-top:hidden">
                Nama SKPD
            </td>
            <td style="vertical-align: top;border-left:hidden;border-top:hidden">
                : {{ nama_skpd($skpd->kd_skpd) }}
            </td>
            <td style="border-top:hidden" colspan="2"></td>
        </tr>
        <tr>
            <td colspan="4">
                Telah disahkan pendapatan dan belanja sejumlah :
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 15px;border-top:hidden">
            </td>
        </tr>
        @php
            $saldo = $saldo_awal == 0 ? $nilai->sal_awal : $saldo_awal;
        @endphp
        <tr>
            <td colspan="2" style="border-top:hidden;border-right:hidden">
                Saldo Awal
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($saldo) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top:hidden;border-right:hidden">
                Pendapatan
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($nilai->pendapatan) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top:hidden;border-right:hidden">
                Belanja
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($nilai->belanja) }}
            </td>
        </tr>
        <tr>
            <td style="border-top:hidden;border-right:hidden" colspan="2">
                Saldo Akhir
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($saldo + ($nilai->pendapatan - $nilai->belanja)) }}
            </td>
        </tr>
        <tr>
            <td style="border-top:hidden;border-right:hidden" colspan="2">
            </td>
            <td colspan="2" style="border-top:hidden">
                ({{ terbilang($saldo + ($nilai->pendapatan - $nilai->belanja)) }})
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 15px">
                Telah disahkan pendapatan dan belanja sejumlah :
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 15px;border-top:hidden">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top:hidden;border-right:hidden">
                Penerimaan Pembiayaan
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($pembiayaan->t_pembiayaan) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top:hidden;border-right:hidden">
                Pengeluaran Pembiayaan
            </td>
            <td colspan="2" style="border-top:hidden">
                : Rp. {{ rupiah($pembiayaan->k_pembiayaan) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-right:hidden"></td>
            <td style="text-align: center" colspan="2">
                {{ $daerah->daerah }}, {{ tanggal($sp2bp->tgl_sp2bp) }} <br>
                KUASA BENDAHARA UMUM DAERAH
                <br>
                <br>
                <br>
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-right:hidden;border-top:hidden"></td>
            <td style="text-align: center;border-top:hidden" colspan="2">
                <b><u>{{ $bud->nama }}</u></b> <br>
                NIP. {{ $bud->nip }}
            </td>
        </tr>
    </table>
</body>

</html>
