<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.6 PIUTANG</title>
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

<body >
{{-- <body> --}}
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD  width="70%" valign="top" align="right" > Lampiran II.1 : </TD>
            <TD width="30%"  align="left" >Daftar Piutang Daerah
                <br>Nomor : 
                <br>Tanggal: 
            </TD>
        </TR>
    </TABLE>
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="21" align="center" >DAFTAR PIUTANG DAERAH <BR> TAHUN ANGGARAN {{tahun_anggaran()}}</TD>
        </TR>
    </TABLE>

    
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
                <td width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">No.</td>
                <td width="30%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Uraian Rincian Piutang</td>
                <td width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Tahun Pengakuan Piutang</td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Saldo Awal Piutang</td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Penambahan Piutang</td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Pengurangan Piutang</td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Saldo Akhir Piutang</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td> 
            </tr>
        </thead>
        @php
            $jum_real_sedia = 0;
            $jum_real_tetap = 0;
            $jum_real_lain = 0;
            $total = 0;
        	$tot_sal_awal = 0;
            $tot_penambahan = 0;
            $tot_pengurangan = 0;
            $total = 0;
            $no = 0;
        @endphp
        @foreach ($rincian as $row)
            @php
                $no = $no + 1;
                $nama = $row->nama;
                $saldo_awal = $row->saldo_awal;
                $penambahan = $row->penambahan;
                $pengurangan = $row->pengurangan;
                $tahun = $row->tahun;
                $sal_akhir = $saldo_awal + $penambahan - $pengurangan;
                $tot_sal_awal = $tot_sal_awal + $saldo_awal;
                $tot_penambahan = $tot_penambahan + $penambahan;
                $tot_pengurangan = $tot_pengurangan + $pengurangan;
                $total = $total + $sal_akhir;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{ $no }}</td> 
                <td align="left"  valign="top" style="font-size:12px">{{ $nama }}</td> 
                <td align="right"  valign="top" style="font-size:12px">{{ $tahun }} &nbsp; &nbsp;</td> 
                <td align="right" valign="top" style="font-size:12px">{{ rupiah($saldo_awal) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{ rupiah($penambahan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{ rupiah($pengurangan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{ rupiah($sal_akhir) }}</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan = "3" align="center" valign="top" style="font-size:12px">TOTAL</td> 
            <td align="right" valign="top" style="font-size:12px">{{ rupiah($tot_sal_awal) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{ rupiah($tot_penambahan) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{ rupiah($tot_pengurangan) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{ rupiah($total) }}</td> 
        </tr>         
    </table>
    {{-- isi --}}
</body>

</html>
