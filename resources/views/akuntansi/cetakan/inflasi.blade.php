<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INFLASI</title>
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

<body onload="window.print()">
{{-- <body> --}}

    
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="center"><strong>TAGGING PENGENDALIAN INFLASI DAERAH</strong></td>                         
        </tr>
         <TR>
            <td align="center"><strong>PROVINSI KALIMANTAN BARAT</strong></td>
        </TR>
        <TR>
            <td align="center"><strong>PER {{tgl_format_oyoy($tgl2)}} </strong></td>
        </TR>
        <TR>
            <td align="center"><strong>TAHUN ANGGARAN {{$thn_ang}} </strong></td>
        </TR>
        
    </TABLE>
    
    
    <TABLE style="border-collapse:collapse;font-size:14px" border="1" width="100%" >
        <THEAD>
            <TR height="50px">
                <TD bgcolor="#CCCCCC" rowspan="3" align="center" width=1% >No.</TD>
                <TD bgcolor="#CCCCCC" colspan="5" align="center" width=45% > Uraian</TD>
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=15% >Anggaran</TD> 
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=15% >Realisasi</TD>
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=10%>Persen</TD>
            </TR>
        </THEAD>
    
        
    @foreach($map1 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map2 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map3 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map4 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map5 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map6 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map7 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach

    @foreach($map8 as $row)
        @php
            $no = $row->no;
            $nm_skpd = $row->nm_skpd;
            $kd_sub = $row->kd_sub_kegiatan;
            $uraian = $row->uraian;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $jumlah_ang_a1 = $row->anggaran;
            $jumlah_real_a1=  $row->realisasi;

            if ($jumlah_ang_a1==0 && $jumlah_real_a1==0) {
                $jumlah_ang_a1s='';
                $jumlah_real_a1s='';
            }else{
                $jumlah_ang_a1s = rupiah($jumlah_ang_a1);
                $jumlah_real_a1s= rupiah($jumlah_real_a1);
            }
            if($jumlah_ang_a1==0 ||$jumlah_ang_a1==''){
                $persena1='';
            }else{
                $persen1=$jumlah_real_a1/$jumlah_ang_a1*100;
                $persena1=rupiah($persen1);
            }
        @endphp
        <TR>
            <TD align="left" >{{$no}}</TD>
            <TD align="left" >{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="left" >{{$kd_rek6}}</TD>
            <TD align="left" >{{$nm_rek6}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach
    
    </TABLE>

    <TABLE width="100%" style="font-size:11px;">
        <TR>
            <TD width="50%" align="center" ><b>&nbsp;</TD>
            <TD width="50%" align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="left" colspan="2" >
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Demikian laporan ini dibuat dengan sebenarnya dan kami bertanggungjawab mutlak atas kebenaran dan kualitas laporan ini
            </TD>
        </TR>
        <TR>
            <TD align="center" ></TD>
            <TD align="center" ></TD>
        </TR>
        <TR>
            <TD align="center" ></TD>
            <TD align="center" ></TD>
        </TR>
        <TR>
            <TD align="center" ></TD>
            <TD align="center" ></TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" >
            Pontianak, {{tgl_format_oyoy($tgl2)}}<br>
            a.n GUBERNUR KALIMANTAN BARAT<br>
            Kepala Badan Keuangan dan Aset Daerah
            </TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><b>{{$ttd->nama}}</TD>
        </TR>
        <TR>
            <TD align="center" ><u></TD>
            <TD align="center" >{{$ttd->pangkat}}</TD>
        </TR>
        <TR>
            <TD align="center" ></TD>
            <TD align="center" >{{$ttd->nip}}</TD>
        </TR>
    </TABLE><br/>
    
</body>

</html>
