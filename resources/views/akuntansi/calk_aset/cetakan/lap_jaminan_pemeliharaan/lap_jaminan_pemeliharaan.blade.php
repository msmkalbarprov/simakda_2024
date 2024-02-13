<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Jaminan Pemeliharaan</title>
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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <tr>
            <td align="right">Lampiran 3</td>
        </tr>                         
        <tr>
            <td align="center"><strong>DAFTAR  JAMINAN  PEMELIHARAAN</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>TAHUN ANGGARAN {{$thn_ang}}</strong></td>
        </tr>
        <tr>
            <td align="center"><strong>&nbsp;</strong></td>
        </tr>
        @if($skpdunit=="keseluruhan")                          
        @else
            <tr>
                <td align="left"><strong>SKPD : {{$kd_skpd}} - {{$nm_skpd}}</strong></td>
            </tr>
        @endif
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td width="5%" align="center"  ><b>NO</b></td>
            @if($skpdunit=="keseluruhan")
                <td width="5%" align="center"  ><b>KODE SKPD</b></td>
                <td width="20%" align="center"  ><b>NAMA SKPD</b></td>
            @else
            @endif
            <td width="13%" align="center"  ><b>NAMA PROGRAM</b></td>
            <td width="13%" align="center"  ><b>NAMA KEGIATAN</b></td>
            <td width="13%" align="center"  ><b>NILAI KONTRAK (Rp.)</b></td>
            <td width="13%" align="center"  ><b>PELAKSANA</b></td>
            <td width="13%" align="center"  ><b>NILAI JAMINAN PEMELIHARAAN (Rp)</b></td>
            <td width="13%" align="center"  ><b>MASA JAMINAN PEMELIHARAAN</b></td>
            <td width="13%" align="center"  ><b>NAMA PENERBIT JAMINAN PEMELIHARAAN</b></td>
        </tr>
        <tr>
            <td align="center" bgcolor="#CCCCCC" >1</td> 
            <td align="center" bgcolor="#CCCCCC" >2</td> 
            <td align="center" bgcolor="#CCCCCC" >3</td> 
            <td align="center" bgcolor="#CCCCCC" >4</td> 
            <td align="center" bgcolor="#CCCCCC" >5</td> 
            <td align="center" bgcolor="#CCCCCC" >6</td> 
            <td align="center" bgcolor="#CCCCCC" >7</td> 
            <td align="center" bgcolor="#CCCCCC" >8</td>
            @if($skpdunit=="keseluruhan")
               <td align="center" bgcolor="#CCCCCC" >9</td>
               <td align="center" bgcolor="#CCCCCC" >10</td>
            @else
            @endif 
        </tr>
        @php
            $no=1;
        @endphp
        @foreach($query as $row)
            @php
                $kode_skpd     = $row->kd_skpd;
                $nama_skpd     = $row->nm_skpd;
                $nm_program    = $row->nm_program;
                $nm_sub_kegiatan   = $row->nm_sub_kegiatan;
                $nilai_kontrak = $row->nilai;
                $pelaksana     = $row->pelaksana;
                $nilai_jamin   = $row->nilai_jaminan;
                $nm_penerbit   = $row->nm_penerbit;
                $masa_awal     = $row->masa_awal;
                $masa_akhir    = $row->masa_akhir;
            @endphp
            <tr>
                <td align="center" valign="top">{{$no++}}</td>
                @if($skpdunit=="keseluruhan")
                    <td align="center" valign="top">{{$kode_skpd}}</td>
                    <td align="left" valign="top">{{$nama_skpd}}</td>
                @else
                @endif
                <td align="left" valign="top">{{$nm_program}}</td>
                <td align="left" valign="top">{{$nm_sub_kegiatan}}</td>
                <td align="right" valign="top">{{$nilai_kontrak < 0 ? '(' . rupiah($nilai_kontrak * -1) . ')' : rupiah($nilai_kontrak) }}</td>
                <td align="right" valign="top">{{$pelaksana}}</td>
                <td align="right" valign="top">{{$nilai_jamin < 0 ? '(' . rupiah($nilai_jamin * -1) . ')' : rupiah($nilai_jamin) }}</td>
                <td align="right" valign="top">{{$masa_awal}} s/d {{$masa_akhir}}</td>
                <td align="right" valign="top">{{$nm_penerbit}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>