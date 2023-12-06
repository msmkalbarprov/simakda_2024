<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB III LRA PENDAPATAN</title>
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
        @foreach($sql as $row)
            @php
                $kd_skpd        = $row->kd_skpd;
                $kd_rek         = $row->kd_rek;
                $nm_rek         = $row->nm_rek;
                $anggaran       = $row->anggaran;
                $realisasi      = $row->realisasi;
                $realisasi_lalu = $row->realisasi_lalu;
                $selisih        = $row->selisih;
                $persen         = $row->persen;
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
                    <td align="justify" colspan="7">{{$nm_rek}} - LRA pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}}  sampai dengan  {{$tanggal}} memiliki Target sebesar Rp. {{rupiah($anggaran)}} dan realisasi sebesar Rp. {{rupiah($realisasi)}} maka $naik_turun sebesar Rp. {{$a}}{{rupiah($selisihh)}}{{$b}} atau {{rupiah($persen)}}<strong>&nbsp;%</strong>.</td>                         
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
        @foreach($sql as $rew)
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
            @if(substr($kd_rek,0,2)==41)
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
        @foreach($sql as $riw)
            @php
                $kd_skpd        = $riw->kd_skpd;
                $kd_rek         = $riw->kd_rek;
                $nm_rek         = $riw->nm_rek;
                $anggaran       = $riw->anggaran;
                $realisasi      = $riw->realisasi;
                $realisasi_lalu = $riw->realisasi_lalu;
                $selisih        = $riw->selisih;
                $persen         = $riw->persen;
                $ket1         = $riw->ket1;
                $ket2         = $riw->ket2;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih<0){
                    $naik_turun = "tidak mencapai target";
                    
                }else if($selisih==0){
                    $naik_turun = "mencapai target";
                    
                }else{
                    $naik_turun = "melebihi target";
                    
                } 

                if($realisasi_lalu<$realisasi){
                    $naik_turun_banding = "kenaikan";
                }else if($realisasi_lalu==$realisasi){
                    $naik_turun_banding = "tidak terjadi perubahan";
                }else{
                    $naik_turun_banding = "penurunan";
                }

                if($ket1==''){
                    $edit ="";
                    $ket1="";
                }else{
                    $ket1=$ket1;
                    $edit ="";
                }
                
                if($ket2=='' or $ket2=='null'){
                    $ket2="";
                    $edit2 ="";
                }else{
                    $ket2=$ket2;
                    $edit2 ="";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==41)
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

                    @if($jenis==1)
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek}}')">Edit {{dotrek($kd_rek)}}</button>
                                <button type="button" value="Refresh" onClick="window.location.reload()">Reload {{dotrek($kd_rek)}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">- {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisih)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
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
                         <td align="justify" colspan="7">Target {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($anggaran)}} terealisasi sebesar Rp. {{rupiah($realisasi)}} atau {{rupiah($persen)}} maka {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @endif  
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
                         <td align="justify" colspan="7">Realisasi {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi tahun {{$thn_ang_1}} sebesar Rp. {{rupiah($realisasi_lalu)}} terjadi  {{$naik_turun_banding}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit}}{{$ket1}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit}}{{$ket1}}</td>
                        @endif            
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
                @endif
            @endif
        @endforeach

        @foreach($sql as $ruw)
            @php
                $kd_skpd        = $ruw->kd_skpd;
                $kd_rek         = $ruw->kd_rek;
                $nm_rek         = $ruw->nm_rek;
                $anggaran       = $ruw->anggaran;
                $realisasi      = $ruw->realisasi;
                $realisasi_lalu = $ruw->realisasi_lalu;
                $selisih        = $ruw->selisih;
                $persen         = $ruw->persen;
                $ket1         = $ruw->ket1;
                $ket2         = $ruw->ket2;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih<0){
                    $naik_turun = "tidak mencapai target";
                    
                }else if($selisih==0){
                    $naik_turun = "mencapai target";
                    
                }else{
                    $naik_turun = "melebihi target";
                    
                } 

                if($realisasi_lalu<$realisasi){
                    $naik_turun_banding = "kenaikan";
                }else if($realisasi_lalu==$realisasi){
                    $naik_turun_banding = "tidak terjadi perubahan";
                }else{
                    $naik_turun_banding = "penurunan";
                }

                if($ket1==''){
                    $edit ="";
                    $ket1="";
                }else{
                    $ket1=$ket1;
                    $edit ="";
                }
                
                if($ket2=='' or $ket2=='null'){
                    $ket2="";
                    $edit2 ="";
                }else{
                    $ket2=$ket2;
                    $edit2 ="";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==42)
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

                    @if($jenis==1)
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek}}')">Edit {{dotrek($kd_rek)}}</button>
                                <button type="button" value="Refresh" onClick="window.location.reload()">Reload {{dotrek($kd_rek)}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">- {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisih)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
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
                         <td align="justify" colspan="7">Target {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($anggaran)}} terealisasi sebesar Rp. {{rupiah($realisasi)}} atau {{rupiah($persen)}} maka {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @endif  
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
                         <td align="justify" colspan="7">Realisasi {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi tahun {{$thn_ang_1}} sebesar Rp. {{rupiah($realisasi_lalu)}} terjadi  {{$naik_turun_banding}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit}}{{$ket1}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit}}{{$ket1}}</td>
                        @endif            
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
                @endif
            @endif
        @endforeach

        @foreach($sql as $uhuy)
            @php
                $kd_skpd        = $uhuy->kd_skpd;
                $kd_rek         = $uhuy->kd_rek;
                $nm_rek         = $uhuy->nm_rek;
                $anggaran       = $uhuy->anggaran;
                $realisasi      = $uhuy->realisasi;
                $realisasi_lalu = $uhuy->realisasi_lalu;
                $selisih        = $uhuy->selisih;
                $persen         = $uhuy->persen;
                $ket1         = $uhuy->ket1;
                $ket2         = $uhuy->ket2;
                if($selisih<0){
                    $a = "(";
                    $selisihh = $selisih*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $selisihh = $selisih;
                    $b = "";
                }

                if($selisih<0){
                    $naik_turun = "tidak mencapai target";
                    
                }else if($selisih==0){
                    $naik_turun = "mencapai target";
                    
                }else{
                    $naik_turun = "melebihi target";
                    
                } 

                if($realisasi_lalu<$realisasi){
                    $naik_turun_banding = "kenaikan";
                }else if($realisasi_lalu==$realisasi){
                    $naik_turun_banding = "tidak terjadi perubahan";
                }else{
                    $naik_turun_banding = "penurunan";
                }

                if($ket1==''){
                    $edit ="";
                    $ket1="";
                }else{
                    $ket1=$ket1;
                    $edit ="";
                }
                
                if($ket2=='' or $ket2=='null'){
                    $ket2="";
                    $edit2 ="";
                }else{
                    $ket2=$ket2;
                    $edit2 ="";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if(substr($kd_rek,0,2)==43)
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

                    @if($jenis==1)
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek}}')">Edit {{dotrek($kd_rek)}}</button>
                                <button type="button" value="Refresh" onClick="window.location.reload()">Reload {{dotrek($kd_rek)}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                @elseif($leng==6)
                    <tr>
                         <td align="left">&nbsp;</td>                         
                         <td align="left"></td>                         
                         <td align="left">- {{$nm_rek}}</td>
                         <td align="right">{{rupiah($anggaran)}}</td>
                         <td align="right">{{rupiah($realisasi)}}</td>
                         <td align="right">{{$a}}{{rupiah($selisih)}}{{$b}}</td>                            
                         <td align="center">{{rupiah($persen)}}</td>
                         <td align="right">{{rupiah($realisasi_lalu)}}</td>
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
                         <td align="justify" colspan="7">Target {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($anggaran)}} terealisasi sebesar Rp. {{rupiah($realisasi)}} atau {{rupiah($persen)}} maka {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit2}}{{$ket2}}</td>
                        @endif  
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
                         <td align="justify" colspan="7">Realisasi {{$nm_rek}} pada tahun {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi tahun {{$thn_ang_1}} sebesar Rp. {{rupiah($realisasi_lalu)}} terjadi  {{$naik_turun_banding}} sebesar Rp. {{$a}}{{rupiah($selisih)}}{{$b}}</td>                         
                    </tr>
                    <tr>
                         <td align="left"><strong>&nbsp;</strong></td>
                         <td align="left"><strong>&nbsp;</strong></td>
                        @if($jenis==1)
                            <td align="justify" bgcolor="yellow" colspan="7">{{$edit}}{{$ket1}}</td>
                        @else
                            <td align="justify" colspan="7">{{$edit}}{{$ket1}}</td>
                        @endif            
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
                @endif
            @endif
        @endforeach
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