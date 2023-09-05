<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAK</title>
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

<body">
{{-- <body> --}}

    <table style=border-collapse:collapse; width=100% align=center border=0 cellspacing=0 cellpadding=4>
        <tr>
            <td align=center><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
        </tr>
        <tr>
            <td align=center><strong>LAPORAN ARUS KAS</strong></td>
        </tr>                    
        <tr>
            <td align=center><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN {{$nm_bln}} {{$thn_ang}}</strong></td>
        </tr>
        <tr>
            <td align=center><strong>METODE LANGSUNG</strong></td>
        </tr>
        <tr>
            <td align=center><strong>&nbsp;</strong></td>
        </tr>
    </table>

    <table style=border-collapse:collapse; width=100% align=center border=1 cellspacing=0 cellpadding=4>
        <thead>                       
            <tr>
                <td bgcolor=#CCCCCC width=5% align=center><b>NO</b></td>                            
                <td  bgcolor=#CCCCCC width=40% align=center><b>URAIAN</b></td>
                <td bgcolor=#CCCCCC width=20% align=center><b>{{$thn_ang}}</b></td>  
                <td bgcolor=#CCCCCC width=20% align=center><b>{{$thn_ang1}}</b></td>  
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style=border-top: none;></td>
                <td style=border-top: none;></td>
                <td style=border-top: none;></td>
                <td style=border-top: none;></td>                          
             </tr>
        </tfoot>
        <tr>
            <td style=vertical-align:top;border-top: none;border-bottom: none; width=5% align=center>&nbsp;</td>                            
            <td style=vertical-align:top;border-top: none;border-bottom: none; width=40%>&nbsp;</td>
            <td style=vertical-align:top;border-top: none;border-bottom: none; width=20%>&nbsp;</td>
            <td style=vertical-align:top;border-top: none;border-bottom: none; width=20%>&nbsp;</td>
        </tr>
    @php
        $no = 0;
        if($pfk_masuk<0){
            $xpfkm="(";
            $pfk_masukk=$pfk_masuk*-1;
            $ypfkm=")";
        }else{
            $xpfkm="";
            $pfk_masukk=$pfk_masuk;
            $ypfkm="";
        }

        if($pfk_keluar<0){
            $xpfkk="(";
            $pfk_keluarr=$pfk_keluar*-1;
            $ypfkk=")";
        }else{
            $xpfkk="";
            $pfk_keluarr=$pfk_keluar;
            $ypfkk="";
        }

        if($pfk_bersih<0){
            $xpfkb="(";
            $pfk_bersihh=$pfk_bersih*-1;
            $ypfkb=")";
        }else{
            $xpfkb="";
            $pfk_bersihh=$pfk_bersih;
            $ypfkb="";
        }

        if($naik_turun_kas<0){
            $xntk="(";
            $naik_turun_kass=$naik_turun_kas*-1;
            $yntk=")";
        }else{
            $xntk="";
            $naik_turun_kass=$naik_turun_kas;
            $yntk="";
        }

    @endphp
    @foreach($map as $row)
        @php
            $no++;
            $nor   =$row->nor ;
            $seq     =$row->seq;
            $bold     =$row->bold;
            $uraian   =$row->uraian;
            $thn_m1     =$row->thn_m1;
            if($thn_m1<0){
                $x1="(";
                $nilai_sbl=$thn_m1*-1;
                $y1=")";
            }else{
                $x1="";
                $nilai_sbl=$thn_m1;
                $y1="";
            }
            $kode_1    =$row->kode_1;
            $kode_2    =$row->kode_2;
            $kode_3    =$row->kode_3;
            $kecuali_1    =$row->kecuali_1;
            $kecuali_2    =$row->kecuali_2;
            $kecuali_3    =$row->kecuali_3;
            if($kode_1==''){
                $kode_1="'XXX'";
            }
            if($kode_2==''){
                $kode_2="'XXXXX'";
            }
            if($kode_3==''){
                $kode_3="'XXXXXXX'";
            }if($kecuali_1==''){
                $kecuali_1="'XXX'";
            }
            if($kecuali_2==''){
                $kecuali_2="'XXXXX'";
            }
            if($kecuali_3==''){
                $kecuali_3="'XXXXXXX'";
            }
            $nilainya = collect(DB::select("SELECT SUM(nilai) nilai
                    from(
                    SELECT SUM(realisasi) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kode_1) 
                    or left(kd_rek6,6) in ($kode_2) 
                    or left(kd_rek6,8) in ($kode_3))
                    union all
                    SELECT SUM(realisasi*-1) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kecuali_1) 
                    or left(kd_rek6,6) in ($kecuali_2) 
                    or left(kd_rek6,8) in ($kecuali_3)))a"))->first();
            $nilai = $nilainya->nilai;
            if($nilai<0){
                $x="(";
                $nilaii=$nilai*-1;
                $y=")";
            }else{
                $x="";
                $nilaii=$nilai;
                $y="";
            }
        @endphp
        @if($bold == 0)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$uraian}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;"></td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;"></td>
            </tr>
        @elseif($bold==1)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$uraian}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x}}{{rupiah($nilaii)}}{{$y}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
            </tr>
        @elseif($bold==2)
            @if($seq==375)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xpfkm}}{{rupiah($pfk_masukk)}}{{$ypfkm}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @elseif($seq==390)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xpfkk}}{{rupiah($pfk_keluarr)}}{{$ypfkk}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @else
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x}}{{rupiah($nilaii)}}{{$y}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @endif
        @elseif($bold==3)
            @if($seq==380)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xpfkm}}{{rupiah($pfk_masukk)}}{{$ypfkm}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @elseif($seq==395)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xpfkk}}{{rupiah($pfk_keluarr)}}{{$ypfkk}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @else
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x}}{{rupiah($nilaii)}}{{$y}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @endif
        @elseif($bold==4)
            @if($seq==400)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xpfkb}}{{rupiah($pfk_bersihh)}}{{$ypfkb}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @elseif($seq==405)
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$xntk}}{{rupiah($naik_turun_kass)}}{{$yntk}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @else
                <tr>
                    <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$no}}</td>
                    <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x}}{{rupiah($nilaii)}}{{$y}}</td>
                    <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top: solid 1px black;">{{$x1}}{{rupiah($nilai_sbl)}}{{$y1}}</td>
                </tr>
            @endif
        @else
        @endif
    @endforeach

    
    </TABLE>
    
</body>

</html>
