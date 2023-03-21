<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca Saldo</title>
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
            <td align="center"><strong>LAPORAN REALISASI DUKUNGAN PROGRAM PEMULIHAN EKONOMI DAERAH</strong></td>                         
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
                <TD bgcolor="#CCCCCC" colspan="3" align="center" width=45% > Uraian</TD>
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=15% >Anggaran</TD> 
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=15% >Realisasi</TD>
                <TD bgcolor="#CCCCCC" rowspan="2" align="center" width=10%>Persen</TD>
            </TR>
        </THEAD>
    @php
        $kd_skpd_a1 = $map1->kd_skpd;
        $kd_sub_kegiatan_a1 = $map1->kd_sub_kegiatan; 
        $nilai1 = DB::select("
                        SELECT 1 as urut , kd_skpd, nm_skpd,'' kode, '' uraian, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($kd_skpd_a1)
                        union all
                        select 2 as urut , kd_skpd,'' nm_skpd, left(kd_sub_kegiatan,12) as kode , (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(z.kd_sub_kegiatan,12))as uraian,
                                                sum(nilai) as anggaran,
                                                (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                                where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                                                and b.tgl_bukti between '$tgl1' and '$tgl2'
                                                )as realisasi
                                                from trdrka z
                                                where kd_skpd in($kd_skpd_a1)
                                                and kd_sub_kegiatan in 
                                                ($kd_sub_kegiatan_a1) and z.jns_ang='$jns_ang'
                                                group by kd_skpd,nm_skpd,kd_sub_kegiatan
                                                union all
                        select 3 as urut , kd_skpd,''nm_skpd, left(kd_sub_kegiatan,15) as kode , (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=left(z.kd_sub_kegiatan,15))as uraian,
                                                sum(nilai) as anggaran,
                                                (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                                where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                                                and b.tgl_bukti between '$tgl1' and '$tgl2'
                                                )as realisasi
                                                from trdrka z
                                                where kd_skpd in($kd_skpd_a1)
                                                and kd_sub_kegiatan in 
                                                ($kd_sub_kegiatan_a1) and z.jns_ang='$jns_ang'
                                                group by kd_skpd,nm_skpd,kd_sub_kegiatan
                                                order by kd_skpd,kode,urut");


    @endphp
    @foreach($nilai1 as $row)
        @php
            $kd_sub = $row->kode;
            $nm_skpd = $row->nm_skpd;
            $uraian = $row->uraian;
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
            <TD align="left" ></TD>
            <TD align="left">{{$nm_skpd}}</TD>
            <TD align="left" >{{$kd_sub}}</TD>
            <TD align="left" >{{$uraian}}</TD>
            <TD align="right">{{$jumlah_ang_a1s}}</TD>
            <TD align="right">{{$jumlah_real_a1s}}</TD>
            <TD align="right">{{$persena1}}</TD>
        </TR>
    @endforeach


    @php
        $kd_skpd_a2 = $map2->kd_skpd;
        $kd_sub_kegiatan_a2 = $map2->kd_sub_kegiatan; 
        $nilai2 = DB::select("
                        SELECT 1 as urut , kd_skpd, nm_skpd,'' kode, '' uraian, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($kd_skpd_a2)
                        union all
                        select 2 as urut , kd_skpd,'' nm_skpd, left(kd_sub_kegiatan,12) as kode , (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(z.kd_sub_kegiatan,12))as uraian,
                                                sum(nilai) as anggaran,
                                                (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                                where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                                                and b.tgl_bukti between '$tgl1' and '$tgl2'
                                                )as realisasi
                                                from trdrka z
                                                where kd_skpd in($kd_skpd_a2)
                                                and kd_sub_kegiatan in 
                                                ($kd_sub_kegiatan_a2) and z.jns_ang='$jns_ang'
                                                group by kd_skpd,nm_skpd,kd_sub_kegiatan
                                                union all
                        select 3 as urut , kd_skpd,''nm_skpd, left(kd_sub_kegiatan,15) as kode , (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=left(z.kd_sub_kegiatan,15))as uraian,
                                                sum(nilai) as anggaran,
                                                (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                                where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                                                and b.tgl_bukti between '$tgl1' and '$tgl2'
                                                )as realisasi
                                                from trdrka z
                                                where kd_skpd in($kd_skpd_a2)
                                                and kd_sub_kegiatan in 
                                                ($kd_sub_kegiatan_a2) and z.jns_ang='$jns_ang'
                                                group by kd_skpd,nm_skpd,kd_sub_kegiatan
                                                order by kd_skpd,kode,urut");


    @endphp
    @foreach($nilai2 as $row)
        @php
            $kd_sub2=$row->kode;
            $nm_skpd2 = $row->nm_skpd;
            $uraiana2 = $row->uraian;
            $jumlah_ang_a2 = $row->anggaran;
            $jumlah_real_a2=  $row->realisasi;

            if ($jumlah_ang_a2==0 && $jumlah_real_a2==0) {
                $jumlah_ang_a2s='';
                $jumlah_real_a2s='';
            }else{
                $jumlah_ang_a2s = rupiah($jumlah_ang_a2);
                $jumlah_real_a2s= rupiah($jumlah_real_a2);
            }
            if($jumlah_ang_a2==0 ||$jumlah_ang_a2==''){
                $persena2='';
            }else{
                $persen2=$jumlah_real_a2/$jumlah_ang_a2*100;
                $persena2=rupiah($persen2);
            }
        @endphp
        <TR>
            <TD align="left" ></TD>
            <TD align="left" >{{$nm_skpd2}}</TD>
            <TD align="left" >{{$kd_sub2}}</TD>
            <TD align="left" >{{$uraiana2}}</TD>
            <TD align="right" >{{$jumlah_ang_a2s}}</TD>
            <TD align="right" >{{$jumlah_real_a2s}}</TD>
            <TD align="right" >{{$persena2}}</TD>
        </TR>
    @endforeach
    @php
        $total = collect(DB::select("SELECT sum(anggaran)anggaran,sum(realisasi)realisasi,sum(realisasi/anggaran*100)persen from 
            (select 3 as urut , kd_skpd,nm_skpd, left(kd_sub_kegiatan,15) as kode , (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=left(z.kd_sub_kegiatan,15))as uraian,
                                    sum(nilai) as anggaran,
                                    (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                    where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                                    and b.tgl_bukti between '$tgl1' and '$tgl2'
                                    )as realisasi
                                    from trdrka z
                                    where kd_skpd in($kd_skpd_a1)
                                    and kd_sub_kegiatan in 
                                    ($kd_sub_kegiatan_a1) and z.jns_ang='$jns_ang'
                                    group by kd_skpd,nm_skpd,kd_sub_kegiatan
                                    
                                    union all
            select 3 as urut , kd_skpd,''nm_skpd, left(kd_sub_kegiatan,15) as kode , (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=left(z.kd_sub_kegiatan,15))as uraian,
                        sum(nilai) as anggaran,
                        (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                        where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan 
                        and b.tgl_bukti between '$tgl1' and '$tgl2'
                        )as realisasi
                        from trdrka z
                        where kd_skpd in($kd_skpd_a2)
                        and kd_sub_kegiatan in 
                        ($kd_sub_kegiatan_a2) and z.jns_ang='$jns_ang'
                        group by kd_skpd,nm_skpd,kd_sub_kegiatan)a"))->first();

        $tot_anggaran = $total->anggaran;
        $tot_realisasi = $total->realisasi;
        $tot_persen = $total->persen;

        $tot_ang = rupiah($tot_anggaran);
        $tot_real = rupiah($tot_realisasi);
        $tot_per = rupiah($tot_persen);

    @endphp
    <TR>    
        <TD align="right" ></TD>
        <TD align="left">   
            <b>JUMLAH DUKUNGAN PENDANAAN BELANJA KESEHATAN DAN BELANJA PRIORITAS LAINNYA</b>
        </TD>
        <TD align="right" ></TD>
        <TD align="right" ></TD>
        <TD align="right" ><b>{{$tot_ang}}</TD>
        <TD align="right" ><b>{{$tot_real}}</TD>
        <TD align="right" ><b>{{$tot_persen}}</TD>
    </TR>
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
