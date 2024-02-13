<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB III LRA BELANJA</title>
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
{{-- <body> --}}
    {{-- isi --}}
    @if($judul==2)  
        <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                <TR>
                    <TD align="center" ><b>BAB III PENJELASAN POS-POS LAPORAN KEUANGAN</TD>
                </TR>
        </TABLE><br/>
        <table style="border-collapse:collapse;{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="left" width="5%"><strong>3.1</strong></td>                         
                <td align="left" colspan="2"><strong>Rincian dan Penjelasan masing-masing pos-pos laporan Keuangan.</strong></td>                         
            </tr>
            <tr>
                <td align="left" width="5%"><strong>&nbsp;</strong></td>                         
                <td align="left" width="10%"><strong>3.1.1.</strong></td>                         
                <td align="left"><strong>Laporan Realisasi Anggaran</strong></td>                         
            </tr>
        </table><br>
    @else
    @endif
    <table style="{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="left" width="2%" rowspan="2"><strong>&nbsp;</strong></td>                         
            <td style="border-top:solid;;border-bottom:solid;" align="center" width="10%" rowspan="2"><strong>Reff</strong></td>                         
            <td style="border-top:solid;;border-bottom:solid;" align="center" width="20%" rowspan="2"><strong>Uraian</strong></td>
            <td style="border-top:solid;" align="center" width="16%"><strong>Anggaran {{$thn_ang}}</strong></td>
            <td style="border-top:solid;" align="center" width="16%"><strong>Realisasi {{$thn_ang}}</strong></td>
            <td style="border-top:solid;" align="center" width="15%"><strong>Lebih / (Kurang)</strong></td>                            
            <td style="border-top:solid;border-bottom:solid;" align="center" width="8%" rowspan="2"><strong>%</strong></td>
            <td style="border-top:solid;" align="center" width="15%"><strong>Realisasi {{$thn_ang_1}}</strong></td>
        </tr>
        <tr>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
        </tr>
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        @foreach($sql_skpd as $row)
            @php
                $kd_skpd        = $row->kd_skpd;
                $kd_rek         = $row->kd_rek;
                $nm_rek         = $row->nm_rek;
                $anggaran       = $row->anggaran;
                $realisasi      = $row->realisasi;
                $realisasi_lalu = $row->realisasi_lalu;
                $selisih        = $row->selisih;
                $persen         = $row->persen;
                $selisih_tahun         = $row->selisih_tahun;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }
                if($selisih_tahun<0){
                    $c = "(";
                    $selisihh_tahun = $selisih_tahun*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $selisihh_tahun = $selisih_tahun;
                    $d = "";
                }
                if($realisasi_lalu<$realisasi){
                    $naik_turun = "peningkatan";
                
                }else if($realisasi==$realisasi_lalu){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "penurunan";
                } 
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==1)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{$kd_rek}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="center"><strong>&nbsp;</strong></td>                         
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong></strong>&nbsp;</td>                            
                    <td align="center"><strong></strong>&nbsp;</td>
                    <td align="center"><strong></strong>&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="7">Belanja pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} memiliki anggaran belanja sebesar Rp.{{rupiah($anggaran)}} dan realisasi belanja sebesar Rp.{{rupiah($realisasi)}} Apabila dibandingkan dengan realisasi belanja Tahun Anggaran {{$thn_ang_1}} yang tercatat sebesar Rp. {{rupiah($realisasi_lalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$c}}{{rupiah($selisihh_tahun)}}{{$d}} dengan rincian penjelasan sebagai berikut :</td>                         
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="center"><strong>&nbsp;</strong></td>                         
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong></strong>&nbsp;</td>                            
                    <td align="center"><strong></strong>&nbsp;</td>
                    <td align="center"><strong></strong>&nbsp;</td>
                </tr>
            @elseif($leng==2)
                <tr>
                    <td align="left">&nbsp;</td> 
                    <td align="right">&nbsp;</td>
                    <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                    <td align="right">{{rupiah($anggaran)}}</td>
                    <td align="right">{{rupiah($realisasi)}}</td>
                    <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                    <td align="center">{{rupiah($persen)}}</td>
                    <td align="right">{{rupiah($realisasi_lalu)}}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        
        <!-- 51 -->
        @foreach($sql_skpd as $rew)
            @php
                $kd_skpd        = $rew->kd_skpd;
                $kd_rek         = $rew->kd_rek;
                $nm_rek         = $rew->nm_rek;
                $anggaran       = $rew->anggaran;
                $realisasi      = $rew->realisasi;
                $realisasi_lalu = $rew->realisasi_lalu;
                $selisih        = $rew->selisih;
                $persen         = $rew->persen;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==51)
                @if($leng==2)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                @elseif($leng==4)
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                        <td align="right">{{rupiah($anggaran)}}</td>
                        <td align="right">{{rupiah($realisasi)}}</td>
                        <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                        <td align="center">{{rupiah($persen)}}</td>
                        <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        @foreach($sql_skpd as $riw)
            @php
                $kd_skpd        = $riw->kd_skpd;
                $kd_rek         = $riw->kd_rek;
                $nm_rek         = $riw->nm_rek;
                $anggaran       = $riw->anggaran;
                $realisasi      = $riw->realisasi;
                $realisasi_lalu = $riw->realisasi_lalu;
                $selisih        = $riw->selisih;
                $persen         = $riw->persen;
                $selisih_tahun         = $riw->selisih_tahun;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih_tahun<0){
                    $c = "(";
                    $selisihh_tahun = $selisih_tahun*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $selisihh_tahun = $selisih_tahun;
                    $d = "";
                }

                if($realisasi_lalu<$realisasi){
                    $naik_turun = "peningkatan";
                    
                }else if($realisasi==$realisasi_lalu){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "penurunan";
                } 

                
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==51)
                @if($leng==4)
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong></strong>&nbsp;</td>                            
                         <td align="center"><strong></strong>&nbsp;</td>
                         <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} memiliki anggaran belanja sebesar Rp. {{rupiah($anggaran)}} dan realisasi belanja sebesar Rp. {{rupiah($realisasi)}} Apabila dibandingkan dengan realisasi belanja Tahun Anggaran {{$thn_ang_1}} yang tercatat sebesar Rp.{{rupiah($realisasi_lalu)}} terjadi {{$naik_turun}} sebesar Rp. {{rupiah($selisihh_tahun)}} Rincian {{$nm_rek}} Tahun Anggaran {{$thn_ang}} sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    @php
                        $sql_det51 = DB::select("select *,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd, case when anggaran<>0 then realisasi/anggaran*100 else 0 end persen  , realisasi-anggaran selisih, realisasi-realisasi_lalu selisih_tahun
                        from
                        (
                            select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                            from(select kd_skpd,kd_rek,sum(anggaran)anggaran,sum(realisasi)realisasi
                                from (
                                    select kd_skpd,left(kd_rek6,4)kd_rek, sum(nilai) anggaran, 0 realisasi 
                                        from trdrka 
                                        where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                                        group by kd_skpd,left(kd_rek6,4)
                                        union all
                                        select b.kd_skpd, left(kd_rek6,4)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                        where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                                        group by kd_skpd,left(kd_rek6,4)
                                    )a
                                    group by kd_skpd,kd_rek
                                )a
                                LEFT JOIN
                                (select b.kd_skpd, LEFT(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                                    from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                    where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                                    group by b.kd_skpd, LEFT(a.kd_rek6,4)
                                ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                            group by a.kd_skpd,a.kd_rek
                        )a
                        where $skpd_clause and kd_rek='$kd_rek'
                        order by kd_skpd");
                        $no_det51 = 1;
                    @endphp
                    @foreach($sql_det51 as $det51)
                        @php
                            $kd_skpd_det51        = $det51->kd_skpd;
                            $nm_skpd_det51        = $det51->nm_skpd;
                            $kd_rek_det51         = $det51->kd_rek;
                            $anggaran_det51       = $det51->anggaran;
                            $realisasi_det51      = $det51->realisasi;
                            $realisasi_lalu_det51 = $det51->realisasi_lalu;
                            $selisih_det51        = $det51->selisih;
                            $persen_det51         = $det51->persen;
                            $selisih_tahun_det51  = $det51->selisih_tahun;
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="right">{{$no_det51++}})</td>                         
                            <td align="left">{{$nm_skpd_det51}}</td>
                            <td align="right">{{$anggaran_det51 < 0 ? '(' . rupiah($anggaran_det51 * -1) . ')' : rupiah($anggaran_det51) }}</td>
                            <td align="right">{{$realisasi_det51 < 0 ? '(' . rupiah($realisasi_det51 * -1) . ')' : rupiah($realisasi_det51) }}</td>
                            <td align="right">{{$selisih_det51 < 0 ? '(' . rupiah($selisih_det51 * -1) . ')' : rupiah($selisih_det51) }}</td>                            
                            <td align="center">{{$persen_det51 < 0 ? '(' . rupiah($persen_det51 * -1) . ')' : rupiah($persen_det51) }}</td>
                            <td align="right">{{$realisasi_lalu_det51 < 0 ? '(' . rupiah($realisasi_lalu_det51 * -1) . ')' : rupiah($realisasi_lalu_det51)}}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{$nm_rek}} Terdiri dari:</td>
                         <td align="right"></td>
                         <td align="right"></td>
                         <td align="right"></td>                            
                         <td align="center"></td>
                         <td align="right"></td>
                    </tr>
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                    
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>

        <!-- 52 -->
        @foreach($sql_skpd as $raw)
            @php
                $kd_skpd        = $raw->kd_skpd;
                $kd_rek         = $raw->kd_rek;
                $nm_rek         = $raw->nm_rek;
                $anggaran       = $raw->anggaran;
                $realisasi      = $raw->realisasi;
                $realisasi_lalu = $raw->realisasi_lalu;
                $selisih        = $raw->selisih;
                $persen         = $raw->persen;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==52)
                @if($leng==2)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                @elseif($leng==4)
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                        <td align="right">{{rupiah($anggaran)}}</td>
                        <td align="right">{{rupiah($realisasi)}}</td>
                        <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                        <td align="center">{{rupiah($persen)}}</td>
                        <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        @foreach($sql_skpd as $ruw)
            @php
                $kd_skpd        = $ruw->kd_skpd;
                $kd_rek         = $ruw->kd_rek;
                $nm_rek         = $ruw->nm_rek;
                $anggaran       = $ruw->anggaran;
                $realisasi      = $ruw->realisasi;
                $realisasi_lalu = $ruw->realisasi_lalu;
                $selisih        = $ruw->selisih;
                $persen         = $ruw->persen;
                $selisih_tahun         = $ruw->selisih_tahun;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih_tahun<0){
                    $c = "(";
                    $selisihh_tahun = $selisih_tahun*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $selisihh_tahun = $selisih_tahun;
                    $d = "";
                }

                if($realisasi_lalu<$realisasi){
                    $naik_turun = "peningkatan";
                    
                }else if($realisasi==$realisasi_lalu){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "penurunan";
                } 

                
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==52)
                @if($leng==4)
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong></strong>&nbsp;</td>                            
                         <td align="center"><strong></strong>&nbsp;</td>
                         <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} memiliki anggaran belanja sebesar Rp. {{rupiah($anggaran)}} dan realisasi belanja sebesar Rp. {{rupiah($realisasi)}} Apabila dibandingkan dengan realisasi belanja Tahun Anggaran {{$thn_ang_1}} yang tercatat sebesar Rp.{{rupiah($realisasi_lalu)}} terjadi {{$naik_turun}} sebesar Rp. {{rupiah($selisihh_tahun)}} Rincian {{$nm_rek}} Tahun Anggaran {{$thn_ang}} sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>

                    @php
                        $sql_det52 = DB::select("select *,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd, case when anggaran<>0 then realisasi/anggaran*100 else 0 end persen  , realisasi-anggaran selisih, realisasi-realisasi_lalu selisih_tahun
                        from
                        (
                            select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                            from(select kd_skpd,kd_rek,sum(anggaran)anggaran,sum(realisasi)realisasi
                                from (
                                    select kd_skpd,left(kd_rek6,4)kd_rek, sum(nilai) anggaran, 0 realisasi 
                                        from trdrka 
                                        where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                                        group by kd_skpd,left(kd_rek6,4)
                                        union all
                                        select b.kd_skpd, left(kd_rek6,4)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                        where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                                        group by kd_skpd,left(kd_rek6,4)
                                    )a
                                    group by kd_skpd,kd_rek
                                )a
                                LEFT JOIN
                                (select b.kd_skpd, LEFT(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                                    from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                    where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                                    group by b.kd_skpd, LEFT(a.kd_rek6,4)
                                ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                            group by a.kd_skpd,a.kd_rek
                        )a
                        where $skpd_clause and kd_rek='$kd_rek'
                        order by kd_skpd");
                        $no_det52 = 1;
                    @endphp
                    @foreach($sql_det52 as $det52)
                        @php
                            $kd_skpd_det52        = $det52->kd_skpd;
                            $nm_skpd_det52        = $det52->nm_skpd;
                            $kd_rek_det52         = $det52->kd_rek;
                            $anggaran_det52       = $det52->anggaran;
                            $realisasi_det52      = $det52->realisasi;
                            $realisasi_lalu_det52 = $det52->realisasi_lalu;
                            $selisih_det52        = $det52->selisih;
                            $persen_det52         = $det52->persen;
                            $selisih_tahun_det52  = $det52->selisih_tahun;
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="right">{{$no_det52++}})</td>                         
                            <td align="left">{{$nm_skpd_det52}}</td>
                            <td align="right">{{$anggaran_det52 < 0 ? '(' . rupiah($anggaran_det52 * -1) . ')' : rupiah($anggaran_det52) }}</td>
                            <td align="right">{{$realisasi_det52 < 0 ? '(' . rupiah($realisasi_det52 * -1) . ')' : rupiah($realisasi_det52) }}</td>
                            <td align="right">{{$selisih_det52 < 0 ? '(' . rupiah($selisih_det52 * -1) . ')' : rupiah($selisih_det52) }}</td>                            
                            <td align="center">{{$persen_det52 < 0 ? '(' . rupiah($persen_det52 * -1) . ')' : rupiah($persen_det52) }}</td>
                            <td align="right">{{$realisasi_lalu_det52 < 0 ? '(' . rupiah($realisasi_lalu_det52 * -1) . ')' : rupiah($realisasi_lalu_det52)}}</td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{$nm_rek}} Terdiri dari:</td>
                         <td align="right"></td>
                         <td align="right"></td>
                         <td align="right"></td>                            
                         <td align="center"></td>
                         <td align="right"></td>
                    </tr>
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                    
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>


        <!-- 53 -->
        @foreach($sql_skpd as $ahay)
            @php
                $kd_skpd        = $ahay->kd_skpd;
                $kd_rek         = $ahay->kd_rek;
                $nm_rek         = $ahay->nm_rek;
                $anggaran       = $ahay->anggaran;
                $realisasi      = $ahay->realisasi;
                $realisasi_lalu = $ahay->realisasi_lalu;
                $selisih        = $ahay->selisih;
                $persen         = $ahay->persen;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==53)
                @if($leng==2)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                @elseif($leng==4)
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                        <td align="right">{{rupiah($anggaran)}}</td>
                        <td align="right">{{rupiah($realisasi)}}</td>
                        <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                        <td align="center">{{rupiah($persen)}}</td>
                        <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        @foreach($sql_skpd as $ihiy)
            @php
                $kd_skpd        = $ihiy->kd_skpd;
                $kd_rek         = $ihiy->kd_rek;
                $nm_rek         = $ihiy->nm_rek;
                $anggaran       = $ihiy->anggaran;
                $realisasi      = $ihiy->realisasi;
                $realisasi_lalu = $ihiy->realisasi_lalu;
                $selisih        = $ihiy->selisih;
                $persen         = $ihiy->persen;
                $selisih_tahun         = $ihiy->selisih_tahun;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih_tahun<0){
                    $c = "(";
                    $selisihh_tahun = $selisih_tahun*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $selisihh_tahun = $selisih_tahun;
                    $d = "";
                }

                if($realisasi_lalu<$realisasi){
                    $naik_turun = "peningkatan";
                    
                }else if($realisasi==$realisasi_lalu){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "penurunan";
                } 

                
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==53)
                @if($leng==4)
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong></strong>&nbsp;</td>                            
                         <td align="center"><strong></strong>&nbsp;</td>
                         <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} memiliki anggaran belanja sebesar Rp. {{rupiah($anggaran)}} dan realisasi belanja sebesar Rp. {{rupiah($realisasi)}} Apabila dibandingkan dengan realisasi belanja Tahun Anggaran {{$thn_ang_1}} yang tercatat sebesar Rp.{{rupiah($realisasi_lalu)}} terjadi {{$naik_turun}} sebesar Rp. {{rupiah($selisihh_tahun)}} Rincian {{$nm_rek}} Tahun Anggaran {{$thn_ang}} sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    @php
                        $sql_det53 = DB::select("select *,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd, case when anggaran<>0 then realisasi/anggaran*100 else 0 end persen  , realisasi-anggaran selisih, realisasi-realisasi_lalu selisih_tahun
                        from
                        (
                            select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                            from(select kd_skpd,kd_rek,sum(anggaran)anggaran,sum(realisasi)realisasi
                                from (
                                    select kd_skpd,left(kd_rek6,4)kd_rek, sum(nilai) anggaran, 0 realisasi 
                                        from trdrka 
                                        where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                                        group by kd_skpd,left(kd_rek6,4)
                                        union all
                                        select b.kd_skpd, left(kd_rek6,4)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                        where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                                        group by kd_skpd,left(kd_rek6,4)
                                    )a
                                    group by kd_skpd,kd_rek
                                )a
                                LEFT JOIN
                                (select b.kd_skpd, LEFT(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                                    from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                    where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                                    group by b.kd_skpd, LEFT(a.kd_rek6,4)
                                ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                            group by a.kd_skpd,a.kd_rek
                        )a
                        where $skpd_clause and kd_rek='$kd_rek'
                        order by kd_skpd");
                        $no_det53 = 1;
                    @endphp
                    @foreach($sql_det53 as $det53)
                        @php
                            $kd_skpd_det53        = $det53->kd_skpd;
                            $nm_skpd_det53        = $det53->nm_skpd;
                            $kd_rek_det53         = $det53->kd_rek;
                            $anggaran_det53       = $det53->anggaran;
                            $realisasi_det53      = $det53->realisasi;
                            $realisasi_lalu_det53 = $det53->realisasi_lalu;
                            $selisih_det53        = $det53->selisih;
                            $persen_det53         = $det53->persen;
                            $selisih_tahun_det53  = $det53->selisih_tahun;
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="right">{{$no_det53++}})</td>                         
                            <td align="left">{{$nm_skpd_det53}}</td>
                            <td align="right">{{$anggaran_det53 < 0 ? '(' . rupiah($anggaran_det53 * -1) . ')' : rupiah($anggaran_det53) }}</td>
                            <td align="right">{{$realisasi_det53 < 0 ? '(' . rupiah($realisasi_det53 * -1) . ')' : rupiah($realisasi_det53) }}</td>
                            <td align="right">{{$selisih_det53 < 0 ? '(' . rupiah($selisih_det53 * -1) . ')' : rupiah($selisih_det53) }}</td>                            
                            <td align="center">{{$persen_det53 < 0 ? '(' . rupiah($persen_det53 * -1) . ')' : rupiah($persen_det53) }}</td>
                            <td align="right">{{$realisasi_lalu_det53 < 0 ? '(' . rupiah($realisasi_lalu_det53 * -1) . ')' : rupiah($realisasi_lalu_det51)}}</td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{$nm_rek}} Terdiri dari:</td>
                         <td align="right"></td>
                         <td align="right"></td>
                         <td align="right"></td>                            
                         <td align="center"></td>
                         <td align="right"></td>
                    </tr>
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                    
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>


        <!-- 54 -->
        @foreach($sql_skpd as $uhuy)
            @php
                $kd_skpd        = $uhuy->kd_skpd;
                $kd_rek         = $uhuy->kd_rek;
                $nm_rek         = $uhuy->nm_rek;
                $anggaran       = $uhuy->anggaran;
                $realisasi      = $uhuy->realisasi;
                $realisasi_lalu = $uhuy->realisasi_lalu;
                $selisih        = $uhuy->selisih;
                $persen         = $uhuy->persen;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==54)
                @if($leng==2)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                @elseif($leng==4)
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                        <td align="right">{{rupiah($anggaran)}}</td>
                        <td align="right">{{rupiah($realisasi)}}</td>
                        <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                        <td align="center">{{rupiah($persen)}}</td>
                        <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>
        @foreach($sql_skpd as $ehey)
            @php
                $kd_skpd        = $ehey->kd_skpd;
                $kd_rek         = $ehey->kd_rek;
                $nm_rek         = $ehey->nm_rek;
                $anggaran       = $ehey->anggaran;
                $realisasi      = $ehey->realisasi;
                $realisasi_lalu = $ehey->realisasi_lalu;
                $selisih        = $ehey->selisih;
                $persen         = $ehey->persen;
                $selisih_tahun         = $ehey->selisih_tahun;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih_tahun<0){
                    $c = "(";
                    $selisihh_tahun = $selisih_tahun*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $selisihh_tahun = $selisih_tahun;
                    $d = "";
                }

                if($realisasi_lalu<$realisasi){
                    $naik_turun = "peningkatan";
                    
                }else if($realisasi==$realisasi_lalu){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "penurunan";
                } 

                
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==54)
                @if($leng==4)
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>                         
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong>&nbsp;</strong></td>
                         <td align="center"><strong></strong>&nbsp;</td>                            
                         <td align="center"><strong></strong>&nbsp;</td>
                         <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($anggaran)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{$a}}{{rupiah($selisihh)}}{{$b}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} memiliki anggaran belanja sebesar Rp. {{rupiah($anggaran)}} dan realisasi belanja sebesar Rp. {{rupiah($realisasi)}} Apabila dibandingkan dengan realisasi belanja Tahun Anggaran {{$thn_ang_1}} yang tercatat sebesar Rp.{{rupiah($realisasi_lalu)}} terjadi {{$naik_turun}} sebesar Rp. {{rupiah($selisihh_tahun)}} Rincian {{$nm_rek}} Tahun Anggaran {{$thn_ang}} sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    @php
                        $sql_det54 = DB::select("select *,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd, case when anggaran<>0 then realisasi/anggaran*100 else 0 end persen  , realisasi-anggaran selisih, realisasi-realisasi_lalu selisih_tahun
                        from
                        (
                            select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                            from(select kd_skpd,kd_rek,sum(anggaran)anggaran,sum(realisasi)realisasi
                                from (
                                    select kd_skpd,left(kd_rek6,4)kd_rek, sum(nilai) anggaran, 0 realisasi 
                                        from trdrka 
                                        where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                                        group by kd_skpd,left(kd_rek6,4)
                                        union all
                                        select b.kd_skpd, left(kd_rek6,4)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                        where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                                        group by kd_skpd,left(kd_rek6,4)
                                    )a
                                    group by kd_skpd,kd_rek
                                )a
                                LEFT JOIN
                                (select b.kd_skpd, LEFT(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                                    from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                    where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                                    group by b.kd_skpd, LEFT(a.kd_rek6,4)
                                ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                            group by a.kd_skpd,a.kd_rek
                        )a
                        where $skpd_clause and kd_rek='$kd_rek'
                        order by kd_skpd");
                        $no_det54 = 1;
                    @endphp
                    @foreach($sql_det54 as $det54)
                        @php
                            $kd_skpd_det54        = $det54->kd_skpd;
                            $nm_skpd_det54        = $det54->nm_skpd;
                            $kd_rek_det54         = $det54->kd_rek;
                            $anggaran_det54       = $det54->anggaran;
                            $realisasi_det54      = $det54->realisasi;
                            $realisasi_lalu_det54 = $det54->realisasi_lalu;
                            $selisih_det54        = $det54->selisih;
                            $persen_det54         = $det54->persen;
                            $selisih_tahun_det54  = $det54->selisih_tahun;
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="right">{{$no_det54++}})</td>                         
                            <td align="left">{{$nm_skpd_det54}}</td>
                            <td align="right">{{$anggaran_det54 < 0 ? '(' . rupiah($anggaran_det54 * -1) . ')' : rupiah($anggaran_det54) }}</td>
                            <td align="right">{{$realisasi_det54 < 0 ? '(' . rupiah($realisasi_det54 * -1) . ')' : rupiah($realisasi_det54) }}</td>
                            <td align="right">{{$selisih_det54 < 0 ? '(' . rupiah($selisih_det54 * -1) . ')' : rupiah($selisih_det54) }}</td>                            
                            <td align="center">{{$persen_det54 < 0 ? '(' . rupiah($persen_det54 * -1) . ')' : rupiah($persen_det54) }}</td>
                            <td align="right">{{$realisasi_lalu_det54 < 0 ? '(' . rupiah($realisasi_lalu_det54 * -1) . ')' : rupiah($realisasi_lalu_det54)}}</td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong></strong>&nbsp;</td>                            
                        <td align="center"><strong></strong>&nbsp;</td>
                        <td align="center"><strong></strong>&nbsp;</td>
                    </tr>
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{$nm_rek}} Terdiri dari:</td>
                         <td align="right"></td>
                         <td align="right"></td>
                         <td align="right"></td>                            
                         <td align="center"></td>
                         <td align="right"></td>
                    </tr>
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisihh)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
                    </tr>
                    
                @endif
            @endif
        @endforeach
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong>&nbsp;</strong></td>
            <td align="center"><strong></strong>&nbsp;</td>                            
            <td align="center"><strong></strong>&nbsp;</td>
            <td align="center"><strong></strong>&nbsp;</td>
        </tr>

        
    </table>

</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_lra_pend') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>