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
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.1 &nbsp;{{ strtoupper($nogub->ket_pergub) }}</TD>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>PENJABARAN LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>

    <hr>
    @if(strlen($kd_skpd)==22)
    <table>
        <tr>
            <TD align="left" width="20%" > Urusan Pemerintahan</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,1)}} {{nama_urusan(substr($kd_skpd,0,1))}}</TD>
        </tr>
        <tr>
            <TD align="left" width="20%" >Bidang Pemerintahan</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,4)}} {{nama_bidang(substr($kd_skpd,0,4))}}</TD>
        </tr>
        <tr>
            <TD align="left" width="20%" >Unit Organisasi</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,17)}} {{nama_org(substr($kd_skpd,0,17))}}</TD>
        </tr>
        <tr>
            <TD align="left" width="20%" >Sub Unit Organisasi</TD>
            <TD align="left" width="80%" >: {{$kd_skpd}} {{nama_skpd($kd_skpd)}}</TD>
        </tr>
    </table>
    @else
    <table>
        <tr>
            <TD align="left" width="20%" > Urusan Pemerintahan</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,1)}} {{nama_urusan(substr($kd_skpd,0,1))}}</TD>
        </tr>
        <tr>
            <TD align="left" width="20%" >Bidang Pemerintahan</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,4)}} {{nama_bidang(substr($kd_skpd,0,4))}}</TD>
        </tr>
        <tr>
            <TD align="left" width="20%" >Unit Organisasi</TD>
            <TD align="left" width="80%" >: {{substr($kd_skpd,0,17)}} {{nama_org(substr($kd_skpd,0,17))}}</TD>
        </tr>
    </table>
    @endif
                    
                
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
                <thead>
                <tr>
                    <td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                    <td rowspan="2" width="25%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                    <td colspan="2" width="45%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" ><b>BERTAMBAH(BERKURANG)</b></td>
                    <td rowspan="2" width="18%" align="center" bgcolor="#CCCCCC" ><b>DASAR HUKUM</b></td>
                    <td rowspan="2" width="18%" align="center" bgcolor="#CCCCCC" ><b>KETERANGAN</b></td>
                    
                </tr>
                <tr>
                    <td width="15%" align="center" bgcolor="#CCCCCC" ><b>ANGGARAN</b></td>
                    <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                    <td width="15%" align="center" bgcolor="#CCCCCC" ><b>Rp.</b></td>
                    <td width="5%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
                    </tr>
                    <tr>
                   <td align="center" bgcolor="#CCCCCC" >1</td> 
                   <td align="center" bgcolor="#CCCCCC" >2</td> 
                   <td align="center" bgcolor="#CCCCCC" >3</td> 
                   <td align="center" bgcolor="#CCCCCC" >4</td> 
                   <td align="center" bgcolor="#CCCCCC" >5=(4-3)</td> 
                   <td align="center" bgcolor="#CCCCCC" >6</td> 
                   <td align="center" bgcolor="#CCCCCC" >7</td> 
                   <td align="center" bgcolor="#CCCCCC" >8</td> 
                </tr>
                </thead>

        @foreach($rincian_pend as $row)
            @php
                $kd_sub_kegiatan_p = $row->kd_sub_kegiatan;
                $kd_rek_p = $row->kd_rek;
                $nm_rek_p = $row->nm_rek;
                $nil_ang_p = $row->anggaran;
                $sd_bulan_ini_p = $row->sd_bulan_ini;
                $sisa_p = $row->sisa;
                if (($nil_ang_p == 0) || ($nil_ang_p == '')) {
                    $persen_p = 0;
                }else {
                    $persen_p = $sd_bulan_ini_p / $nil_ang_p * 100;
                }
                $sisa1_p = $sisa_p<0 ? $sisa_p*-1 :$sisa_p;
                $asis_p = $sisa_p<0 ? '(' :'';
                $bsis_p = $sisa_p<0 ? ')' :'';
                       
                $leng_p = strlen($kd_rek_p);
            @endphp
            @if ($leng_p == 2)       
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                    <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                    <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>
            @elseif ($leng_p == 4) 
                @if($kd_rek_p=='4101')
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                        <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                        <td align="left" valign="top">{{$hukum_4101}}</td> 
                        <td align="right" valign="top"></td> 
                    </tr>
                @elseif($kd_rek_p=='4102')
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                        <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                        <td align="left" valign="top">{{$hukum_4102}}</td> 
                        <td align="right" valign="top"></td> 
                    </tr>        
                @else
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                        <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                        <td align="right" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>
                @endif
            @elseif ($leng_p == 6) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                    <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                    <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>        
            @elseif ($leng_p == 8) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                    <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                    <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>        
            @elseif ($leng_p == 12) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                    <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                    <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>        
            @else
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_p}}.{{dotrek($kd_rek_p)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek_p}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang_p)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini_p)}}</td> 
                    <td align="right" valign="top">{{$asis_p}}{{rupiah($sisa1_p)}}{{$bsis_p}}</td> 
                    <td align="right" valign="top">{{rupiah($persen_p)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>       
            @endif
        @endforeach    
        //jumlah pendapatan
        @php
            $nil_ang_pj         = $tot_pend->anggaran;
            $sd_bulan_ini_pj    = $tot_pend->sd_bulan_ini;
            $sisa_pj            = $tot_pend->sisa;
            if (($nil_ang_pj == 0) || ($nil_ang_pj == '')) {
                $persen_pj = 0;
            }else {
                $persen_pj = $sd_bulan_ini_pj / $nil_ang_pj * 100;
            }
            $sisa1_pj = $sisa_pj<0 ? $sisa_pj*-1 :$sisa_pj;
            $asis_pj = $sisa_pj<0 ? '(' :'';
            $bsis_pj = $sisa_pj<0 ? ')' :'';
        @endphp
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
            <td align="right" valign="top"><b>{{rupiah($nil_ang_pj)}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini_pj)}}</b></td> 
            <td align="right" valign="top"><b>{{$asis_pj}}{{rupiah($sisa1_pj)}}{{$bsis_pj}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($persen_pj)}}</b></td> 
            <td align="left" valign="top"><b></b></td>
            <td align="left" valign="top"><b></b></td> 
        </tr>
        //belanja daerah
        @php
            $nil_ang_bd         = $belda->anggaran;
            $sd_bulan_ini_bd    = $belda->sd_bulan_ini;
            $sisa_bd            = $belda->sisa;
            $nilai_sp2d_bd      = $belda->nilai_sp2d;
            $sisa_kas_bd        = $belda->sisa_kas;
            $nil_cp             = $belda->nilai_cp;
            if (($nil_ang_bd == 0) || ($nil_ang_bd == '')) {
                $persen_bd = 0;
            }else {
                $persen_bd = $sd_bulan_ini_bd / $nil_ang_bd * 100;
            }
            $sisa1_bd = $sisa_bd<0 ? $sisa_bd*-1 :$sisa_bd;
            $asis_bd = $sisa_bd<0 ? '(' :'';
            $bsis_bd = $sisa_bd<0 ? ')' :'';
        @endphp
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
            <td align="right" valign="top"><b>{{rupiah($nil_ang_bd)}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini_bd)}}</b></td> 
            <td align="right" valign="top"><b>{{$asis_bd}}{{rupiah($sisa1_bd)}}{{$bsis_bd}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($persen_bd)}}</b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
        </tr>
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="left" valign="top"><b></b></td> 
            <td align="right" valign="top"><b>SP2D : {{rupiah($nilai_sp2d_bd)}}</td> 
        </tr>
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="left" valign="top"><b></b></td> 
            <td align="right" valign="top"><b>CP : {{rupiah($nil_cp)}}</td> 
        </tr>
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="right" valign="top"><b></b></td> 
            <td align="left" valign="top"><b></b></td> 
            <td align="right" valign="top"><b>SISA KAS = SP2D - ( SPJ + CP ) : {{rupiah($sisa_kas_bd)}}</td> 
        </tr>

        //belanja
        @foreach($rincian_bel as $row)
            @php
                $urut                   = $row->urut;
                $kd_sub_kegiatan        = $row->kd_sub_kegiatan;
                $kd_sub_kegiatan_potong = substr($row->kd_sub_kegiatan,0,15);
                $kd_rek                 = $row->kd_rek;
                $nm_rek                 = $row->nm_rek;
                $nil_ang                = $row->anggaran;
                $sd_bulan_ini           = $row->sd_bulan_ini;
                $sisa                   = $row->sisa;
                if (($nil_ang == 0) || ($nil_ang == '')) {
                $persen = 0;
                }else {
                    $persen = $sd_bulan_ini_bd / $nil_ang * 100;
                }
                $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
                $asis = $sisa<0 ? '(' :'';
                $bsis = $sisa<0 ? ')' :'';
                $leng=strlen($kd_rek);
            @endphp
            @if ($leng == 2)       
                <tr>
                    <td align="left" valign="top"><b>{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</b></td> 
                    <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                    <td align="right" valign="top"><b>{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>
            @elseif ($leng == 4) 
                <tr>
                    <td align="left" valign="top"><b>{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</b></td> 
                    <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                    <td align="right" valign="top"><b>{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>
            @elseif ($leng == 6) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                    <td align="right" valign="top">{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</td> 
                    <td align="right" valign="top">{{rupiah($persen)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>       
            @elseif ($leng == 8) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                    <td align="right" valign="top">{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</td> 
                    <td align="right" valign="top">{{rupiah($persen)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>        
            @elseif ($leng == 12) 
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                    <td align="right" valign="top">{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</td> 
                    <td align="right" valign="top">{{rupiah($persen)}}</td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>         
            @else
                <tr>
                    <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                    <td align="left"  valign="top">{{$nm_rek}}</td> 
                    <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                    <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                    <td align="right" valign="top">{{$asis}}{{rupiah($sisa1)}}{{$bsis}}</td> 
                    <td align="right" valign="top">{{rupiah($persen)}}</td> 
                    <td align="left" valign="top">{{$hukum_1}}</td> 
                    <td align="right" valign="top"></td> 
                </tr>      
            @endif
        @endforeach
        <tr>
            <td align="left" valign="top"></td> 
            <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
            <td align="right" valign="top"><b>{{rupiah($nil_ang_bd)}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini_bd)}}</b></td> 
            <td align="right" valign="top"><b>{{$asis_bd}}{{rupiah($sisa1_bd)}}{{$bsis_bd}}</b></td> 
            <td align="right" valign="top"><b>{{rupiah($persen_bd)}}</b></td> 
            <td align="right" valign="top"><b></td> 
            <td align="right" valign="top"></td>  
        </tr>

        //jika bkad ada pembiayaan
        @if($kd_skpd=='5.02.0.00.0.00.02.0000')
            @foreach($rincian_pem as $row)
                @php
                    $urut_pem                       = $row->urut;
                    $kd_sub_kegiatan_pem            = $row->kd_sub_kegiatan;
                    $kd_sub_kegiatan_potong_pem    = substr($row->kd_sub_kegiatan,0,15);
                    $kd_rek_pem                     = $row->kd_rek;
                    $nm_rek_pem                     = $row->nm_rek;
                    $nil_ang_pem                    = $row->anggaran;
                    $sd_bulan_ini_pem               = $row->sd_bulan_ini;
                    $sisa_pem                       = $row->sisa;
                    if (($nil_ang_pem == 0) || ($nil_ang_pem == '')) {
                        $persen_pem = 0;
                    }else {
                        $persen_pem = $sd_bulan_ini_pem / $nil_ang_pem * 100;
                    }
                    $sisa1_pem = $sisa_pem<0 ? $sisa_pem*-1 :$sisa_pem;
                    $asis_pem = $sisa_pem<0 ? '(' :'';
                    $bsis_pem = $sisa_pem<0 ? ')' :'';
                    $leng=strlen($kd_rek_pem);
                @endphp
                @if ($leng == 2)       
                <tr>
                    <td align="left" valign="top"><b>{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</b></td> 
                    <td align="left"  valign="top"><b>{{$nm_rek_pem}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($nil_ang_pem)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                    <td align="right" valign="top"><b>{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen_pem)}}</b></td> 
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top"></td> 
                </tr>
                @elseif ($leng == 4) 
                    <tr>
                        <td align="left" valign="top"><b>{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</b></td> 
                        <td align="left"  valign="top"><b>{{$nm_rek_pem}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($nil_ang_pem)}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini_pem)}}</b></td> 
                        <td align="right" valign="top"><b>{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($persen_pem)}}</b></td> 
                        <td align="right" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>
                @elseif ($leng == 6) 
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_pem)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_pem)}}</td> 
                        <td align="right" valign="top">{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_pem)}}</td> 
                        <td align="right" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>       
                @elseif ($leng == 8) 
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_pem)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_pem)}}</td> 
                        <td align="right" valign="top">{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_pem)}}</td> 
                        <td align="right" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>        
                @elseif ($leng == 12) 
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_pem)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_pem)}}</td> 
                        <td align="right" valign="top">{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_pem)}}</td> 
                        <td align="right" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>         
                @else
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan_potong_pem}}.{{dotrek($kd_rek_pem)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($nil_ang_pem)}}</td> 
                        <td align="right" valign="top">{{rupiah($sd_bulan_ini_pem)}}</td> 
                        <td align="right" valign="top">{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</td> 
                        <td align="right" valign="top">{{rupiah($persen_pem)}}</td> 
                        <td align="left" valign="top"></td> 
                        <td align="right" valign="top"></td> 
                    </tr>      
                @endif
            @endforeach
            //belanja daerah
            @php
                $nil_ang_pem         = $tot_pem->anggaran;
                $sd_bulan_ini_pem    = $tot_pem->sd_bulan_ini;
                $sisa_pem            = $tot_pem->sisa;
                if (($nil_ang_pem == 0) || ($nil_ang_pem == '')) {
                    $persen_pem = 0;
                }else {
                    $persen_pem = $sd_bulan_ini_pem / $nil_ang_pem * 100;
                }
                $sisa1_pem = $sisa_pem<0 ? $sisa_pem*-1 :$sisa_pem;
                $asis_pem = $sisa_pem<0 ? '(' :'';
                $bsis_pem = $sisa_pem<0 ? ')' :'';
            @endphp
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>JUMLAH PEMBIAYAAN</b></td> 
                <td align="right" valign="top"><b>{{rupiah($nil_ang_pem)}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini_pem)}}</b></td> 
                <td align="right" valign="top"><b>{{$asis_pem}}{{rupiah($sisa1_pem)}}{{$bsis_pem}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($persen_pem)}}</b></td> 
                <td align="right" valign="top"><b></b></td> 
                <td align="right" valign="top"><b></b></td> 
            </tr>
        @endif


    </table>
    </div>

    {{-- tanda tangan --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center"></td>
        </tr>
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center">Pontianak, {{tgl_format_oyoy($tgl_ttd)}}<br>GUBERNUR KALIMANTAN BARAT<br><br><br><br><br><b><u>SUTARMIDJI</u></b>
            </td>
        </tr>
    </table>
</body>

</html>
