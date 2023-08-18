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
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.4 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_perda_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_perda_tentang) }}</TD>
        </TR>
    </table>
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td rowspan="4" align="center" style="border-right:hidden" width="25%">
                    <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
                </td>
                <td align="center" width="50%" style="border-left:hidden;border-right:hidden;border-bottom:hidden"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
                <td rowspan="4" align="center" style="border-left:hidden" width="25%">
                    &nbsp;
                </td>
                
            </tr>
            <tr>
                <td align="center" width="50%" style="border-left:hidden;border-right:hidden;border-bottom:hidden;border-top:hidden" ><strong>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</strong></td>
            </tr>
            <tr>
                <td align="center" width="50%" style="border-left:hidden;border-right:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>

    <hr>
 
    {{-- isi --}}
    <table  style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
                    <td rowspan = "2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">KODE</td>
                    <td rowspan = "2" width="35%" align="center" bgcolor="#CCCCCC" style="font-size:12px">URUSAN PEMERINTAH DAERAH</td>
                    <td colspan = "8" width="45%" align="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN BELANJA</td>
                    <td rowspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">JUMLAH</td>
                    <td colspan = "8" width="45%" align="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI BELANJA</td>
                    <td rowspan = "2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">JUMLAH</td>
                </tr>
                <tr>
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">PEGAWAI</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BARANG DAN JASA</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">MODAL</td>
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">HIBAH</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BANTUAN SOSIAL</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BAGI HASIL</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BANTUAN KEUANGAN</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BELANJA TIDAK TERDUGA</td>  
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">PEGAWAI</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BARANG DAN JASA</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">MODAL</td>
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">HIBAH</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BANTUAN SOSIAL</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BAGI HASIL</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BANTUAN KEUANGAN</td> 
                   <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">BELANJA TIDAK TERDUGA</td>  
                <tr>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">11</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">12</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">13</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">14</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">15</td>
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">16</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">17</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">18</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">19</td> 
                   <td align="center" bgcolor="#CCCCCC" style="font-size:12px">20</td>  
                </tr>
            </thead>
                @php
                  
                    $total_apeg     =0;
                    $total_abarjas      =0;
                    $total_amod     =0;
                    $total_ahibah       =0;
                    $total_abansos      =0;
                    $total_abghasil     =0;
                    $total_abankeu      =0;
                    $total_abtt     =0;
                    $total_aja      =0;
                    $total_rpeg     =0;
                    $total_rbarjas      =0;
                    $total_rmod     =0;
                    $total_rhibah       =0;
                    $total_rbansos      =0;
                    $total_rbghasil     =0;
                    $total_rbankeu      =0;
                    $total_rbtt     =0;
                    $total_rja      =0;
					$nomor          = 0;
                @endphp
                    @foreach ($rincian as $row)
                        @php
                               $kode = $row->kode;
                               $nm_rek = $row->nm_rek;
                               $ang_peg = $row->ang_peg;
                               $ang_brng = $row->ang_brng;
                               $ang_mod = $row->ang_mod;
                               $ang_hibah = $row->ang_hibah;
                               $ang_bansos = $row->ang_bansos;
                               $ang_bghasil = $row->ang_bghasil;
                               $ang_bankeu = $row->ang_bankeu;
                               $ang_btt = $row->ang_btt;
                               $real_peg = $row->real_peg;
                               $real_brng = $row->real_brng;
                               $real_mod = $row->real_mod;
                               $real_hibah = $row->real_hibah;
                               $real_bansos = $row->real_bansos;
                               $real_bghasil = $row->real_bghasil;
                               $real_bankeu = $row->real_bankeu;
                               $real_btt = $row->real_btt;
                               
                               $tot_ang=$ang_peg+$ang_brng+$ang_mod+$ang_hibah+$ang_bansos+$ang_bghasil+$ang_bankeu+$ang_btt;
                               $tot_real=$real_peg+$real_brng+$real_mod+$real_hibah+$real_bansos+$real_bghasil+$real_bankeu+$real_btt;
                            
                            
                      $len = strlen($kode); 

                        @endphp


                    

                        @php
                            $total_apeg       =$total_apeg+$ang_peg;
                            $total_abarjas    =$total_abarjas+$ang_brng;
                            $total_amod       =$total_amod+$ang_mod;
                            $total_ahibah     =$total_ahibah+$ang_hibah;
                            $total_abansos    =$total_abansos+$ang_bansos;
                            $total_abghasil   =$total_abghasil+$ang_bghasil;
                            $total_abankeu    =$total_abankeu+$ang_bankeu;
                            $total_abtt       =$total_abtt+$ang_btt;
                            
                            $total_rpeg       =$total_rpeg+$real_peg;
                            $total_rbarjas    =$total_rbarjas+$real_brng;
                            $total_rmod       =$total_rmod+$real_mod;
                            $total_rhibah     =$total_rhibah+$real_hibah;
                            $total_rbansos    =$total_rbansos+$real_bansos;
                            $total_rbghasil   =$total_rbghasil+$real_bghasil;
                            $total_rbankeu    =$total_rbankeu+$real_bankeu;
                            $total_rbtt       =$total_rbtt+$real_btt;
                            
                        @endphp
                        <tr>
                               <td align="left" valign="top" style="font-size:12px">{{$kode}}</td> 
                               <td align="left"  valign="top" style="font-size:12px">{{$nm_rek}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_peg)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_brng)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_mod)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_hibah)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_bansos)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_bghasil)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_bankeu)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_btt)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_peg+$ang_brng+$ang_mod+$ang_hibah+$ang_bansos+$ang_bghasil+$ang_bankeu+$ang_btt)}}</td> 

                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_peg)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_brng)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_mod)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_hibah)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_bansos)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_bghasil)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_bankeu)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_btt)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($real_peg+$real_brng+$real_mod+$real_hibah+$real_bansos+$real_bghasil+$real_bankeu+$real_btt)}}</td> 
                            </tr>     
                    
                @endforeach

                            <tr>
                               <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_apeg)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_abarjas)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_amod)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_ahibah)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_abansos)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_abghasil)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_abankeu)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_abtt)}}
                               </td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_apeg+$total_abarjas+$total_amod+$total_ahibah+$total_abansos+$total_abghasil+$total_abankeu+$total_abtt)}}</td> 

                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rpeg)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rbarjas)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rmod)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rhibah)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rbansos)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rbghasil)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rbankeu)}}</td>
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rbtt)}}</td> 
                               <td align="right" valign="top" style="font-size:12px">{{rupiah($total_rpeg+$total_rbarjas+$total_rmod+$total_rhibah+$total_rbansos+$total_rbghasil+$total_rbankeu+$total_rbtt)}}</td> 
                            </tr>     

    </table>
    {{-- isi --}}
    
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
            <tr>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    &nbsp;
                </td>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    {{ $daerah->daerah }},
                        {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                </td>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                    GUBERNUR KALIMANTAN BARAT
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b><u>SUTARMIDJI</u></b></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>

        </table>
    </div>

    {{-- tanda tangan --}}
    
</body>

</html>
