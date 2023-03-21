<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LO</title>
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
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
            @if($kd_skpd=='')

            @elseif(strlen($kd_skpd)==17)

            <TR>
                <td align="center"><strong>{{nama_org($kd_skpd)}}</strong></td>
            </TR>
            @else

            <TR>
                <td align="center"><strong>{{nama_skpd($kd_skpd)}}</strong></td>
            </TR>
            @endif
            <TR>
                <td align="center"><strong>LAPORAN OPERASIONAL</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN {{$nm_bln}} {{$thn_ang}}  </strong></td>
            </TR>
            
                
    </TABLE>

    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>NO</b></td>                            
                <td  bgcolor="#CCCCCC" width="40%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang}}</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang_1}}</b></td>
                <td  bgcolor="#CCCCCC" width="15%" align="center" ><b>Kenaikan</br>(Penurunan)</b></td>
                <td  bgcolor="#CCCCCC" width="5%" align="center" ><b>%</b></td>   
            </tr>
                        
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>                           
            </tr>
        </tfoot>
        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>                            
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="15%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%">&nbsp;</td>
        </tr>
    @php
        $no = 0;
    @endphp
    @foreach($map_lo as $loquery)
        @php
            $nama      = $loquery->uraian; 
            $bold       = $loquery->bold;
            $n1         = $loquery->kode_1ja;
            $n1        = ($n1=="-"?"'-'":$n1);
            $n2         = $loquery->kode;
            $n2         = ($n2=="-"?"'-'":$n2);
            $n3         = $loquery->kode_1;
            $n3         = ($n3=="-"?"'-'":$n3);
            $n4        = $loquery->kode_2;
            $n4        = ($n4=="-"?"'-'":$n4);
            $n5        = $loquery->kode_3;
            $n5        = ($n5=="-"?"'-'":$n5);
            $cetak_a    = $loquery->cetak;
            $k1        = $loquery->kurangi_1;
            $k1        = ($k1=="-"?"'-'":$k1);
            $k2        = $loquery->kurangi;
            $k2        = ($k2=="-"?"'-'":$k2);
            $cetak_k    = $loquery->c_kurangi;

            $nilainya = collect(DB::select("select sum(nilai_a-nilai_b) nilai
                    from(SELECT SUM($cetak_a) as nilai_a,0 nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE (left(kd_rek6,1) in ($n1) or left(kd_rek6,2) in ($n2) or left(kd_rek6,4) in ($n3) or left(kd_rek6,6) in ($n4) or left(kd_rek6,8) in ($n5)) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan $skpd_clauses

                    union all

                    SELECT 0  nilai_a,SUM($cetak_k) nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE (left(kd_rek6,1) in ($k1) or left(kd_rek6,2) in ($k2)) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan $skpd_clauses) a "))->first();

            $nilai=$nilainya->nilai;

            $nilainya_lalu = collect(DB::select("select sum(nilai_a-nilai_b) nilai
                    from(SELECT SUM(kredit-debet) as nilai_a,0 nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE (left(kd_rek6,1) in ($n1) or left(kd_rek6,2) in ($n2) or left(kd_rek6,4) in ($n3) or left(kd_rek6,6) in ($n4) or left(kd_rek6,8) in ($n5)) 
                    and year(tgl_voucher)=$thn_ang_1 $skpd_clauses

                    union all

                    SELECT 0  nilai_a,SUM($cetak_k) nilai_b FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE (left(kd_rek6,1) in ($k1) or left(kd_rek6,2) in ($k2)) 
                    and year(tgl_voucher)=$thn_ang_1 $skpd_clauses) a "))->first();   
            
            $nilai_lalu=$nilainya_lalu->nilai;
            $real_nilai = $nilai - $nilai_lalu; 
            if ($real_nilai < 0){
                $lo0="("; 
                $real_nilai1=$real_nilai*-1; 
                $lo00=")";
            }else {
                $lo0=""; 
                $real_nilai1=$real_nilai; 
                $lo00="";
            }

            if( $nilai_lalu=='' || $nilai_lalu==0){
                $persen1 = 0;
            }else{
                $persen1 = ($real_nilai/$nilai_lalu)*100;
            }

            

            $no=$no+1;
        @endphp

            @if ($bold == 0)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="right"></td>
                </tr>

            @elseif ($bold == 1 || $bold== 2)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="right"></td>
                </tr>
            @else
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{rupiah($nilai)}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{rupiah($nilai_lalu)}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right">{{$lo0}}{{rupiah($real_nilai1)}}{{$lo00}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="right">{{rupiah($persen1)}}</td>
                </tr>
            @endif
        

    @endforeach

    
    </TABLE>
    
</body>

</html>
