<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB III LO PENDAPATAN</title>
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
                <td align="left" colspan="2"><strong>Rincian dan Penjelasan masing-masing pos-pos laporan Keuangan SKPD.</strong></td>                         
            </tr>
            <tr>
                <td align="left" width="5%"><strong>&nbsp;</strong></td>                         
                <td align="left" width="10%"><strong>3.1.2.</strong></td>                         
                <td align="left"><strong>Laporan Operasional</strong></td>                         
            </tr>
        </table><br>
    @else
    @endif
    <table style="{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="left" width="2%" rowspan="2"><strong>&nbsp;</strong></td>                         
            <td style="border-top:solid;;border-bottom:solid;" align="center" width="10%" rowspan="2"><strong>Reff</strong></td>                         
            <td style="border-top:solid;;border-bottom:solid;" align="center" width="30%" rowspan="2"><strong>Uraian</strong></td>
            <td style="border-top:solid;" align="center" width="16%"><strong>Realisasi-LO {{$thn_ang}}</strong></td>
            <td style="border-top:solid;" align="center" width="16%"><strong>Realisasi-LO {{$thn_ang_1}}</strong></td>
            <td style="border-top:solid;" align="center" width="15%"><strong>Kenaikan / (Penurunan)</strong></td>                            
            <td style="border-top:solid;border-bottom:solid;" align="center" width="8%" rowspan="2"><strong>%</strong></td>
            <td style="border-top:solid;" align="center" width="15%"><strong>Realisasi-LRA {{$thn_ang_1}}</strong></td>
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
        @foreach($kode_7 as $row)
            @php
                $kd_skpd        = $row->kd_skpd;
                $kd_rek         = $row->kd_rek;
                $nm_rek         = $row->nm_rek;
                $realisasi      = $row->realisasi;
                $real_tlalu     = $row->real_tlalu;
                $real_lra       = $row->real_lra;
                $kenaikan       = $row->kenaikan;
                $persen         = $row->persen;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==1)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{$kd_rek}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
            @elseif($leng==2)
                <tr>
                    <td align="left">&nbsp;</td> 
                    <td align="right">&nbsp;</td>
                    <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                    <td align="right">{{rupiah($realisasi)}}</td>
                    <td align="right">{{rupiah($real_tlalu)}}</td>
                    <td align="right">{{$a}}{{rupiah($kenaikann)}}{{$b}}</td>                            
                    <td align="center">{{rupiah($persen)}}</td>
                    <td align="right">{{rupiah($real_lra)}}</td>
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

        <!-- 71 -->
        @foreach($kode_71 as $rew)
            @php
                $kd_skpd        = $rew->kd_skpd;
                $kd_rek         = $rew->kd_rek;
                $nm_rek         = $rew->nm_rek;
                $realisasi      = $rew->realisasi;
                $real_tlalu     = $rew->real_tlalu;
                $real_lra       = $rew->real_lra;
                $kenaikan       = $rew->kenaikan;
                $persen         = $rew->persen;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==2)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
            @elseif($leng==4)
                <tr>
                    <td align="left">&nbsp;</td> 
                    <td align="right">&nbsp;</td>
                    <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                    <td align="right">{{rupiah($realisasi)}}</td>
                    <td align="right">{{rupiah($real_tlalu)}}</td>
                    <td align="right">{{$a}}{{rupiah($kenaikann)}}{{$b}}</td>                            
                    <td align="center">{{rupiah($persen)}}</td>
                    <td align="right">{{rupiah($real_lra)}}</td>
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
        <!-- 71 detail -->
        @foreach($kode_71 as $riw)
            @php
                $kd_skpd        = $riw->kd_skpd;
                $kd_rek         = $riw->kd_rek;
                $nm_rek         = $riw->nm_rek;
                $realisasi      = $riw->realisasi;
                $real_tlalu     = $riw->real_tlalu;
                $real_lra       = $riw->real_lra;
                $kenaikan       = $riw->kenaikan;
                $persen         = $riw->persen;
                $banding         = $riw->banding;
                $lekur_lo         = $riw->lekur_lo;
                if($real_tlalu<$realisasi){
                    $naik_turun = "terjadi peningkatan";
                }else if($real_tlalu == $realisasi){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "terjadi penurunan";
                }
                
                if($realisasi != $real_lra){
                    $selisih = "perbedaan";
                }else{
                    $selisih = "persamaan";
                }

                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                if($banding<0){
                    $c = "(";
                    $bandingg = $banding*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $bandingg = $banding;
                    $d = "";
                }
                if($lekur_lo<0){
                    $e = "(";
                    $lekur_loo = $lekur_lo*-1;
                    $f = ")";
                }else{

                    $e = "";
                    $lekur_loo = $lekur_lo;
                    $f = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==4)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Provinsi Kalimantan Barat Tahun Anggaran {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi {{$nm_rek}} Tahun Anggaran {{$thn_ang_1}} sebesar Rp. {{rupiah($real_tlalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($kenaikann)}}{{$b}} atau {{rupiah($persen)}}%. Jika {{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dibandingkan dengan realisasi {{$nm_rek}} - LRA sebesar Rp. {{rupiah($real_lra)}} {{$selisih}} sebesar Rp. {{$c}}{{rupiah($banding)}}{{$d}}, dapat dijelaskan sebagai berikut :</td>                         
                </tr>
                @php
                    $kode_det = DB::select("SELECT no,c_kode,kode,nm_rek ,thn,kecuali
                                                from ket_lo_calk 
                                                where left(kd_rek,4)='$kd_rek'
                                                order by kd_rek");
                    $total = 0;
                @endphp

                @foreach($kode_det as $ruw)
                    @php
                        $no        = $ruw->no;
                        $c_kode    = $ruw->c_kode;
                        $kode      = $ruw->kode;
                        $nm_kode    = $ruw->nm_rek;
                        $thn       = $ruw->thn;
                        $kecuali       = $ruw->kecuali;
                        if($c_kode==""){
                            $c_kode="debet-debet";
                        }
                        if($kecuali==""){
                            $kecuali="xxx";
                        }
                        
                        $leng_kode = strlen($kode);
                        $leng_kecuali = strlen($kecuali);
                        $nilainya = collect(DB::select("SELECT SUM(nilai)nilai from(SELECT kd_skpd as kd_skpd,  sum($c_kode) nilai
                            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                            where LEFT(kd_rek6,$leng_kode) = '$kode'  and 
                            LEFT(kd_rek6,$leng_kecuali)!='$kecuali'  AND YEAR(tgl_voucher)$thn and $skpd_clause
                            group by kd_skpd
                            union all 
                            select '$kd_skpd' as kd_skpd, 0 nilai)a"))->first();
                        $realisasi_det = $nilainya->nilai; 
                        $awal_kode = substr($kode,0,1); 
                        $awal_rek = substr($kode,0,4);
                        if($awal_kode!=4 && $awal_rek==$kd_rek ){
                            $total=$total+$realisasi;
                        }
                    @endphp
                    
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>                         
                        <td align="left">{{$no}} {{$nm_kode}}</td>
                        <td align="right">{{rupiah($realisasi_det)}}</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>                         
                    <td align="left">- {{$nm_rek}} 2023</td>
                    <td align="right">{{rupiah($total+$real_lra)}}</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="center">&nbsp;</td>
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

        <!-- 72 -->
        @foreach($kode_72 as $ihiy)
            @php
                $kd_skpd        = $ihiy->kd_skpd;
                $kd_rek         = $ihiy->kd_rek;
                $nm_rek         = $ihiy->nm_rek;
                $realisasi      = $ihiy->realisasi;
                $real_tlalu     = $ihiy->real_tlalu;
                $real_lra       = $ihiy->real_lra;
                $kenaikan       = $ihiy->kenaikan;
                $persen         = $ihiy->persen;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==2)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
            @elseif($leng==4)
                <tr>
                    <td align="left">&nbsp;</td> 
                    <td align="right">&nbsp;</td>
                    <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                    <td align="right">{{rupiah($realisasi)}}</td>
                    <td align="right">{{rupiah($real_tlalu)}}</td>
                    <td align="right">{{$a}}{{rupiah($kenaikann)}}{{$b}}</td>                            
                    <td align="center">{{rupiah($persen)}}</td>
                    <td align="right">{{rupiah($real_lra)}}</td>
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
        <!-- 72 detail -->
        @foreach($kode_72 as $uhuy)
            @php
                $kd_skpd        = $uhuy->kd_skpd;
                $kd_rek         = $uhuy->kd_rek;
                $nm_rek         = $uhuy->nm_rek;
                $realisasi      = $uhuy->realisasi;
                $real_tlalu     = $uhuy->real_tlalu;
                $real_lra       = $uhuy->real_lra;
                $kenaikan       = $uhuy->kenaikan;
                $persen         = $uhuy->persen;
                $banding         = $uhuy->banding;
                $lekur_lo         = $uhuy->lekur_lo;
                if($real_tlalu<$realisasi){
                    $naik_turun = "terjadi peningkatan";
                }else if($real_tlalu == $realisasi){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "terjadi penurunan";
                }
                
                if($realisasi != $real_lra){
                    $selisih = "perbedaan";
                }else{
                    $selisih = "persamaan";
                }

                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                if($banding<0){
                    $c = "(";
                    $bandingg = $banding*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $bandingg = $banding;
                    $d = "";
                }
                if($lekur_lo<0){
                    $e = "(";
                    $lekur_loo = $lekur_lo*-1;
                    $f = ")";
                }else{

                    $e = "";
                    $lekur_loo = $lekur_lo;
                    $f = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==4)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Provinsi Kalimantan Barat Tahun Anggaran {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi {{$nm_rek}} Tahun Anggaran {{$thn_ang_1}} sebesar Rp. {{rupiah($real_tlalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($kenaikann)}}{{$b}} atau {{rupiah($persen)}}%. Jika {{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dibandingkan dengan realisasi {{$nm_rek}} - LRA sebesar Rp. {{rupiah($real_lra)}} {{$selisih}} sebesar Rp. {{$c}}{{rupiah($banding)}}{{$d}}, dapat dijelaskan sebagai berikut :</td>                         
                </tr>
                @php
                    $kode_det72 = DB::select("SELECT no,c_kode,kode,nm_rek ,thn,kecuali
                                                from ket_lo_calk 
                                                where left(kd_rek,4)='$kd_rek'
                                                order by kd_rek");
                    $total = 0;
                @endphp

                @foreach($kode_det72 as $ehey)
                    @php
                        $no        = $ehey->no;
                        $c_kode    = $ehey->c_kode;
                        $kode      = $ehey->kode;
                        $nm_kode    = $ehey->nm_rek;
                        $thn       = $ehey->thn;
                        $kecuali       = $ehey->kecuali;
                        if($c_kode==""){
                            $c_kode="debet-debet";
                        }
                        if($kecuali==""){
                            $kecuali="xxx";
                        }
                        
                        $leng_kode = strlen($kode);
                        $leng_kecuali = strlen($kecuali);
                        $nilainya = collect(DB::select("SELECT SUM(nilai)nilai from(SELECT kd_skpd as kd_skpd,  sum($c_kode) nilai
                            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                            where LEFT(kd_rek6,$leng_kode) = '$kode'  and 
                            LEFT(kd_rek6,$leng_kecuali)!='$kecuali'  AND YEAR(tgl_voucher)$thn and $skpd_clause
                            group by kd_skpd
                            union all 
                            select '$kd_skpd' as kd_skpd, 0 nilai)a"))->first();
                        $realisasi_det = $nilainya->nilai; 
                        $awal_kode = substr($kode,0,1); 
                        $awal_rek = substr($kode,0,4);
                        if($awal_kode!=4 && $awal_rek==$kd_rek ){
                            $total=$total+$realisasi;
                        }
                    @endphp
                    
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>                         
                        <td align="left">{{$no}} {{$nm_kode}}</td>
                        <td align="right">{{rupiah($realisasi_det)}}</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>                         
                    <td align="left">- {{$nm_rek}} 2023</td>
                    <td align="right">{{rupiah($total+$real_lra)}}</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="center">&nbsp;</td>
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

        <!-- 73 -->
        @foreach($kode_73 as $iri)
            @php
                $kd_skpd        = $iri->kd_skpd;
                $kd_rek         = $iri->kd_rek;
                $nm_rek         = $iri->nm_rek;
                $realisasi      = $iri->realisasi;
                $real_tlalu     = $iri->real_tlalu;
                $real_lra       = $iri->real_lra;
                $kenaikan       = $iri->kenaikan;
                $persen         = $iri->persen;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==2)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
            @elseif($leng==4)
                <tr>
                    <td align="left">&nbsp;</td> 
                    <td align="right">&nbsp;</td>
                    <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                    <td align="right">{{rupiah($realisasi)}}</td>
                    <td align="right">{{rupiah($real_tlalu)}}</td>
                    <td align="right">{{$a}}{{rupiah($kenaikann)}}{{$b}}</td>                            
                    <td align="center">{{rupiah($persen)}}</td>
                    <td align="right">{{rupiah($real_lra)}}</td>
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
        <!-- 73 detail -->
        @foreach($kode_73 as $uru)
            @php
                $kd_skpd        = $uru->kd_skpd;
                $kd_rek         = $uru->kd_rek;
                $nm_rek         = $uru->nm_rek;
                $realisasi      = $uru->realisasi;
                $real_tlalu     = $uru->real_tlalu;
                $real_lra       = $uru->real_lra;
                $kenaikan       = $uru->kenaikan;
                $persen         = $uru->persen;
                $banding         = $uru->banding;
                $lekur_lo         = $uru->lekur_lo;
                if($real_tlalu<$realisasi){
                    $naik_turun = "terjadi peningkatan";
                }else if($real_tlalu == $realisasi){
                    $naik_turun = "tidak terjadi perubahan";
                }else{
                    $naik_turun = "terjadi penurunan";
                }
                
                if($realisasi != $real_lra){
                    $selisih = "perbedaan";
                }else{
                    $selisih = "persamaan";
                }

                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
                }
                if($banding<0){
                    $c = "(";
                    $bandingg = $banding*-1;
                    $d = ")";
                }else{

                    $c = "";
                    $bandingg = $banding;
                    $d = "";
                }
                if($lekur_lo<0){
                    $e = "(";
                    $lekur_loo = $lekur_lo*-1;
                    $f = ")";
                }else{

                    $e = "";
                    $lekur_loo = $lekur_lo;
                    $f = "";
                }
                $leng = strlen($kd_rek);
            @endphp
            @if($leng==4)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$a}}{{rupiah($kenaikann)}}{{$b}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Provinsi Kalimantan Barat Tahun Anggaran {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi {{$nm_rek}} Tahun Anggaran {{$thn_ang_1}} sebesar Rp. {{rupiah($real_tlalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$a}}{{rupiah($kenaikann)}}{{$b}} atau {{rupiah($persen)}}%. Jika {{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dibandingkan dengan realisasi {{$nm_rek}} - LRA sebesar Rp. {{rupiah($real_lra)}} {{$selisih}} sebesar Rp. {{$c}}{{rupiah($banding)}}{{$d}}, dapat dijelaskan sebagai berikut :</td>                         
                </tr>
                @php
                    $kode_det73 = DB::select("SELECT no,c_kode,kode,nm_rek ,thn,kecuali
                                                from ket_lo_calk 
                                                where left(kd_rek,4)='$kd_rek'
                                                order by kd_rek");
                    $total = 0;
                @endphp

                @foreach($kode_det73 as $ere)
                    @php
                        $no        = $ere->no;
                        $c_kode    = $ere->c_kode;
                        $kode      = $ere->kode;
                        $nm_kode    = $ere->nm_rek;
                        $thn       = $ere->thn;
                        $kecuali       = $ere->kecuali;
                        if($c_kode==""){
                            $c_kode="debet-debet";
                        }
                        if($kecuali==""){
                            $kecuali="xxx";
                        }
                        
                        $leng_kode = strlen($kode);
                        $leng_kecuali = strlen($kecuali);
                        $nilainya = collect(DB::select("SELECT SUM(nilai)nilai from(SELECT kd_skpd as kd_skpd,  sum($c_kode) nilai
                            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                            where LEFT(kd_rek6,$leng_kode) = '$kode'  and 
                            LEFT(kd_rek6,$leng_kecuali)!='$kecuali'  AND YEAR(tgl_voucher)$thn and $skpd_clause
                            group by kd_skpd
                            union all 
                            select '$kd_skpd' as kd_skpd, 0 nilai)a"))->first();
                        $realisasi_det = $nilainya->nilai; 
                        $awal_kode = substr($kode,0,1); 
                        $awal_rek = substr($kode,0,4);
                        if($awal_kode!=4 && $awal_rek==$kd_rek ){
                            $total=$total+$realisasi;
                        }
                    @endphp
                    
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>                         
                        <td align="left">{{$no}} {{$nm_kode}}</td>
                        <td align="right">{{rupiah($realisasi_det)}}</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>                         
                    <td align="left">- {{$nm_rek}} 2023</td>
                    <td align="right">{{rupiah($total+$real_lra)}}</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="center">&nbsp;</td>
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