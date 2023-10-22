<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA D4</title>
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
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN D4 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>RINGKASAN REALISASI PENJABARAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK, SUB</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>RINCIAN OBJEK, PENDAPATAN, BELANJA, DAN PEMBIAYAAN TA {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
              <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Kode</td>
              <td width="35%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Uraian</td>
              <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Anggaran</td>
              <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Realisasi</td>
              <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Tambah/kurang</td>
              <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">%</td>
            </tr>
            <tr>
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
              <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
            </tr>
        </thead>
        @foreach($rincian as $row)
            @php
                $kelompok = $row->kelompok;
                $nm_rek = $row->nm_rek;
                $anggaran = $row->anggaran;
                $realisasi = $row->realisasi;
                $tamkur = $anggaran-$realisasi;
                if ($anggaran<0) {
                    $aa="(";
                    $anggaran1=$anggaran*-1;
                    $ba=")";
                }else{
                    $aa="";
                    $anggaran1=$anggaran;
                    $ba="";              
                }
                if ($realisasi<0) {
                    $ar="(";
                    $realisasi1=$realisasi*-1;
                    $br=")";
                }else{
                    $ar="";
                    $realisasi1=$realisasi;
                    $br="";              
                }
                if ($tamkur<0) {
                    $at="(";
                    $tamkur1=$tamkur*-1;
                    $bt=")";
                }else{
                    $at="";
                    $tamkur1=$tamkur;
                    $bt="";              
                }
                if ($anggaran==0) {
                    $persen=0;
                }else{
                    $persen=$realisasi/$anggaran*100;
                }
                if ($persen<0) {
                    $ap="(";
                    $persen1=$persen*-1;
                    $bp=")";
                }else{
                    $ap="";
                    $persen1=$persen;
                    $bp="";              
                }
            @endphp
            <tr>
                <td align="left" valign="top" style="font-size:12px"><b>{{$kelompok}}</b></td> 
                <td align="left"  valign="top" style="font-size:12px"><b>{{$nm_rek}}</b></td> 
                <td align="right" valign="top" style="font-size:12px"><b>{{$aa}}{{rupiah($anggaran1)}}{{$ba}}</b></td> 
                <td align="right" valign="top" style="font-size:12px"><b>{{$ar}}{{rupiah($realisasi1)}}{{$br}}</b></td> 
                <td align="right" valign="top" style="font-size:12px"><b>{{$at}}{{rupiah($tamkur1)}}{{$bt}}</b></td>
                <td align="right" valign="top" style="font-size:12px"><b>{{$ap}}{{rupiah($persen1)}}{{$bp}}</b></td>              
            </tr>
        @endforeach

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
