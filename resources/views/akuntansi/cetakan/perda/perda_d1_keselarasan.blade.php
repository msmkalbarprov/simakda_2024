<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.1</title>
    <style>
        body {
          font-family: Arial;
        }

        .bordered {
          width: 100%;
          border-collapse: collapse;
        }

        .bordered th,
        .bordered td {
          border: 1px solid black;
          padding: 4px;
        }

        .bordered td:nth-child(n+5) {
          text-align: right;
        }

        .bordered th {
          /* background-color: #cccccc; */
        }

        .bordered {
          font-size: 11px;
        }

        .bold {
          font-weight: bold;
        }

        table {
          width: 100%;
        }

        
    </style>
</head>

<body >
{{-- <body> --}}
    <table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN D1 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_perda_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_perda_tentang) }}</TD>
        </TR>
    </table>
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td rowspan="4" align="center" style="border-right:hidden">
                    <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
                </td>
                
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>REKAPITULASI REALISASI BELANJA DAERAH UNTUK<BR>KESELARASAN DAN KETERPADUAN URUSAN PEMERINTAH DAERAH<BR> DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
                <td colspan = "4" rowspan = "3" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">KODE</td>
                <td rowspan = "3" width="35%" align="center" bgcolor="#CCCCCC" style="font-size:12px">URUSAN PEMERINTAH DAERAH</td>
                <td colspan = "8" width="45%" align="center" bgcolor="#CCCCCC" style="font-size:12px">KELOMPOK BELANJA</td>
            </tr>
            <tr>
                <td colspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">OPERASI</td>
                <td colspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">MODAL</td>
                <td colspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">TIDAK TERDUGA</td>
                <td colspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">TRANSFER</td>
            </tr>
            <tr>
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN</td> 
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI</td>
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN</td> 
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI</td>
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN</td> 
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI</td>
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN</td> 
                <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI</td>
            <tr>
                <td colspan = "4"  align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td>  
            </tr>
        </thead>
        @php
            $ja_operasi = 0;
            $ja_modal = 0;
            $ja_btt = 0;
            $ja_bt = 0;
            $jr_operasi = 0;
            $jr_modal = 0;
            $jr_btt = 0;
            $jr_bt = 0;
        @endphp
        @foreach($rincian as $row)
            @php
                $kode = $row->kode;
                $nm_rek = $row->nama;
                $a_operasi = $row->a_operasi;
                $a_modal = $row->a_modal;
                $a_btt = $row->a_btt;
                $a_bt = $row->a_bt;
                $r_operasi = $row->r_operasi;
                $r_modal = $row->r_modal;
                $r_btt = $row->r_btt;
                $r_bt = $row->r_bt;
                $k1 = substr($kode,0,1);
                $k2 = substr($kode,1,2);
                $k3 = substr($kode,4,1);
                $k4 = substr($kode,6,2);
                 
                $leng=strlen($kode);
                    
            @endphp
            @if($leng==1)
                @php
                    $ja_operasi = $ja_operasi+$a_operasi;
                    $ja_modal = $ja_modal+$a_modal;
                    $ja_btt = $ja_btt+$a_btt;
                    $ja_bt = $ja_bt+$a_bt;
                    $jr_operasi = $jr_operasi+$r_operasi;
                    $jr_modal = $jr_modal+$r_modal;
                    $jr_btt = $jr_btt+$r_btt;
                    $jr_bt = $jr_bt+$r_bt;
                @endphp
                <tr>
                    <td align="center" valign="top" style="font-size:12px"><b>{{$k1}}</b></td> 
                    <td align="center" valign="top" style="font-size:12px"><b></b></td> 
                    <td align="center" valign="top" style="font-size:12px"><b></b></td> 
                    <td align="center" valign="top" style="font-size:12px"><b></b></td> 
                    <td align="left"  valign="top" style="font-size:12px"><b>{{$nm_rek}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($a_operasi)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($r_operasi)}}</b> </td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($a_modal)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($r_modal)}}</b> </td>
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($a_btt)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($r_btt)}}</b> </td>
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($a_bt)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($r_bt)}}</b> </td>
                </tr>
            @else
                <tr>
                    <td align="center" valign="top" style="font-size:12px">{{$k1}}</td> 
                    <td align="center" valign="top" style="font-size:12px">{{$k2}}</td> 
                    <td align="center" valign="top" style="font-size:12px">{{$k3}}</td> 
                    <td align="center" valign="top" style="font-size:12px">{{$k4}}</td> 
                    <td align="left"  valign="top" style="font-size:12px">{{$nm_rek}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($a_operasi)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($r_operasi)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($a_modal)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($r_modal)}}</td>
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($a_btt)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($r_btt)}} 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($a_bt)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($r_bt)}} </td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td align="center" valign="top" style="font-size:12px" colspan="5"><b>TOTAL</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($ja_operasi)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($jr_operasi)}}</b> </td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($ja_modal)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($jr_modal)}}</b></td>  
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($ja_btt)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($jr_btt)}}</b></td>  
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($ja_bt)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($jr_bt)}}</b></td> 
        </tr>
   

    </table>
    {{-- isi --}}
    {{-- tanda tangan --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center"></td>
        </tr>
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center">Pontianak, {{tgl_format_oyoy($tanggal_ttd)}}<br>Pj. GUBERNUR KALIMANTAN BARAT<br><br><br><br><br><b><u>HARISSON</u></b>
            </td>
        </tr>
    </table>
    
</body>

</html>
