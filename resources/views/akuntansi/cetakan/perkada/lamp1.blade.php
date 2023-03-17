<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.4 URUSAN</title>
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

<body >
{{-- <body> --}}
    <table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I &nbsp;{{ strtoupper($nogub->ket_pergub) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_pergub_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_pergub_tentang) }}</TD>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>RINGKASAN LAPORAN REALISASI ANGGARAN</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>

    <hr>
    @if($kd_skpd=="")
    @else
    <table>
        <tr>
            <TD align="left" width="20%" >SKPD</TD>
            <TD align="left" width="80%" >: {{$kd_skpd}} {{nama_skpd($kd_skpd)}}</TD>
        </tr>
    </table>
    @endif
                    
                
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
                <thead>
                <tr>
                    <td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                    <td rowspan="2" width="32%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                    <td colspan="2" width="37%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan="2" width="23%" align="center" bgcolor="#CCCCCC" ><b>BERTAMBAH/KURANG</b></td>
                </tr>
                <tr>
                    <td width="19%" align="center" bgcolor="#CCCCCC" ><b>ANGGARAN</b></td>
                    <td width="18%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                    <td width="18%" align="center" bgcolor="#CCCCCC" ><b>(Rp)</b></td>
                    <td width="5%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
                    </tr>
                    <tr>
                   <td align="center" bgcolor="#CCCCCC" >1</td> 
                   <td align="center" bgcolor="#CCCCCC" >2</td> 
                   <td align="center" bgcolor="#CCCCCC" >3</td> 
                   <td align="center" bgcolor="#CCCCCC" >4</td> 
                   <td align="center" bgcolor="#CCCCCC" >5</td> 
                   <td align="center" bgcolor="#CCCCCC" >6</td> 
                </tr>
                </thead>
               @php
                    $ang_surplus = $sus->ang_surplus;
                    $nil_surplus = $sus->nil_surplus;
                    $ang_neto    = $sus->ang_neto;
                    $nil_neto    = $sus->nil_neto;
                    $sisa_surplus = $nil_surplus - $ang_surplus ;
                    $sisa_neto =  $nil_neto - $ang_neto ;
                    $ang_silpa = $ang_surplus+$ang_neto;
                    $nil_silpa = $nil_surplus+$nil_neto;
                    $sisa_silpa = $nil_silpa - $ang_silpa;

                        if (($ang_surplus == 0) || ($ang_surplus == '')) {
                            $persen_surplus = 0;
                        } else {
                            $persen_surplus = $nil_surplus / $ang_surplus * 100;
                        }
                        if (($ang_neto == 0) || ($ang_neto == '')) {
                            $persen_neto = 0;
                        }else {
                            $persen_neto = $nil_neto / $ang_neto * 100;
                        }
                        if (($ang_silpa == 0) || ($ang_silpa == '')) {
                            $persen_silpa = 0;
                        }else {
                            $persen_silpa = ($nil_silpa / $ang_silpa) * 100;
                        }

                        if ($ang_surplus < 0) {
                            $ang_surplus = $ang_surplus * -1;
                            $aa = '(';
                            $bb = ')';
                        }else {
                            $ang_surplus = $ang_surplus;
                            $aa = '';
                            $bb = '';
                        }

                        if ($nil_surplus < 0) {
                            $nil_surplus = $nil_surplus * -1;
                            $c = '(';
                            $d = ')';
                        } else {
                            $nil_surplus = $nil_surplus;
                            $c = '';
                            $d = '';
                        }

                        if ($ang_neto < 0) {
                            $ang_neto = $ang_neto * -1;
                            $e = '(';
                            $f = ')';
                        }else {
                            $ang_neto = $ang_neto;
                            $e = '';
                            $f = '';
                        }

                        if ($nil_neto < 0) {
                            $nil_neto = $nil_neto * -1;
                            $g = '(';
                            $h = ')';
                        } else {
                            $nil_neto = $nil_neto;
                            $g = '';
                            $h = '';
                        }

                        if ($sisa_surplus < 0) {
                            $sisa_surplus = $sisa_surplus * -1;
                            $i = '(';
                            $j = ')';
                        }else {
                            $sisa_surplus = $sisa_surplus;
                            $i = '';
                            $j = '';
                        }

                        if ($sisa_neto < 0) {
                            $sisa_neto = $sisa_neto * -1;
                            $k = '(';
                            $l = ')';
                        } else {
                            $sisa_neto = $sisa_neto;
                            $k = '';
                            $l = '';
                        }

                        if ($ang_silpa < 0) {
                            $ang_silpa = $ang_silpa * -1;
                            $m = '(';
                            $n = ')';
                        } else {
                            $ang_silpa = $ang_silpa;
                            $m = '';
                            $n = '';
                        }

                        if ($nil_silpa < 0) {
                            $nil_silpa = $nil_silpa * -1;
                            $o = '(';
                            $p = ')';
                        } else {
                            $nil_silpa = $nil_silpa;
                            $o = '';
                            $p = '';
                        }

                        if ($sisa_silpa < 0) {
                            $sisa_silpa = $sisa_silpa * -1;
                            $q = '(';
                            $r = ')';
                        } else {
                            $sisa_silpa = $sisa_silpa;
                            $q = '';
                            $r = '';
                        }
                    $total_terima   = 0;
                    $total_keluar   = 0;
                    $nomor          = 0;
                @endphp
                    @foreach ($rincian as $row)
                        @php
                            $kd_rek         = $row->kd_rek;
                            $nm_rek         = $row->nama;
                            $nil_ang        = $row->anggaran;
                            $group_id       = $row->group_id;
                            $bold           = $row->is_bold;
                            $show_kd_rek    = $row->is_show_kd_rek;
                            $right_align    = $row->is_right_align;
                
                            $realisasi   = $row->realisasi;
                            $sisa           = $realisasi - $nil_ang;
                            
                            if (($nil_ang == 0) || ($nil_ang == '')) {
                            $persen = 0;
                            } else {
                                $persen = $realisasi / $nil_ang * 100;
                            }
                            $sisa1          = $sisa < 0 ? $sisa * -1 : $sisa;
                            $a              = $sisa < 0 ? '(' : '';
                            $b              = $sisa < 0 ? ')' : '';
                            $leng           = strlen($kd_rek);
                        
                        if ($group_id==1 and $kd_rek==4) {
                            $nanggaran_pendapatan = $nil_ang;
                            $nrealisasi_pendapatan = $realisasi;
                        }else{
                            $nanggaran_pendapatan = $nil_ang;
                            $nrealisasi_pendapatan = $realisasi;
                        }
                        
                        if ($group_id==1 and $kd_rek==5) {
                            $nanggaran_belanja = $nil_ang;
                            $nrealisasi_belanja = $realisasi;
                        }else{
                            $nanggaran_belanja = $nil_ang;
                            $nrealisasi_belanja = $realisasi;
                        }


                        if ($group_id==2 and $row->kd_rek==61) {
                            $apenerimaan_pembiayaan = $nil_ang;
                            $bpenerimaan_pembiayaan = $realisasi;
                            
                        }else{
                            $apenerimaan_pembiayaan = $nil_ang;
                            $bpenerimaan_pembiayaan = $realisasi;
                            
                        }
                        
                        if ($group_id==2 and $row->kd_rek==62) {
                            $apengeluaran_pembiayaan=$nil_ang;
                            $bpengeluaran_pembiayaan=$realisasi;
                        }else{
                            $apengeluaran_pembiayaan=$nil_ang;
                            $bpengeluaran_pembiayaan=$realisasi;
                        }

                        $persenj         = !empty($nanggaran_pendapatan-$nanggaran_belanja) || ($nanggaran_pendapatan-$nanggaran_belanja) == 0 ? 0 : (($nrealisasi_pendapatan-$nrealisasi_belanja)/($nanggaran_pendapatan-$nanggaran_belanja)) * 100;

                        $persenpem         = !empty($apenerimaan_pembiayaan-$apengeluaran_pembiayaan) || ($apenerimaan_pembiayaan-$apengeluaran_pembiayaan) == 0 ? 0 : (($bpenerimaan_pembiayaan-$bpengeluaran_pembiayaan)/($apenerimaan_pembiayaan-$apengeluaran_pembiayaan)) * 100;





                    @endphp
                    @if ($show_kd_rek==1)
                        @php
                            $kd_rek=$kd_rek;
                        @endphp
                    @else
                        @php
                            $kd_rek="";
                        @endphp
                    @endif
                    


                              
                        @if ($group_id == 0)
                        <tr>
                            <td style="font-size:14px;font-family:Open Sans" colspan="6">&nbsp;</td>
                        </tr>
                        @if ($row->kd_rek == 45)       
                            <tr>
                                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right"  valign="top" style="padding-left: 10px"><b>{{$nm_rek}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$aa}}{{ rupiah($ang_surplus) }}{{$bb}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$c}}{{ rupiah($nil_surplus) }}{{$d}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$i}}{{ rupiah($sisa_surplus) }}{{$j}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen_surplus, "2", ",", ".")}}</b></td> 
                            </tr>
                        @elseif ($row->kd_rek == 6263)
                            <tr>
                                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right"  valign="top" style="padding-left: 10px"><b>{{$nm_rek}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$e}}{{ rupiah($ang_neto) }}{{$f}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$i}}{{ rupiah($nil_neto) }}{{$j}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$k}}{{ rupiah($sisa_neto) }}{{$l}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen_neto, "2", ",", ".")}}</b></td> 
                            </tr>        
                        @else
                        <tr>
                            <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right"  valign="top"><b>{{$nm_rek}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                        </tr>         
                        @endif
                    
            @elseif ($group_id == 1)
                    @if ($row->kd_rek== 5 || $row->kd_rek== 6 || $group_id==0)
                        <tr>
                            <td style="font-size:14px;font-family:Open Sans" colspan="7">&nbsp;</td>
                        </tr>
                        @if ($right_align==1)
                            <tr>
                                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                            </tr>        
                        @else
                            <tr>
                                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                            </tr>    
                        @endif
                        
                    @else
                        <tr>
                            <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                            <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                        </tr>    
                    @endif
                    
            @elseif ($group_id == 2)
                <tr>
                    <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top" style="padding-left: 10px"><b>{{$nm_rek}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                </tr>     
            @elseif ($group_id == 3)
            <tr>
                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top" style="padding-left: 60px"><b>{{$nm_rek}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
            </tr>
                        
            @elseif ($group_id == 4)
                
            <tr>
                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top" style="padding-left: 20px"><b>{{$nm_rek}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
            </tr>   
            
            @else
            <tr>
                <td style="font-size:14px;font-family:Open Sans" align="left" valign="top">{{dotrek($kd_rek)}}</td> 
                <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top" style="padding-left: 50px">{{$nm_rek}}</td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top">{{number_format($nil_ang, "2", ",", ".")}}</td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top">{{number_format($realisasi, "2", ",", ".")}}</td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                <td style="font-size:14px;font-family:Open Sans" align="right" valign="top">{{number_format($persen, "2", ",", ".")}}</td> 
            </tr>
            @endif   
                        
                @endforeach
                @php
                    $silpa_anggaran = $nanggaran_pendapatan-$nanggaran_belanja+$apenerimaan_pembiayaan-$apengeluaran_pembiayaan;
                    $silpa_belanja = $nrealisasi_pendapatan-$nrealisasi_belanja+$bpenerimaan_pembiayaan-$bpengeluaran_pembiayaan; 
                    if($silpa_anggaran !=0 && $silpa_belanja!=0){
                        $persensilpa = $silpa_belanja/$silpa_anggaran*100;
                    }elseif($silpa_anggaran == 0 || $silpa_belanja !=0){
                        $persensilpa = 100;
                    }elseif($silpa_anggaran !=0 || $silpa_belanja ==0){
                        $persensilpa = 0;
                    }else{
                        $persensilpa = 0;
                    }
                @endphp
                <tr>
                    <td style="font-size:14px;font-family:Open Sans" align="left" valign="top"></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="left"  valign="top" style="padding-left: 50px"><b>SISA LEBIH PEMBIYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$m}}{{ rupiah($ang_silpa) }}{{$n}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$o}}{{ rupiah($nil_silpa) }}{{$p}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{$q}}{{ rupiah($sisa_silpa) }}{{$r}}</b></td> 
                    <td style="font-size:14px;font-family:Open Sans" align="right" valign="top"><b>{{rupiah($persen_silpa)}}</b></td> 
                </tr>

    </table>
    </div>

    {{-- tanda tangan --}}
    
</body>

</html>
