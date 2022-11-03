<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #rincian>tbody>tr>td {
            vertical-align: top
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: 1px solid black
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
            <img src="{{asset('template/assets/images/'.$daerah->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td></tr>
            <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>{{ strtoupper($daerah->nm_pemda) }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>SKPD {{ $skpd->nm_skpd }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>&nbsp;</strong></td></tr>
            </table>
    <hr>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: center"><b>BUKU KAS UMUM PENGELUARAN</b></td>
        </tr>
        <tr>
            <td style="text-align: center;padding-bottom:30px"><b>PERIODE {{ strtoupper(bulan($bulan)) }}</b></td>
        </tr>
    </table>
    <table style="width: 100%;margin-top:10px" border="1" id="rincian">
        <thead>
            <td align="center" bgcolor="#CCCCCC" width="3%" style="font-size:12px;font-weight:bold;">No</td>
            <td align="center" bgcolor="#CCCCCC" width="10%" style="font-size:12px;font-weight:bold">Tanggal</td>
            <td align="center" bgcolor="#CCCCCC" colspan="10" width="10%" style="font-size:12px;font-weight:bold">No. Bukti</td>
            <td align="center" bgcolor="#CCCCCC"  width="22%" style="font-size:12px;font-weight:bold">Uraian</td>
            <td align="center" bgcolor="#CCCCCC" width="13%" style="font-size:12px;font-weight:bold">Penerimaan</td>
            <td align="center" bgcolor="#CCCCCC" width="13%" style="font-size:12px;font-weight:bold">Pengeluaran</td>
            <td align="center" bgcolor="#CCCCCC" width="13%" style="font-size:12px;font-weight:bold">Saldo</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">1</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">2</td>
            <td align="center" bgcolor="#CCCCCC" colspan="10" style="font-size:12px;border-top:solid 1px black">3</td>
            <td align="center" bgcolor="#CCCCCC"  style="font-size:12px;border-top:solid 1px black">4</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">5</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">6</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">7</td>
        </tr>

        </thead>
        <tbody>
            
                <tr>
                    <tr><td valign="top" width="5%" align="center" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black"></td>
                    <td valign="top" width="10%" align="center" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black"></td>
                    <td valign="top"  width="13%" colspan="10" align="center" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black"></td>
                    <td valign="top"  width="20%"  align="left" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black">Saldo Lalu</td>
                    <td valign="top"  width="13%" align="right" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black"></td>
                    <td valign="top"  width="13%" align="right" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black"></td>
                    <td valign="top"  width="13%" align="right" style="font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black">{{ rupiah($data_sawal->terima-$data_sawal->keluar+$data_tahun_lalu->sld_awalpajak+$data_tahun_lalu->nilai) }}</td></tr>
                </tr>
            
        </tbody>
    </table>
    <table style="border-collapse:collapse; border-color: black;font-family:Open Sans" width="100%" align="center" border="1" cellspacing="1" cellpadding="1" >
        <tr>
            <td colspan="14" valign="top" align="left" style="font-size:12px;border: solid 1px white;">Saldo Kas di Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu bulan $lcperiode2 </td>
            <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"></td>
            <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"></td>
        </tr>

        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;">Rp" . number_format((($trh1->jmterima + $lcterima + $lcterima_pajak) - ($trh1->jmkeluar + $lckeluar + $lckeluar_pajak) + $tox + $sld_apjk), "2", ",", ".") . " </td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>

        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;"><i>(Terbilang : $terbilangsaldo)</i></td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>

        <tr>
        <td colspan="2" valign="top" align="left" style="font-size:12px;border: solid 1px white;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
        <td colspan ="14"valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;"></td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;"></td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
        <td valign="top" align="right" style="font-size:12px;border: solid 1px white;"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="12" valign="top" align="left" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        <td valign="top" align="center" style="font-size:12px;border: solid 1px white;">&nbsp;</td>
        </tr>
        <tr></table>
    <table style="width: 100%">
        <tbody>
            {{-- <tr>
                <td colspan="2" style="padding-top: 10px" class="kanan kiri">Saldo Sampai Dengan Tanggal
                    {{ tanggal($tgl_voucher) }},</td>
            </tr>
            <tr>
                <td class="kiri" style="width: 10%">- Saldo Bank</td>
                <td class="kanan">: Rp. {{ rupiah($bank->terima - $bank->keluar) }}</td>
            </tr>
            <tr>
                <td class="kiri" style="width: 10%">- Jumlah Terima</td>
                <td class="kanan">: Rp. {{ rupiah($total_terima) }}</td>
            </tr>
            <tr>
                <td class="kiri bawah" style="width: 10%">- Jumlah Keluar</td>
                <td class="kanan bawah">: Rp. {{ rupiah($total_keluar) }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px" class="kanan kiri">Perkiraan Akhir Saldo,</td>
            </tr>
            <tr>
                <td class="kiri bawah" style="width: 10%">- Saldo Bank</td>
                <td class="kanan bawah">: Rp.
                    {{ rupiah($bank->terima - $bank->keluar + $total_terima - $total_keluar) }}</td>
            </tr> --}}
        </tbody>
    </table>
</body>

</html>
