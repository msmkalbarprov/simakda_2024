<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hambatan CALK</title>
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
        <TR>
            <TD colspan="7" align="center" ><b>BAB   II   IKHTISAR PENCAPAIAN KINERJA KEUANGAN</TD>
        </TR>
        <tr>
            <TD colspan="7" align="center" >KEGIATAN KURANG DARI 75 %</TD>
        </tr>
        <TR>
            <TD colspan="7" align="center" >TAHUN {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">Kode SKPD</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">Nama SKPD</td>
            <td bgcolor="#CCCCCC" width="10%" align="center">Kode Kegiatan</td>                        
            <td bgcolor="#CCCCCC" width="20%" align="center">Nama Kegiatan</td>                        
            <td bgcolor="#CCCCCC" width="10%" align="center">Anggaran</td>
            <td bgcolor="#CCCCCC" width="10%" align="center">Realisasi</td>
            <td bgcolor="#CCCCCC" width="10%" align="center">%</td>
            <td  bgcolor="#CCCCCC" width="20%" align="center">Hambatan</td>                           
        </tr>
        @php
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $nomor=$row->nomor;
                $skpd=$row->skpd;
                $kode=$row->kode;
                $nm_skpd=$row->nm_skpd;
                $bidang=$row->bidang;
                $ang_ubah=$row->angg_ubah;
                $realisasi=$row->realisasi;
                $persen=$row->persen;
                $hambatan=$row->hambatan;
                
                if($persen==0){
                    $persenx =  0.00; 
                }else{
                    $persenx =  substr($persen,0,5); 
                }
            @endphp
            @if($nomor=="1")
                <tr>
                    <td valign="top"><b>{{$skpd}}</b></td>
                    <td colspan="7" align="left" valign="top"><b>{{$nm_skpd}}</b></td>
                </tr>
            @else
                <tr>
                    <td ></td>
                    <td ></td>
                    <td align="center" valign="top">{{$kode}}</td>
                    <td align="left">{{$bidang}}</td>
                    <td align="right" valign="top">{{$ang_ubah < 0 ? '(' . rupiah($ang_ubah * -1) . ')' : rupiah($ang_ubah) }}</td> 
                    <td align="right" valign="top">{{$realisasi < 0 ? '(' . rupiah($realisasi * -1) . ')' : rupiah($realisasi)}}</td> 
                    <td align="center" valign="top">{{$persenx < 0 ? '(' . rupiah($persenx * -1) . ')' : rupiah($persenx)}}</td>
                    <td align="justify">{{$hambatan}}</td>
                </tr>
            @endif
        @endforeach
</body>
</html>