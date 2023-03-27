<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK SPB HIBAH</title>

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
                <b>SURAT PENGESAHAN BELANJA (SPB) HIBAH DANA BOS <br><br><br></b>
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
            <td style="border-left:hidden;border-top:hidden">: {{ tanggal($spb->tgl_spb_hibah) }}</td>
        </tr>
        <tr>
            <td style="width: 20%;vertical-align:top">
                KODE REKENING
            </td>
            <td style="vertical-align: top;border-left:hidden">: {{ $spb->kd_rek6 }} - {{ $spb->nm_rek6 }}</td>
            <td style="border-top:hidden;vertical-align:top">
                Nomor
            </td>
            <td style="border-left:hidden;border-top:hidden;vertical-align:top">: {{ $spb->no_spb_hibah }}</td>
        </tr>
        <tr>
            <td style="border-top:hidden" colspan="2"></td>
            <td style="border-top:hidden;vertical-align:top">
                Tahun Anggaran
            </td>
            <td style="border-left:hidden;border-top:hidden;vertical-align:top">: {{ tahun_anggaran() }}</td>
        </tr>
        <tr>
            <td colspan="4">
                Telah disahkan belanja hibah Dana BOS pada Satdikmen Swasta sejumlah : Rp.
                {{ rupiah($nilai_spb->rupiah) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;border-top:hidden">
                ({{ terbilang($nilai_spb->rupiah) }})
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 15px"></td>
        </tr>
        <tr>
            <td colspan="2" style="border-right:hidden"></td>
            <td style="text-align: center" colspan="2">
                {{ $daerah->daerah }}, {{ tanggal($tanggal) }} <br>
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
