<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB III LO BEBAN</title>
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
            <td style="border-top:solid;" align="center" width="15%"><strong>Realisasi-LRA {{$thn_ang}}</strong></td>
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
        @foreach($kode_8 as $row)
            @php
                $kd_rek         = $row->kd_rek;
                $nm_rek         = $row->nm_rek;
                $realisasi      = $row->realisasi;
                $real_tlalu     = $row->real_tlalu;
                $real_lra       = $row->real_lra;
                $kenaikan       = $row->kenaikan;
                $persen         = $row->persen;
                $lekur_lo         = $row->lekur_lo;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
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
            @if($leng==1)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{$kd_rek}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$e}}{{rupiah($lekur_loo)}}{{$f}}</strong></td>
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
                    <td align="right">{{$e}}{{rupiah($lekur_loo)}}{{$f}}</td>                            
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

        <!-- 81 -->
        @foreach($kode_81 as $ihiy)
            @php
                $kd_rek         = $ihiy->kd_rek;
                $nm_rek         = $ihiy->nm_rek;
                $realisasi      = $ihiy->realisasi;
                $real_tlalu     = $ihiy->real_tlalu;
                $real_lra       = $ihiy->real_lra;
                $kenaikan       = $ihiy->kenaikan;
                $persen         = $ihiy->persen;
                $lekur_lo         = $ihiy->lekur_lo;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
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
            @if($leng==2)
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                    <td align="left"><strong>{{$nm_rek}}</strong></td>
                    <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    <td align="right"><strong>{{$e}}{{rupiah($lekur_loo)}}{{$f}}</strong></td>
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
                    <td align="right">{{$e}}{{rupiah($lekur_loo)}}{{$f}}</td>                            
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
        <!-- 81 detail -->
        @foreach($kode_81 as $uhuy)
            @php
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
                    <td align="right"><strong>{{$e}}{{rupiah($lekur_loo)}}{{$f}}</strong></td>
                    <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                    <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi {{$nm_rek}} Tahun Anggaran {{$thn_ang_1}} sebesar Rp. {{rupiah($real_tlalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$e}}{{rupiah($lekur_loo)}}{{$f}} atau {{rupiah($persen)}}%. Jika {{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dibandingkan dengan realisasi {{$nm_rek}} - LRA sebesar Rp. {{rupiah($real_lra)}} perbedaan sebesar Rp. {{$c}}{{rupiah($banding)}}{{$d}}, dapat dijelaskan sebagai berikut :</td>                         
                </tr>
                @if($kd_rek=="8101")
                <!-- 8101-->
                    @if($jenis==1)
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek}}')">Edit {{$nm_rek}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @php
                        $det_81 = DB::select("select * from calk_map_bab3_lo_beban z where kd_rek='$kd_rek' order by urut");
                        $total = 0;
                    @endphp
                    @foreach($det_81 as $det_81)
                        @php
                            $kd_rek_det = $det_81->kd_rek;
                            $no_det = $det_81->no;
                            $uraian_det = $det_81->uraian;
                            $thn_det = $det_81->thn;
                            $c_kode_det = $det_81->c_kode;
                            $kode_det = $det_81->kode;
                            $tabel_det = $det_81->tabel;
                            $edit_det = $det_81->edit;
                            if($tabel_det!="jurnal"){
                                $c_kode_det="debet-debet";
                            }
                            
                            $nilai_det = collect(DB::select("SELECT 
                                (case 
                                    when '$tabel_det'='jurnal' 
                                        then (select sum($c_kode_det)nilai 
                                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                            where $skpd_clause and YEAR(tgl_voucher)$thn_det and left(kd_rek6,len($kode_det))='$kode_det') 
                                    when '$tabel_det'='trkapitalisasi' 
                                        then (select isnull(sum(nilai_trans),0)*-1 nilai 
                                            from trkapitalisasi 
                                            where jenis<>'X' and nilai_trans<>0 and $skpd_clause and left(kd_rek6,len($kode_det))='$kode_det')
                                    when '$tabel_det'='trkapitalisasi' and '$kd_skpd'='3.27.0.00.0.00.03.0003'
                                        then (select sum(isnull(nilai,0))
                                            from nilai_beban_calk where $skpd_clause and kd_rek='8102') 
                                    when '$tabel_det' = 'nilai_beban_calk' 
                                        then (select sum(isnull(nilai,0)) nilai from nilai_beban_calk where $skpd_clause and kd_rek='$kode_det') else 0 end
                                ) nilai
                            "))->first();
                            $nilainya_det = $nilai_det->nilai;
                            $awal_kode = substr($kode_det,0,1); 
                            $awal_rek = substr($kd_rek_det,0,4);
                            $total=$total+$nilainya_det;

                        @endphp
                        
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="left">&nbsp;</td>
                            @if($no_det!="-")
                                <td align="left">&nbsp; {{$uraian_det}}</td>
                                <td align="right">{{rupiah($nilainya_det)}}</td>
                            @else
                                <td align="left"><strong>{{$no_det}} {{$uraian_det}}</strong></td>
                                <td align="right"><strong>{{rupiah($nilainya_det)}}</strong></td>
                            @endif                         
                            <td align="left">&nbsp;</td>                            
                            <td align="left">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                        </tr>
                    @endforeach 
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>                         
                        <td align="left">- {{$nm_rek}} 2023</td>
                        <td align="right">{{rupiah($total)}}</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                <!-- -->
                @elseif($kd_rek=="8102")
                <!--8102-->
                    @php
                        $no_8102 = 0;
                    @endphp
                    @foreach($kode_8102 as $k8102)
                        @php
                            $no_8102=$no_8102+1;
                            $nm_rek_8102 = $k8102->nm_rek;
                            $realisasi_8102 = $k8102->realisasi;
                            $real_tlalu_8102 = $k8102->real_tlalu;
                            $real_lra_8102 = $k8102->real_lra;
                            $lekur_lo_8102 = $k8102->lekur_lo;
                            $persen_8102 = $k8102->persen;

                            if($lekur_lo_8102 <0){
                                $e_8102 = "(";
                                $lekur_loo_8102 = $lekur_lo_8102*-1;
                                $f_8102 = ")";
                            }else{

                                $e_8102 = "";
                                $lekur_loo_8102 = $lekur_lo_8102;
                                $f_8102 = "";
                            }
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td> 
                            <td align="right">&nbsp;</td>
                            <td align="left">{{$no_8102}} {{$nm_rek_8102}}</td>
                            <td align="right">{{rupiah($realisasi_8102)}}</td>
                            <td align="right">{{rupiah($real_tlalu_8102)}}</td>
                            <td align="right">{{$e_8102}}{{rupiah($lekur_loo_8102)}}{{$f_8102}}</td>
                            <td align="center">{{rupiah($persen_8102)}}</td>
                            <td align="right">{{rupiah($real_lra_8102)}}</td>
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
                    @php
                        $no_det8102 = 0;
                    @endphp
                    @foreach($kode_8102 as $kdet8102)
                        @php
                            $no_det8102=$no_det8102+1;
                            $kd_rek_det8102 = $kdet8102->kd_rek;
                            $nm_rek_det8102 = $kdet8102->nm_rek;
                            $nm_rek_bel_det8102 = $kdet8102->nm_rek_bel;
                            $realisasi_det8102 = $kdet8102->realisasi;
                            $real_tlalu_det8102 = $kdet8102->real_tlalu;
                            $real_lra_det8102 = $kdet8102->real_lra;
                            $lekur_lo_det8102 = $kdet8102->lekur_lo;
                            $banding_det8102 = $kdet8102->banding;
                            $persen_det8102 = $kdet8102->persen;

                            if($lekur_lo_det8102 <0){
                                $e_det8102 = "(";
                                $lekur_loo_det8102 = $lekur_lo_det8102*-1;
                                $f_det8102 = ")";
                            }else{

                                $e_det8102 = "";
                                $lekur_loo_det8102 = $lekur_lo;
                                $f_det8102 = "";
                            }
                            if($banding_det8102<0 || $banding_det8102>0){
                                $beda_det8102 = "terjadi perbedaan";
                            }else{
                                $beda_det8102 = "tidak terjadi perbedaan";
                            }
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td> 
                            <td align="right">&nbsp;</td>
                            <td align="left"><strong>{{$no_det8102}} {{$nm_rek_det8102}}</strong></td>
                            <td align="right"><strong>{{rupiah($realisasi_det8102)}}</strong></td>
                            <td align="right"><strong>{{rupiah($real_tlalu_det8102)}}</strong></td>
                            <td align="right"><strong>{{$e_det8102}}{{rupiah($lekur_loo_det8102)}}{{$f_det8102}}</strong></td>
                            <td align="center"><strong>{{rupiah($persen_det8102)}}</strong></td>
                            <td align="right"><strong>{{rupiah($real_lra_det8102)}}</strong></td>
                        </tr>
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="7">Jika {{$nm_rek_det8102}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp.{{rupiah($realisasi_det8102)}} dibandingkan dengan realisasi {{$nm_rek_bel_det8102}} - LRA sebesar Rp. {{rupiah($real_lra_det8102)}} {{$beda_det8102}} sebesar Rp. {{rupiah($banding_det8102)}}, dapat dijelaskan sebagai beriku :</td>                         
                        </tr>
                        <tr>
                            <td align="left"></td>                         
                            <td valign="top" align="right">-</td>                         
                            <td align="left"><b>{{$nm_rek_bel_det8102}} - LRA {{$thn_ang}}</td>
                            <td align="right"><b>{{rupiah($real_lra_det8102)}}</td>
                            <td align="right"></td>
                            <td align="right"></td>                            
                            <td align="center"></td>
                            <td align="right"></td>
                        </tr>
                        @if($jenis==1)
                            <tr>
                                 <td align="left"><strong>&nbsp;</strong></td>
                                 <td align="left"><strong>&nbsp;</strong></td>
                                 <td align="justify" colspan="7">
                                    <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek_det8102}}')">Edit {{$nm_rek_det8102}}</button>
                                </td>                         
                            </tr>
                        @else
                        @endif
                        @if($kd_rek_det8102=="810201")
                            @php
                                $arr_az = "a";
                                $tot_810201 = 0;
                            @endphp
                            @foreach($det_810201 as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_810201 = $tot_810201+$nilai; 
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_810201+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @elseif($kd_rek_det8102=="810202")
                            @php
                                $arr_az = "a";
                                $tot_810202 = 0;
                            @endphp
                            @foreach($det_810202 as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_810202 = $tot_810202+$nilai;
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_810202+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @elseif($kd_rek_det8102=="810203")
                            @php
                                $arr_az = "a";
                                $tot_810203 = 0;
                            @endphp
                            @foreach($det_810203 as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_810203 = $tot_810203+$nilai;
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_810203+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @elseif($kd_rek_det8102=="810204")
                            @php
                                $arr_az = "a";
                                $tot_810204 = 0;
                            @endphp
                            @foreach($det_810204 as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_810204 = $tot_810204+$nilai;
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_810204+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @elseif($kd_rek_det8102=="810299")
                            @php
                                $arr_az = "a";
                                $tot_810299 = 0;
                            @endphp
                            @foreach($det_810299 as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_810299 = $tot_810299+$nilai;
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_810299+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @else
                            @php
                                $arr_az_l = "a";
                                $tot_8102_l = 0;
                                $det_8102_l = DB::select("select kd_rek,nm_rek,sum(nilai) nilai
                                        from(
                                            select left(piutang_utang,8) kd_rek,concat((select nm_rek5 from ms_rek5 where kd_rek5=left(piutang_utang,8)),' Tahun Anggaran $thn_ang')nm_rek , 0 nilai
                                            from ms_rek6 a where left(map_lo,6)='$kd_rek_det8102'
                                            union all
                                            select left(piutang_utang,8) kd_rek,concat((select nm_rek5 from ms_rek5 where kd_rek5=left(piutang_utang,8)),' Tahun Anggaran $thn_ang_1')nm_rek , 0 nilai
                                            from ms_rek6 a where left(map_lo,6)='$kd_rek_det8102'
                                            union all
                                            select left(c.piutang_utang,8)kd_rek,concat((select nm_rek5 from ms_rek5 where kd_rek5=left(c.piutang_utang,8)),' Tahun Anggaran $thn_ang')nm_rek, sum(isnull(kredit,0)-isnull(debet,0)) nilai
                                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.piutang_utang
                                            where year(tgl_voucher)=$thn_ang and left(map_lo,6)='$kd_rek_det8102' and $skpd_clause
                                            group by left(c.piutang_utang,8)
                                            union all
                                            select left(c.piutang_utang,8)kd_rek,concat((select nm_rek5 from ms_rek5 where kd_rek5=left(c.piutang_utang,8)),' Tahun Anggaran $thn_ang_1')nm_rek, sum(isnull(debet,0)-isnull(kredit,0)) nilai
                                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.piutang_utang
                                            where year(tgl_voucher)=$thn_ang_1 and left(map_lo,6)='$kd_rek_det8102' and $skpd_clause
                                            group by left(c.piutang_utang,8)
                                        )a
                                        group by kd_rek,nm_rek order by kd_rek");
                            @endphp
                            @foreach($det_8102_l as $rowsc)
                                @php
                                    $kd_rek        = $rowsc->kd_rek;
                                    $nm_rek        = $rowsc->nm_rek;
                                    $nilai         = $rowsc->nilai;
                                    $tot_8102_l = $tot_8102_l+$nilai;
                                @endphp
                                <tr>
                                     <td align="left">&nbsp;</td>                         
                                     <td align="right" valign="top">{{$arr_az_l++}}.</td>            
                                     <td align="left" valign="top" >{{$nm_rek}}</td>
                                     <td align="right" valign="top" >{{rupiah($nilai)}}</td>
                                     <td align="right"></td>
                                     <td align="right"></td>                            
                                     <td align="center"></td>
                                     <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek_det8102}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($tot_8102_l+$real_lra_det8102)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                    @endforeach
                <!---->
                @elseif($kd_rek=="8107" || $kd_rek=="8108")
                    @php
                        $no_8178=0;
                        $det_8178 = DB::select("SELECT  kd_rek, nm_rek, realisasi, real_tlalu, (real_tlalu-realisasi)kenaikan,
                                                           case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                        from(
                            SELECT kd_rek,nm_rek,sum(realisasi)realisasi,sum(real_tlalu)real_tlalu, 0 real_lra
                            from(
                                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,6) as kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=LEFT(kd_rek6,6)) as nm_rek, sum(debet-kredit) as realisasi, 0 real_tlalu , 0 real_lra
                                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                where YEAR(tgl_voucher)=$thn_ang and  LEFT(kd_rek6,4) IN ('$kd_rek') 
                                group by kd_skpd, LEFT(kd_rek6,6)
                                union all
                                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,6) as kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=LEFT(kd_rek6,6)) as nm_rek,   0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra
                                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                where YEAR(tgl_voucher)=$thn_ang_1 and  LEFT(kd_rek6,4) IN ('$kd_rek') 
                                group by kd_skpd, LEFT(kd_rek6,6)
                            )a
                            where $skpd_clause
                            group by kd_rek,nm_rek
                        )a");
                    @endphp
                    @foreach($det_8178 as $riw78)
                        @php
                            $no_8178=$no_8178+1;
                            $nm_rek_8178 = $riw78->nm_rek;
                            $realisasi_8178 = $riw78->realisasi;
                            $real_tlalu_8178 = $riw78->real_tlalu;
                            $real_lra_8178 = $riw78->real_lra;
                            $lekur_lo_8178 = $riw78->lekur_lo;
                            $persen_8178 = $riw78->persen;

                            if($lekur_lo_8178 <0){
                                $e_8178 = "(";
                                $lekur_loo_8178 = $lekur_lo_8178*-1;
                                $f_8178 = ")";
                            }else{

                                $e_8178 = "";
                                $lekur_loo_8178 = $lekur_lo;
                                $f_8178 = "";
                            }
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td> 
                            <td align="right">&nbsp;</td>
                            <td align="left">{{$no_8178}} {{$nm_rek_8178}}</td>
                            <td align="right">{{rupiah($realisasi_8178)}}</td>
                            <td align="right">{{rupiah($real_tlalu_8178)}}</td>
                            <td align="right">{{$e_8178}}{{rupiah($lekur_loo_8178)}}{{$f_8178}}</td>
                            <td align="center">{{rupiah($persen_8178)}}</td>
                            <td align="right">{{rupiah($real_lra_8178)}}</td>
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
                @else
                    @php
                        $no_81_l=0;
                        $det_81_l = DB::select("select kd_rek,nm_rek,nm_rek_bel,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                                   case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                            from(
                                Select kd_rek,nm_rek,nm_rek_bel,sum(realisasi)realisasi,sum(real_lalu)real_tlalu,sum(real_lra)real_lra
                                from(
                                    select kd_skpd,left(a.kd_rek6,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(a.kd_rek6,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel, sum(isnull(debet,0)-isnull(kredit,0)) realisasi,0 real_lalu,0 real_lra
                                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.map_lo
                                    where year(tgl_voucher)=2023 and left(a.kd_rek6,4)='$kd_rek'
                                    group by kd_skpd,left(a.kd_rek6,6),left(c.kd_rek6,6)
                                    union all
                                    select kd_skpd,left(a.kd_rek6,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(a.kd_rek6,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel,0 realisasi ,sum(isnull(debet,0)-isnull(kredit,0)) real_lalu,0 real_lra
                                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.map_lo
                                    where year(tgl_voucher)=2022 and left(a.kd_rek6,4)='$kd_rek'
                                    group by kd_skpd,left(a.kd_rek6,6),left(c.kd_rek6,6)
                                    union all
                                    select kd_skpd,left(c.map_lo,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.map_lo,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel,0 realisasi ,0 real_lalu,sum(isnull(debet,0)-isnull(kredit,0)) real_lra
                                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.map_lo
                                    where year(tgl_voucher)=2023 and left(map_lo,4)='$kd_rek' 
                                    group by kd_skpd,left(c.map_lo,6),left(c.kd_rek6,6)
                                )a
                                where $skpd_clause
                                group by kd_rek,nm_rek,nm_rek_bel
                            )a
                            order by kd_rek
                            ");
                    @endphp
                    @foreach($det_81_l as $riwsc)
                        @php
                            $no_81_l=$no_81_l+1;
                            $nm_rek_81_l = $riwsc->nm_rek;
                            $realisasi_81_l = $riwsc->realisasi;
                            $real_tlalu_81_l = $riwsc->real_tlalu;
                            $real_lra_81_l = $riwsc->real_lra;
                            $lekur_lo_81_l = $riwsc->lekur_lo;
                            $persen_81_l = $riwsc->persen;

                            if($lekur_lo_81_l <0){
                                $e_81_l = "(";
                                $lekur_loo_81_l = $lekur_lo_81_l*-1;
                                $f_81_l = ")";
                            }else{

                                $e_81_l = "";
                                $lekur_loo_81_l = $lekur_lo;
                                $f_81_l = "";
                            }
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td> 
                            <td align="right">&nbsp;</td>
                            <td align="left">{{$no_81_l}} {{$nm_rek_81_l}}</td>
                            <td align="right">{{rupiah($realisasi_81_l)}}</td>
                            <td align="right">{{rupiah($real_tlalu_81_l)}}</td>
                            <td align="right">{{$e_81_l}}{{rupiah($lekur_loo_81_l)}}{{$f_81_l}}</td>
                            <td align="center">{{rupiah($persen_81_l)}}</td>
                            <td align="right">{{rupiah($real_lra_81_l)}}</td>
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

        <!-- 82-84 -->
        @foreach($kode_8 as $row)
            @php
                $kd_rek         = $row->kd_rek;
                $nm_rek         = $row->nm_rek;
                $realisasi      = $row->realisasi;
                $real_tlalu     = $row->real_tlalu;
                $real_lra       = $row->real_lra;
                $kenaikan       = $row->kenaikan;
                $persen         = $row->persen;
                $lekur_lo         = $row->lekur_lo;
                if($kenaikan<0){
                    $a = "(";
                    $kenaikann = $kenaikan*-1;
                    $b = ")";
                }else{

                    $a = "";
                    $kenaikann = $kenaikan;
                    $b = "";
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
            @if($leng!=1)
                @if($kd_rek!="81")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                        <td align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                        <td align="right"><strong>{{$e}}{{rupiah($lekur_loo)}}{{$f}}</strong></td>
                        <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                        <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                    </tr>
                    @php
                        $kode_8284 = DB::select("SELECT kd_skpd, kd_rek, nm_rek, realisasi, real_tlalu, (real_tlalu-realisasi)kenaikan,
                                   case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                            from(
                                SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
                                from(
                                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,  sum(debet-kredit) realisasi, 0 as real_tlalu , 0 real_lra
                                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                    where LEFT(kd_rek6,2) IN ('$kd_rek')  AND YEAR(tgl_voucher)='$thn_ang'
                                    group by kd_skpd, LEFT(kd_rek6,4)
                                    union all
                                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,   0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra
                                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                    where LEFT(kd_rek6,2) IN ('$kd_rek')  AND YEAR(tgl_voucher)='$thn_ang_1'
                                    group by kd_skpd, LEFT(kd_rek6,4)
                                    union all
                                    SELECT kd_skpd as kd_skpd, LEFT(map_lo,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(map_lo,4)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(debet-kredit) real_lra
                                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    where LEFT(map_lo,2) IN ('$kd_rek') AND YEAR(tgl_voucher)='$thn_ang'
                                    group by kd_skpd, LEFT(map_lo,4)
                                )a
                                where $skpd_clause
                                group by kd_skpd,kd_rek,nm_rek
                            )a
                            order by kd_skpd,kd_rek,nm_rek");
                    @endphp
                    @foreach($kode_8284 as $k8284)
                        @php
                            $kd_skpd        = $k8284->kd_skpd;
                            $kd_rek         = $k8284->kd_rek;
                            $nm_rek       = $k8284->nm_rek;
                            $realisasi      = $k8284->realisasi;
                            $real_tlalu     = $k8284->real_tlalu;
                            $real_lra       = $k8284->real_lra;
                            $kenaikan       = $k8284->kenaikan;
                            $persen         = $k8284->persen;
                            $banding         = $k8284->banding;
                            $lekur_lo         = $k8284->lekur_lo;
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
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="left">&nbsp;</td>                         
                            <td align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                            <td align="right">{{rupiah($realisasi)}}</td>
                            <td align="right">{{rupiah($real_tlalu)}}</td>
                            <td align="right">{{$e}}{{rupiah($lekur_loo)}}{{$f}}</td>
                            <td align="center">{{rupiah($persen)}}</td>
                            <td align="right">{{rupiah($real_lra)}}</td>
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
                    @foreach($kode_8284 as $k8284)
                        @php
                            $kd_skpd        = $k8284->kd_skpd;
                            $kd_rek         = $k8284->kd_rek;
                            $nm_rek       = $k8284->nm_rek;
                            $realisasi      = $k8284->realisasi;
                            $real_tlalu     = $k8284->real_tlalu;
                            $real_lra       = $k8284->real_lra;
                            $kenaikan       = $k8284->kenaikan;
                            $persen         = $k8284->persen;
                            $banding         = $k8284->banding;
                            $lekur_lo         = $k8284->lekur_lo;
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
                            $kd2    = substr($kd_rek,0,2);
                        @endphp
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>                         
                            <td align="left"><strong>{{dotrek($kd_rek)}}</strong></td>                         
                            <td align="left"><strong>{{$nm_rek}}</strong></td>
                            <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                            <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                            <td align="right"><strong>{{$e}}{{rupiah($lekur_loo)}}{{$f}}</strong></td>
                            <td align="center"><strong>{{rupiah($persen)}}</strong></td>
                            <td align="right"><strong>{{rupiah($real_lra)}}</strong></td>
                        </tr>
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="7">{{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dan realisasi {{$nm_rek}} Tahun Anggaran {{$thn_ang_1}} sebesar Rp. {{rupiah($real_tlalu)}} terjadi {{$naik_turun}} sebesar Rp. {{$e}}{{rupiah($lekur_loo)}}{{$f}} atau {{rupiah($persen)}}%. Jika {{$nm_rek}} pada {{$nm_skpd}} Tahun Anggaran  {{$thn_ang}} sebesar Rp. {{rupiah($realisasi)}} dibandingkan dengan realisasi {{$nm_rek}} - LRA sebesar Rp. {{rupiah($real_lra)}} {{$selisih}} sebesar Rp. {{$c}}{{rupiah($banding)}}{{$d}}, dapat dijelaskan sebagai berikut :</td>
                        </tr>
                        @if($kd2=="82")
                            @php
                                $nm_bel_82 = collect(DB::select("SELECT * FROM (SELECT left(kd_rek6,4)kd_rek3,(select nm_rek3 from ms_rek3 where kd_rek3=left(kd_rek6,4))nm_rek3,left(map_lo,4)kd_lo3 
                                    from ms_rek6
                                    where left(kd_rek6,1)='5'
                                    group by left(kd_rek6,4),left(map_lo,4))a where kd_lo3='$kd_rek'"))->first();
                                $nm_rek3_bel =  $nm_bel_82->nm_rek3;
                                
                                $det_8284 = DB::select("SELECT kd_rek,nm_rek,sum(nilai) nilai
                                    from(
                                        select left(piutang_utang,6) kd_rek,concat((select nm_rek4 from ms_rek4 where kd_rek4=left(piutang_utang,6)),' Tahun Anggaran $thn_ang')nm_rek , 0 nilai
                                        from ms_rek6 a where left(map_lo,4)='$kd_rek'
                                        union all
                                        select left(piutang_utang,6) kd_rek,concat((select nm_rek4 from ms_rek4 where kd_rek4=left(piutang_utang,6)),' Tahun Anggaran $thn_ang_1')nm_rek , 0 nilai
                                        from ms_rek6 a where left(map_lo,4)='$kd_rek'
                                        union all
                                        select left(c.piutang_utang,6)kd_rek,concat((select nm_rek4 from ms_rek4 where kd_rek4=left(c.piutang_utang,6)),' Tahun Anggaran $thn_ang')nm_rek, sum(isnull(kredit,0)-isnull(debet,0)) nilai
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.piutang_utang
                                        where year(tgl_voucher)=$thn_ang and left(c.map_lo,4)='$kd_rek' and $skpd_clause
                                        group by left(c.piutang_utang,6)
                                        union all
                                        select left(c.piutang_utang,6)kd_rek,concat((select nm_rek4 from ms_rek4 where kd_rek4=left(c.piutang_utang,6)),' Tahun Anggaran $thn_ang_1')nm_rek, sum(isnull(debet,0)-isnull(kredit,0)) nilai
                                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.piutang_utang
                                        where year(tgl_voucher)=$thn_ang_1 and left(c.map_lo,4)='$kd_rek' and $skpd_clause
                                        group by left(c.piutang_utang,6)
                                    )a
                                    group by kd_rek,nm_rek");
                                $az_8284 = "a";
                                $tot_det_8282 = 0;
                            @endphp
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek3_bel}} - LRA {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($real_lra)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
                            </tr>
                            @foreach($det_8284 as $k_8284)
                                @php
                                    $kd_rek_det_8284 = $k_8284->kd_rek;
                                    $nm_rek_det_8284 = $k_8284->nm_rek;
                                    $nilai_det_8284 = $k_8284->nilai;
                                    $tot_det_8282=$tot_det_8282+$nilai_det_8284;
                                @endphp
                                <tr>
                                    <td align="left"></td>                         
                                    <td valign="top" align="right">{{$az_8284++}}.</td>                         
                                    <td align="left">{{$nm_rek_det_8284}}</td>
                                    <td align="right">{{rupiah($nilai_det_8284)}}</td>
                                    <td align="right"></td>
                                    <td align="right"></td>                            
                                    <td align="center"></td>
                                    <td align="right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left"></td>                         
                                <td valign="top" align="right">-</td>                         
                                <td align="left"><b>{{$nm_rek}} - LO {{$thn_ang}}</td>
                                <td align="right"><b>{{rupiah($real_lra+$tot_det_8282)}}</td>
                                <td align="right"></td>
                                <td align="right"></td>                            
                                <td align="center"></td>
                                <td align="right"></td>
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
                        @else
                            @php
                                $no_det_8384=0;
                                $det_det_8384 = DB::select("SELECT kd_skpd, kd_rek, nm_rek, realisasi, real_tlalu, (real_tlalu-realisasi)kenaikan,
                                                                   case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                                from(
                                    SELECT kd_skpd,kd_rek,c.nm_rek4 nm_rek,sum(realisasi)realisasi,sum(real_tlalu)real_tlalu, 0 real_lra
                                    from(
                                        SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,6) as kd_rek, sum(debet-kredit) as realisasi, 0 real_tlalu , 0 real_lra
                                        from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                        where YEAR(tgl_voucher)=$thn_ang and  LEFT(kd_rek6,4) IN ('$kd_rek') 
                                        group by kd_skpd, LEFT(kd_rek6,6)
                                        union all
                                        SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,6) as kd_rek,   0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra
                                        from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                                        where YEAR(tgl_voucher)=$thn_ang_1 and  LEFT(kd_rek6,4) IN ('$kd_rek') 
                                        group by kd_skpd, LEFT(kd_rek6,6)
                                    )a inner join ms_rek4 c on a.kd_rek=c.kd_rek4 
                                    where $skpd_clause
                                    group by kd_skpd,kd_rek,c.nm_rek4
                                )a");
                            @endphp
                            @foreach($det_det_8384 as $riw8384)
                                @php
                                    $no_det_8384=$no_det_8384+1;
                                    $nm_rek_det_8384 = $riw8384->nm_rek;
                                    $realisasi_det_8384 = $riw8384->realisasi;
                                    $real_tlalu_det_8384 = $riw8384->real_tlalu;
                                    $real_lra_det_8384 = $riw8384->real_lra;
                                    $lekur_lo_det_8384 = $riw8384->lekur_lo;
                                    $persen_det_8384 = $riw8384->persen;

                                    if($lekur_lo_det_8384 <0){
                                        $e_det_8384 = "(";
                                        $lekur_loo_det_8384 = $lekur_lo_det_8384*-1;
                                        $f_det_8384 = ")";
                                    }else{

                                        $e_det_8384 = "";
                                        $lekur_loo_det_8384 = $lekur_lo;
                                        $f_det_8384 = "";
                                    }
                                @endphp
                                <tr>
                                    <td align="left">&nbsp;</td> 
                                    <td align="right">&nbsp;</td>
                                    <td align="left">{{$no_det_8384}} {{$nm_rek_det_8384}}</td>
                                    <td align="right">{{rupiah($realisasi_det_8384)}}</td>
                                    <td align="right">{{rupiah($real_tlalu_det_8384)}}</td>
                                    <td align="right">{{$e_det_8384}}{{rupiah($lekur_loo_det_8384)}}{{$f_det_8384}}</td>
                                    <td align="center">{{rupiah($persen_det_8384)}}</td>
                                    <td align="right">{{rupiah($real_lra_det_8384)}}</td>
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
                @else
                @endif
            @else
            @endif
        @endforeach
        
    </table>

</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_lo_beban') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>