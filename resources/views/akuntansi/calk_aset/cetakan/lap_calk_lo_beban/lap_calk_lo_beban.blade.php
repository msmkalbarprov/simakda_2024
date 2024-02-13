<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penjelasan Pendapatan</title>
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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="7" align="center" ><b>LAPORAN CALK {{$judul}}</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        {!! $head !!}
        @if($jenis=="1")
            @php
                $tot_lra_pjk            =0;
                $tot_piutang_pjk        =0;
                $tot_piutang_pjk_lalu   =0;
                $tot_pjk_lo             =0; 
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $lra_pjk=$row->lra_pjk;
                    $piutang_pjk=$row->piutang_pjk;
                    $piutang_pjk_lalu=$row->piutang_pjk_lalu;
                                
                    $pjk_lo = $lra_pjk+$piutang_pjk+$piutang_pjk_lalu;

                    $tot_lra_pjk            =$tot_lra_pjk+$lra_pjk;
                    $tot_piutang_pjk        =$tot_piutang_pjk+$piutang_pjk;
                    $tot_piutang_pjk_lalu   =$tot_piutang_pjk_lalu+$piutang_pjk_lalu;
                    $tot_pjk_lo             =$tot_pjk_lo+$pjk_lo; 
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_pjk < 0 ? '(' . rupiah($lra_pjk * -1) . ')' : rupiah($lra_pjk)}}</td>                         
                    <td align="right" valign="top">{{$piutang_pjk < 0 ? '(' . rupiah($piutang_pjk * -1) . ')' : rupiah($piutang_pjk)}}</td>                         
                    <td align="right" valign="top">{{$piutang_pjk_lalu < 0 ? '(' . rupiah($piutang_pjk_lalu * -1) . ')' : rupiah($piutang_pjk_lalu)}}</td> 
                    <td align="right" valign="top">{{$pjk_lo < 0 ? '(' . rupiah($pjk_lo * -1) . ')' : rupiah($pjk_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan=2 align="center" valign="top">TOTAL</td>                        
                <td align="right" valign="top">{{$tot_lra_pjk < 0 ? '(' . rupiah($tot_lra_pjk * -1) . ')' : rupiah($tot_lra_pjk)}}</td>                         
                <td align="right" valign="top">{{$tot_piutang_pjk < 0 ? '(' . rupiah($tot_piutang_pjk * -1) . ')' : rupiah($tot_piutang_pjk)}}</td>                         
                <td align="right" valign="top">{{$tot_piutang_pjk_lalu < 0 ? '(' . rupiah($tot_piutang_pjk_lalu * -1) . ')' : rupiah($tot_piutang_pjk_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_pjk_lo < 0 ? '(' . rupiah($tot_pjk_lo * -1) . ')' : rupiah($tot_pjk_lo)}}</td>
            </tr>
        @elseif($jenis=="2")
            @php
                $tot_lra_pjk            =0;
                $tot_piutang_pjk        =0;
                $tot_piutang_pjk_lalu   =0;
                $tot_dimuka             =0;
                $tot_dimuka_lalu        =0;
                $tot_pjk_lo             =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $lra_pjk=$row->lra_pjk;
                    $piutang_pjk=$row->piutang_pjk;
                    $piutang_pjk_lalu=$row->piutang_pjk_lalu;
                    $dimuka=$row->dimuka;
                    $dimuka_lalu=$row->dimuka_lalu;
                                
                    $pjk_lo = $lra_pjk+$piutang_pjk+$piutang_pjk_lalu+$dimuka+$dimuka_lalu;

                    $tot_lra_pjk            =$tot_lra_pjk+$lra_pjk;
                    $tot_piutang_pjk        =$tot_piutang_pjk+$piutang_pjk;
                    $tot_piutang_pjk_lalu   =$tot_piutang_pjk_lalu+$piutang_pjk_lalu;
                    $tot_dimuka             =$tot_dimuka+$dimuka;
                    $tot_dimuka_lalu        =$tot_dimuka_lalu+$dimuka_lalu;
                    $tot_pjk_lo             =$tot_pjk_lo+$pjk_lo;
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_pjk < 0 ? '(' . rupiah($lra_pjk * -1) . ')' : rupiah($lra_pjk)}}</td>
                    <td align="right" valign="top">{{$piutang_pjk < 0 ? '(' . rupiah($piutang_pjk * -1) . ')' : rupiah($piutang_pjk)}}</td>
                    <td align="right" valign="top">{{$piutang_pjk_lalu < 0 ? '(' . rupiah($piutang_pjk_lalu * -1) . ')' : rupiah($piutang_pjk_lalu)}}</td> 
                    <td align="right" valign="top">{{$dimuka < 0 ? '(' . rupiah($dimuka * -1) . ')' : rupiah($dimuka)}}</td>
                    <td align="right" valign="top">{{$dimuka_lalu < 0 ? '(' . rupiah($dimuka_lalu * -1) . ')' : rupiah($dimuka_lalu)}}</td> 
                    <td align="right" valign="top">{{$pjk_lo < 0 ? '(' . rupiah($pjk_lo * -1) . ')' : rupiah($pjk_lo)}}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="2" align="center" valign="top">TOTAL</td>                       
                    <td align="right" valign="top">{{$tot_lra_pjk < 0 ? '(' . rupiah($tot_lra_pjk * -1) . ')' : rupiah($tot_lra_pjk)}}</td>
                    <td align="right" valign="top">{{$tot_piutang_pjk < 0 ? '(' . rupiah($tot_piutang_pjk * -1) . ')' : rupiah($tot_piutang_pjk)}}</td>
                    <td align="right" valign="top">{{$tot_piutang_pjk_lalu < 0 ? '(' . rupiah($tot_piutang_pjk_lalu * -1) . ')' : rupiah($tot_piutang_pjk_lalu)}}</td> 
                    <td align="right" valign="top">{{$tot_dimuka < 0 ? '(' . rupiah($tot_dimuka * -1) . ')' : rupiah($tot_dimuka)}}</td>
                    <td align="right" valign="top">{{$tot_dimuka_lalu < 0 ? '(' . rupiah($tot_dimuka_lalu * -1) . ')' : rupiah($tot_dimuka_lalu)}}</td> 
                    <td align="right" valign="top">{{$tot_pjk_lo < 0 ? '(' . rupiah($tot_pjk_lo * -1) . ')' : rupiah($tot_pjk_lo)}}</td>
                </tr>
        @elseif($jenis=="3")
            @php
                $tot_lra_lain               =0;
                $tot_piutang_lain           =0;
                $tot_piutang_lain_lalu      =0;
                $tot_piutang_bhp            =0;
                $tot_piutang_bhp_lalu       =0;
                $tot_piutang_blud           =0;
                $tot_piutang_blud_lalu      =0;
                $tot_pdpt_dimuka_lalu       =0;
                $tot_pdpt_dimuka            =0;
                $tot_pdpt_dimuka_lalu       =0;
                $tot_pdpt_dimuka            =0;
                $tot_piutang_angsuran_lalu  =0;
                $tot_piutang_angsuran       =0;
                $tot_lain_lo                =0; 
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                =$row->kd_skpd;
                    $nm_skpd                =$row->nm_skpd;
                    $lra_lain               =$row->lra_lain;
                    $piutang_lain           =$row->piutang_lain;
                    $piutang_lain_lalu      =$row->piutang_lain_lalu;
                    $piutang_bhp            =$row->piutang_bhp;
                    $piutang_bhp_lalu       =$row->piutang_bhp_lalu;
                    $piutang_blud           =$row->piutang_blud;
                    $piutang_blud_lalu      =$row->piutang_blud_lalu;
                    $pdpt_dimuka_lalu       =$row->pdpt_dimuka_lalu;
                    $pdpt_dimuka            =$row->pdpt_dimuka;
                    $pdpt_dimuka_lalu       =$row->pdpt_dimuka_lalu;
                    $pdpt_dimuka            =$row->pdpt_dimuka;
                    $piutang_angsuran_lalu  =$row->piutang_angsuran_lalu;
                    $piutang_angsuran       =$row->piutang_angsuran;
                                
                    $lain_lo = $lra_lain+$piutang_lain+$piutang_lain_lalu+$piutang_bhp+$piutang_bhp_lalu+$piutang_blud+$piutang_blud_lalu+$pdpt_dimuka_lalu+$pdpt_dimuka+$piutang_angsuran_lalu+$piutang_angsuran;


                    $tot_lra_lain               =$tot_lra_lain+$lra_lain;
                    $tot_piutang_lain           =$tot_piutang_lain+$piutang_lain;
                    $tot_piutang_lain_lalu      =$tot_piutang_lain_lalu+$piutang_lain_lalu;
                    $tot_piutang_bhp            =$tot_piutang_bhp+$piutang_bhp;
                    $tot_piutang_bhp_lalu       =$tot_piutang_bhp_lalu+$piutang_bhp_lalu;
                    $tot_piutang_blud           =$tot_piutang_blud+$piutang_blud;
                    $tot_piutang_blud_lalu      =$tot_piutang_blud_lalu+$piutang_blud_lalu;
                    $tot_pdpt_dimuka_lalu       =$tot_pdpt_dimuka_lalu+$pdpt_dimuka_lalu;
                    $tot_pdpt_dimuka            =$tot_pdpt_dimuka+$pdpt_dimuka;
                    $tot_pdpt_dimuka_lalu       =$tot_pdpt_dimuka_lalu+$pdpt_dimuka_lalu;
                    $tot_pdpt_dimuka            =$tot_pdpt_dimuka+$pdpt_dimuka;
                    $tot_piutang_angsuran_lalu  =$tot_piutang_angsuran_lalu+$piutang_angsuran_lalu;
                    $tot_piutang_angsuran       =$tot_piutang_angsuran+$piutang_angsuran;
                    $tot_lain_lo                =$tot_lain_lo+$lain_lo; 
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_lain < 0 ? '(' . rupiah($lra_lain * -1) . ')' : rupiah($lra_lain)}}</td>
                    <td align="right" valign="top">{{$piutang_lain < 0 ? '(' . rupiah($piutang_lain * -1) . ')' : rupiah($piutang_lain)}}</td>
                    <td align="right" valign="top">{{$piutang_lain_lalu < 0 ? '(' . rupiah($piutang_lain_lalu * -1) . ')' : rupiah($piutang_lain_lalu)}}</td> 
                    <td align="right" valign="top">{{$piutang_bhp < 0 ? '(' . rupiah($piutang_bhp * -1) . ')' : rupiah($piutang_bhp)}}</td>
                    <td align="right" valign="top">{{$piutang_bhp_lalu < 0 ? '(' . rupiah($piutang_bhp_lalu * -1) . ')' : rupiah($piutang_bhp_lalu)}}</td> 
                    <td align="right" valign="top">{{$piutang_blud < 0 ? '(' . rupiah($piutang_blud * -1) . ')' : rupiah($piutang_blud)}}</td><td align="right" valign="top">{{$piutang_blud_lalu < 0 ? '(' . rupiah($piutang_blud_lalu * -1) . ')' : rupiah($piutang_blud_lalu)}}</td> 
                    <td align="right" valign="top">{{$pdpt_dimuka < 0 ? '(' . rupiah($pdpt_dimuka * -1) . ')' : rupiah($pdpt_dimuka)}}</td>
                    <td align="right" valign="top">{{$pdpt_dimuka_lalu < 0 ? '(' . rupiah($pdpt_dimuka_lalu * -1) . ')' : rupiah($pdpt_dimuka_lalu)}}</td> 
                    <td align="right" valign="top">{{$piutang_angsuran < 0 ? '(' . rupiah($piutang_angsuran * -1) . ')' : rupiah($piutang_angsuran)}}</td>
                    <td align="right" valign="top">{{$piutang_angsuran_lalu < 0 ? '(' . rupiah($piutang_angsuran_lalu * -1) . ')' : rupiah($piutang_angsuran_lalu)}}</td>
                    <td align="right" valign="top">{{$lain_lo < 0 ? '(' . rupiah($lain_lo * -1) . ')' : rupiah($lain_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>                         
                <td align="right" valign="top">{{$tot_lra_lain < 0 ? '(' . rupiah($tot_lra_lain * -1) . ')' : rupiah($tot_lra_lain)}}</td>
                <td align="right" valign="top">{{$tot_piutang_lain < 0 ? '(' . rupiah($tot_piutang_lain * -1) . ')' : rupiah($tot_piutang_lain)}}</td>
                <td align="right" valign="top">{{$tot_piutang_lain_lalu < 0 ? '(' . rupiah($tot_piutang_lain_lalu * -1) . ')' : rupiah($tot_piutang_lain_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_piutang_bhp < 0 ? '(' . rupiah($tot_piutang_bhp * -1) . ')' : rupiah($tot_piutang_bhp)}}</td>
                <td align="right" valign="top">{{$tot_piutang_bhp_lalu < 0 ? '(' . rupiah($tot_piutang_bhp_lalu * -1) . ')' : rupiah($tot_piutang_bhp_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_piutang_blud < 0 ? '(' . rupiah($tot_piutang_blud * -1) . ')' : rupiah($tot_piutang_blud)}}</td><td align="right" valign="top">{{$tot_piutang_blud_lalu < 0 ? '(' . rupiah($tot_piutang_blud_lalu * -1) . ')' : rupiah($tot_piutang_blud_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_pdpt_dimuka < 0 ? '(' . rupiah($tot_pdpt_dimuka * -1) . ')' : rupiah($tot_pdpt_dimuka)}}</td>
                <td align="right" valign="top">{{$tot_pdpt_dimuka_lalu < 0 ? '(' . rupiah($tot_pdpt_dimuka_lalu * -1) . ')' : rupiah($tot_pdpt_dimuka_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_piutang_angsuran < 0 ? '(' . rupiah($tot_piutang_angsuran * -1) . ')' : rupiah($tot_piutang_angsuran)}}</td>
                <td align="right" valign="top">{{$tot_piutang_angsuran_lalu < 0 ? '(' . rupiah($tot_piutang_angsuran_lalu * -1) . ')' : rupiah($tot_piutang_angsuran_lalu)}}</td>
                <td align="right" valign="top">{{$tot_lain_lo < 0 ? '(' . rupiah($tot_lain_lo * -1) . ')' : rupiah($tot_lain_lo)}}</td>
            </tr>
        @elseif($jenis=="4")
            @php
                $tot_lra_peg        = 0;
                $tot_peg_kapit      = 0;
                $tot_utang_peg      = 0;
                $tot_utang_peg_lalu = 0;
                $tot_blj_mdl_x      = 0;
                $tot_peg_lo         = 0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd        = $row->kd_skpd;
                    $nm_skpd        = $row->nm_skpd;
                    $lra_peg        = $row->lra_peg;
                    $peg_kapit      = $row->peg_kapit;
                    $utang_peg      = $row->utang_peg;
                    $utang_peg_lalu = $row->utang_peg_lalu;
                    $blj_mdl_x      = $row->blj_mdl_x;
                                
                    $peg_lo = $lra_peg+$peg_kapit+$utang_peg+$utang_peg_lalu+$blj_mdl_x;
                    
                    $tot_lra_peg        = $tot_lra_peg+$lra_peg;
                    $tot_peg_kapit      = $tot_peg_kapit+ $peg_kapit;
                    $tot_utang_peg      = $tot_utang_peg+$utang_peg;
                    $tot_utang_peg_lalu = $tot_utang_peg_lalu+$utang_peg_lalu;
                    $tot_blj_mdl_x      = $tot_blj_mdl_x+$blj_mdl_x;
                    $tot_peg_lo         = $tot_peg_lo+$peg_lo;
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_peg < 0 ? '(' . rupiah($lra_peg * -1) . ')' : rupiah($lra_peg)}}</td>
                    <td align="right" valign="top">{{$peg_kapit < 0 ? '(' . rupiah($peg_kapit * -1) . ')' : rupiah($peg_kapit)}}</td>                         
                    <td align="right" valign="top">{{$utang_peg < 0 ? '(' . rupiah($utang_peg * -1) . ')' : rupiah($utang_peg)}}</td>
                    <td align="right" valign="top">{{$utang_peg_lalu < 0 ? '(' . rupiah($utang_peg_lalu * -1) . ')' : rupiah($utang_peg_lalu)}}</td> 
                    <td align="right" valign="top">{{$blj_mdl_x < 0 ? '(' . rupiah($blj_mdl_x * -1) . ')' : rupiah($blj_mdl_x)}}</td> 
                    <td align="right" valign="top">{{$peg_lo < 0 ? '(' . rupiah($peg_lo * -1) . ')' : rupiah($peg_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>
                <td align="right" valign="top">{{$tot_lra_peg < 0 ? '(' . rupiah($tot_lra_peg * -1) . ')' : rupiah($tot_lra_peg)}}</td>
                <td align="right" valign="top">{{$tot_peg_kapit < 0 ? '(' . rupiah($tot_peg_kapit * -1) . ')' : rupiah($tot_peg_kapit)}}</td>                         
                <td align="right" valign="top">{{$tot_utang_peg < 0 ? '(' . rupiah($tot_utang_peg * -1) . ')' : rupiah($tot_utang_peg)}}</td>
                <td align="right" valign="top">{{$tot_utang_peg_lalu < 0 ? '(' . rupiah($tot_utang_peg_lalu * -1) . ')' : rupiah($tot_utang_peg_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_blj_mdl_x < 0 ? '(' . rupiah($tot_blj_mdl_x * -1) . ')' : rupiah($tot_blj_mdl_x)}}</td> 
                <td align="right" valign="top">{{$tot_peg_lo < 0 ? '(' . rupiah($tot_peg_lo * -1) . ')' : rupiah($tot_peg_lo)}}</td>
            </tr>
        @elseif($jenis=="5")
            @php
                $tot_brgjsa_lra             =0;
                $tot_beban_dimuka           =0;
                $tot_beban_dimuka_lalu      =0;
                $tot_utang_brg_jasa         =0;
                $tot_utang_brg_jasa_lalu    =0;
                $tot_bm_tdk_aset_ttp        =0;
                $tot_brg_jsa_tmbh_kapit     =0;
                $tot_pers_blud_l            =0;
                $tot_pers_blud_n            =0;
                $tot_beban_jasa_btt         =0;
                $tot_beljas_per             =0;
                $tot_kor_21                 =0;
                $tot_kor_22                 =0;
                $tot_brgjsa_lo              =0; 
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $brgjsa_lra=$row->brgjsa_lra;
                    $beban_dimuka=$row->beban_dimuka;
                    $beban_dimuka_lalu=$row->beban_dimuka_lalu;
                    $utang_brg_jasa=$row->utang_brg_jasa;
                    $utang_brg_jasa_lalu=$row->utang_brg_jasa_lalu;
                    $bm_tdk_aset_ttp=$row->bm_tdk_aset_ttp;
                    $brg_jsa_tmbh_kapit=$row->brg_jsa_tmbh_kapit;
                    $pers_blud_l=$row->pers_blud_l;
                    $pers_blud_n=$row->pers_blud_n;
                    $beban_jasa_btt=$row->beban_jasa_btt;
                    $beljas_per=$row->beljas_per;
                    $kor_21=$row->kor_21;
                    $kor_22=$row->kor_22;

                                
                    $brgjsa_lo = $brgjsa_lra+$beban_dimuka+$beban_dimuka_lalu+$utang_brg_jasa+$utang_brg_jasa_lalu+$bm_tdk_aset_ttp+$brg_jsa_tmbh_kapit+$pers_blud_l+$pers_blud_n+$beban_jasa_btt+$beljas_per+$kor_21+$kor_22;

                    $tot_brgjsa_lra             =$tot_brgjsa_lra+$brgjsa_lra;
                    $tot_beban_dimuka           =$tot_beban_dimuka+$beban_dimuka;
                    $tot_beban_dimuka_lalu      =$tot_beban_dimuka_lalu+$beban_dimuka_lalu;
                    $tot_utang_brg_jasa         =$tot_utang_brg_jasa+$utang_brg_jasa;
                    $tot_utang_brg_jasa_lalu    =$tot_utang_brg_jasa_lalu+$utang_brg_jasa_lalu;
                    $tot_bm_tdk_aset_ttp        =$tot_bm_tdk_aset_ttp+$bm_tdk_aset_ttp;
                    $tot_brg_jsa_tmbh_kapit     =$tot_brg_jsa_tmbh_kapit+$brg_jsa_tmbh_kapit;
                    $tot_pers_blud_l            =$tot_pers_blud_l+$pers_blud_l;
                    $tot_pers_blud_n            =$tot_pers_blud_n+$pers_blud_n;
                    $tot_beban_jasa_btt         =$tot_beban_jasa_btt+$beban_jasa_btt;
                    $tot_beljas_per             =$tot_beljas_per+$beljas_per;
                    $tot_kor_21                 =$tot_kor_21+$kor_21;
                    $tot_kor_22                 =$tot_kor_22+$kor_22;
                    $tot_brgjsa_lo              =$tot_brgjsa_lo+$brgjsa_lo; 
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>
                    <td align="right" valign="top">{{$brgjsa_lra < 0 ? '(' . rupiah($brgjsa_lra * -1) . ')' : rupiah($brgjsa_lra)}}</td>
                    <td align="right" valign="top">{{$beban_dimuka < 0 ? '(' . rupiah($beban_dimuka * -1) . ')' : rupiah($beban_dimuka)}}</td>
                    <td align="right" valign="top">{{$beban_dimuka_lalu < 0 ? '(' . rupiah($beban_dimuka_lalu * -1) . ')' : rupiah($beban_dimuka_lalu)}}</td>
                    <td align="right" valign="top">{{$utang_brg_jasa < 0 ? '(' . rupiah($utang_brg_jasa * -1) . ')' : rupiah($utang_brg_jasa)}}</td>
                    <td align="right" valign="top">{{$utang_brg_jasa_lalu < 0 ? '(' . rupiah($utang_brg_jasa_lalu * -1) . ')' : rupiah($utang_brg_jasa_lalu)}}</td> 
                    <td align="right" valign="top">{{$bm_tdk_aset_ttp < 0 ? '(' . rupiah($bm_tdk_aset_ttp * -1) . ')' : rupiah($bm_tdk_aset_ttp)}}</td>
                    <td align="right" valign="top">{{$brg_jsa_tmbh_kapit < 0 ? '(' . rupiah($brg_jsa_tmbh_kapit * -1) . ')' : rupiah($brg_jsa_tmbh_kapit)}}</td>
                    <td align="right" valign="top">{{$pers_blud_l < 0 ? '(' . rupiah($pers_blud_l * -1) . ')' : rupiah($pers_blud_l)}}</td>
                    <td align="right" valign="top">{{$pers_blud_n < 0 ? '(' . rupiah($pers_blud_n * -1) . ')' : rupiah($pers_blud_n)}}</td>
                    <td align="right" valign="top">{{$beban_jasa_btt < 0 ? '(' . rupiah($beban_jasa_btt * -1) . ')' : rupiah($beban_jasa_btt)}}</td>
                    <td align="right" valign="top">{{$beljas_per < 0 ? '(' . rupiah($beljas_per * -1) . ')' : rupiah($beljas_per)}}</td> 
                    <td align="right" valign="top">{{$kor_21 < 0 ? '(' . rupiah($kor_21 * -1) . ')' : rupiah($kor_21)}}</td>
                    <td align="right" valign="top">{{$kor_22 < 0 ? '(' . rupiah($kor_22 * -1) . ')' : rupiah($kor_22)}}</td>
                    <td align="right" valign="top">{{$brgjsa_lo < 0 ? '(' . rupiah($brgjsa_lo * -1) . ')' : rupiah($brgjsa_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>
                <td align="right" valign="top">{{$tot_brgjsa_lra < 0 ? '(' . rupiah($tot_brgjsa_lra * -1) . ')' : rupiah($tot_brgjsa_lra)}}</td>
                <td align="right" valign="top">{{$tot_beban_dimuka < 0 ? '(' . rupiah($tot_beban_dimuka * -1) . ')' : rupiah($tot_beban_dimuka)}}</td>
                <td align="right" valign="top">{{$tot_beban_dimuka_lalu < 0 ? '(' . rupiah($tot_beban_dimuka_lalu * -1) . ')' : rupiah($tot_beban_dimuka_lalu)}}</td>
                <td align="right" valign="top">{{$tot_utang_brg_jasa < 0 ? '(' . rupiah($tot_utang_brg_jasa * -1) . ')' : rupiah($tot_utang_brg_jasa)}}</td>
                <td align="right" valign="top">{{$tot_utang_brg_jasa_lalu < 0 ? '(' . rupiah($tot_utang_brg_jasa_lalu * -1) . ')' : rupiah($tot_utang_brg_jasa_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_bm_tdk_aset_ttp < 0 ? '(' . rupiah($tot_bm_tdk_aset_ttp * -1) . ')' : rupiah($tot_bm_tdk_aset_ttp)}}</td>
                <td align="right" valign="top">{{$tot_brg_jsa_tmbh_kapit < 0 ? '(' . rupiah($tot_brg_jsa_tmbh_kapit * -1) . ')' : rupiah($tot_brg_jsa_tmbh_kapit)}}</td>
                <td align="right" valign="top">{{$tot_pers_blud_l < 0 ? '(' . rupiah($tot_pers_blud_l * -1) . ')' : rupiah($tot_pers_blud_l)}}</td>
                <td align="right" valign="top">{{$tot_pers_blud_n < 0 ? '(' . rupiah($tot_pers_blud_n * -1) . ')' : rupiah($tot_pers_blud_n)}}</td>
                <td align="right" valign="top">{{$tot_beban_jasa_btt < 0 ? '(' . rupiah($tot_beban_jasa_btt * -1) . ')' : rupiah($tot_beban_jasa_btt)}}</td>
                <td align="right" valign="top">{{$tot_beljas_per < 0 ? '(' . rupiah($tot_beljas_per * -1) . ')' : rupiah($tot_beljas_per)}}</td> 
                <td align="right" valign="top">{{$tot_kor_21 < 0 ? '(' . rupiah($tot_kor_21 * -1) . ')' : rupiah($tot_kor_21)}}</td>
                <td align="right" valign="top">{{$tot_kor_22 < 0 ? '(' . rupiah($tot_kor_22 * -1) . ')' : rupiah($tot_kor_22)}}</td>
                <td align="right" valign="top">{{$tot_brgjsa_lo < 0 ? '(' . rupiah($tot_brgjsa_lo * -1) . ')' : rupiah($tot_brgjsa_lo)}}</td>
            </tr>
        @elseif($jenis=="6")
            @php
                $tot_persediaan_lra         =0;
                $tot_persediaan             =0;
                $tot_persediaan_lalu        =0;
                $tot_bm_tdk_aset_ttp        =0;
                $tot_persediaan_aset        =0;
                $tot_utang_persediaan       =0;
                $tot_utang_persediaan_lalu  =0;
                $tot_hibah_pihak3           =0;
                $tot_persediaan_btt         =0;
                $tot_excomp                 =0;
                $tot_bel_per_eks            =0;
                $tot_bb_btt                 =0;
                $tot_kor_per                =0;
                $tot_belmod_per             =0;
                $tot_belhiper               =0;
                $tot_beljas_per             =0;
                $tot_persediaan_lo          =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $persediaan_lra=$row->persediaan_lra;
                    $persediaan=$row->persediaan;
                    $persediaan_lalu=$row->persediaan_lalu;
                    $bm_tdk_aset_ttp=$row->bm_tdk_aset_ttp;
                    $persediaan_aset=$row->persediaan_aset;
                    $utang_persediaan=$row->utang_persediaan;
                    $utang_persediaan_lalu=$row->utang_persediaan_lalu;
                    $hibah_pihak3=$row->hibah_pihak3;
                    $persediaan_btt=$row->persediaan_btt;
                    $excomp=$row->excomp;
                    $bel_per_eks=$row->bel_per_eks;
                    $bb_btt=$row->bb_btt;
                    $kor_per=$row->kor_per;
                    $belmod_per=$row->belmod_per;
                    $belhiper=$row->belhiper;
                    $beljas_per=$row->beljas_per;
                                
                    $persediaan_lo = $persediaan_lra+$persediaan+$persediaan_lalu+$bm_tdk_aset_ttp+$persediaan_aset+$utang_persediaan+$utang_persediaan_lalu+$hibah_pihak3+$persediaan_btt+$excomp+$bel_per_eks+$bb_btt+$kor_per+$belmod_per+$belhiper+$beljas_per;
                    
                    $tot_persediaan_lra         =$tot_persediaan_lra+$persediaan_lra;
                    $tot_persediaan             =$tot_persediaan+$persediaan;
                    $tot_persediaan_lalu        =$tot_persediaan_lalu+$persediaan_lalu;
                    $tot_bm_tdk_aset_ttp        =$tot_bm_tdk_aset_ttp+$bm_tdk_aset_ttp;
                    $tot_persediaan_aset        =$tot_persediaan_aset+$persediaan_aset;
                    $tot_utang_persediaan       =$tot_utang_persediaan+$utang_persediaan;
                    $tot_utang_persediaan_lalu  =$tot_utang_persediaan_lalu+$utang_persediaan_lalu;
                    $tot_hibah_pihak3           =$tot_hibah_pihak3+$hibah_pihak3;
                    $tot_persediaan_btt         =$tot_persediaan_btt+$persediaan_btt;
                    $tot_excomp                 =$tot_excomp+$excomp;
                    $tot_bel_per_eks            =$tot_bel_per_eks+$bel_per_eks;
                    $tot_bb_btt                 =$tot_bb_btt+$bb_btt;
                    $tot_kor_per                =$tot_kor_per+$kor_per;
                    $tot_belmod_per             =$tot_belmod_per+$belmod_per;
                    $tot_belhiper               =$tot_belhiper+$belhiper;
                    $tot_beljas_per             =$tot_beljas_per+$beljas_per;
                    $tot_persediaan_lo          =$tot_persediaan_lo+$persediaan_lo;
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>
                    <td align="right" valign="top">{{$persediaan_lra < 0 ? '(' . rupiah($persediaan_lra * -1) . ')' : rupiah($persediaan_lra)}}</td>
                    <td align="right" valign="top">{{$persediaan < 0 ? '(' . rupiah($persediaan * -1) . ')' : rupiah($persediaan)}}</td>
                    <td align="right" valign="top">{{$persediaan_lalu < 0 ? '(' . rupiah($persediaan_lalu * -1) . ')' : rupiah($persediaan_lalu)}}</td>
                    <td align="right" valign="top">{{$bm_tdk_aset_ttp < 0 ? '(' . rupiah($bm_tdk_aset_ttp * -1) . ')' : rupiah($bm_tdk_aset_ttp)}}</td>
                    <td align="right" valign="top">{{$persediaan_aset < 0 ? '(' . rupiah($persediaan_aset * -1) . ')' : rupiah($persediaan_aset)}}</td> 
                    <td align="right" valign="top">{{$utang_persediaan < 0 ? '(' . rupiah($utang_persediaan * -1) . ')' : rupiah($utang_persediaan)}}</td>
                    <td align="right" valign="top">{{$utang_persediaan_lalu < 0 ? '(' . rupiah($utang_persediaan_lalu * -1) . ')' : rupiah($utang_persediaan_lalu)}}</td>
                    <td align="right" valign="top">{{$hibah_pihak3 < 0 ? '(' . rupiah($hibah_pihak3 * -1) . ')' : rupiah($hibah_pihak3)}}</td>
                    <td align="right" valign="top">{{$persediaan_btt < 0 ? '(' . rupiah($persediaan_btt * -1) . ')' : rupiah($persediaan_btt)}}</td>
                    <td align="right" valign="top">{{$excomp < 0 ? '(' . rupiah($excomp * -1) . ')' : rupiah($excomp)}}</td>
                    <td align="right" valign="top">{{$bel_per_eks < 0 ? '(' . rupiah($bel_per_eks * -1) . ')' : rupiah($bel_per_eks)}}</td> 
                    <td align="right" valign="top">{{$bb_btt < 0 ? '(' . rupiah($bb_btt * -1) . ')' : rupiah($bb_btt)}}</td>
                    <td align="right" valign="top">{{$kor_per < 0 ? '(' . rupiah($kor_per * -1) . ')' : rupiah($kor_per)}}</td>
                    <td align="right" valign="top">{{$belmod_per < 0 ? '(' . rupiah($belmod_per * -1) . ')' : rupiah($belmod_per)}}</td>
                    <td align="right" valign="top">{{$belhiper < 0 ? '(' . rupiah($belhiper * -1) . ')' : rupiah($belhiper)}}</td>
                    <td align="right" valign="top">{{$beljas_per < 0 ? '(' . rupiah($beljas_per * -1) . ')' : rupiah($beljas_per)}}</td>
                    <td align="right" valign="top">{{$persediaan_lo < 0 ? '(' . rupiah($persediaan_lo * -1) . ')' : rupiah($persediaan_lo)}}</td>

                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>
                <td align="right" valign="top">{{$tot_persediaan_lra < 0 ? '(' . rupiah($tot_persediaan_lra * -1) . ')' : rupiah($tot_persediaan_lra)}}</td>
                <td align="right" valign="top">{{$tot_persediaan < 0 ? '(' . rupiah($tot_persediaan * -1) . ')' : rupiah($tot_persediaan)}}</td>
                <td align="right" valign="top">{{$tot_persediaan_lalu < 0 ? '(' . rupiah($tot_persediaan_lalu * -1) . ')' : rupiah($tot_persediaan_lalu)}}</td>
                <td align="right" valign="top">{{$tot_bm_tdk_aset_ttp < 0 ? '(' . rupiah($tot_bm_tdk_aset_ttp * -1) . ')' : rupiah($tot_bm_tdk_aset_ttp)}}</td>
                <td align="right" valign="top">{{$tot_persediaan_aset < 0 ? '(' . rupiah($tot_persediaan_aset * -1) . ')' : rupiah($tot_persediaan_aset)}}</td> 
                <td align="right" valign="top">{{$tot_utang_persediaan < 0 ? '(' . rupiah($tot_utang_persediaan * -1) . ')' : rupiah($tot_utang_persediaan)}}</td>
                <td align="right" valign="top">{{$tot_utang_persediaan_lalu < 0 ? '(' . rupiah($tot_utang_persediaan_lalu * -1) . ')' : rupiah($tot_utang_persediaan_lalu)}}</td>
                <td align="right" valign="top">{{$tot_hibah_pihak3 < 0 ? '(' . rupiah($tot_hibah_pihak3 * -1) . ')' : rupiah($tot_hibah_pihak3)}}</td>
                <td align="right" valign="top">{{$tot_persediaan_btt < 0 ? '(' . rupiah($tot_persediaan_btt * -1) . ')' : rupiah($tot_persediaan_btt)}}</td>
                <td align="right" valign="top">{{$tot_excomp < 0 ? '(' . rupiah($tot_excomp * -1) . ')' : rupiah($tot_excomp)}}</td>
                <td align="right" valign="top">{{$tot_bel_per_eks < 0 ? '(' . rupiah($tot_bel_per_eks * -1) . ')' : rupiah($tot_bel_per_eks)}}</td> 
                <td align="right" valign="top">{{$tot_bb_btt < 0 ? '(' . rupiah($tot_bb_btt * -1) . ')' : rupiah($tot_bb_btt)}}</td>
                <td align="right" valign="top">{{$tot_kor_per < 0 ? '(' . rupiah($tot_kor_per * -1) . ')' : rupiah($tot_kor_per)}}</td>
                <td align="right" valign="top">{{$tot_belmod_per < 0 ? '(' . rupiah($tot_belmod_per * -1) . ')' : rupiah($tot_belmod_per)}}</td>
                <td align="right" valign="top">{{$tot_belhiper < 0 ? '(' . rupiah($tot_belhiper * -1) . ')' : rupiah($tot_belhiper)}}</td>
                <td align="right" valign="top">{{$tot_beljas_per < 0 ? '(' . rupiah($tot_beljas_per * -1) . ')' : rupiah($tot_beljas_per)}}</td>
                <td align="right" valign="top">{{$tot_persediaan_lo < 0 ? '(' . rupiah($tot_persediaan_lo * -1) . ')' : rupiah($tot_persediaan_lo)}}</td>

            </tr>
        @elseif($jenis=="7")
            @php
                $tot_lra_pem=0;
                $tot_utang_pem=0;
                $tot_utang_pem_lalu=0;
                $tot_bm_tdk_kapit=0;
                $tot_pem_aset=0;
                $tot_pem_btt=0; 
                $tot_pem_lo=0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $lra_pem=$row->lra_pem;
                    $utang_pem=$row->utang_pem;
                    $utang_pem_lalu=$row->utang_pem_lalu;
                    $bm_tdk_kapit=$row->bm_tdk_kapit;
                    $pem_aset=$row->pem_aset;
                    $pem_btt=$row->pem_btt;
                                
                    $pem_lo = $lra_pem+$utang_pem+$utang_pem_lalu+$bm_tdk_kapit+$pem_aset+$pem_btt;

                    $tot_lra_pem        =$tot_lra_pem+$lra_pem;
                    $tot_utang_pem      =$tot_utang_pem+$utang_pem;
                    $tot_utang_pem_lalu =$tot_utang_pem_lalu+$utang_pem_lalu;
                    $tot_bm_tdk_kapit   =$tot_bm_tdk_kapit+$bm_tdk_kapit;
                    $tot_pem_aset       =$tot_pem_aset+$pem_aset;
                    $tot_pem_btt        =$tot_pem_btt+$pem_btt; 
                    $tot_pem_lo         =$tot_pem_lo+$pem_lo; 
                    
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_pem < 0 ? '(' . rupiah($lra_pem * -1) . ')' : rupiah($lra_pem)}}</td>                         
                    <td align="right" valign="top">{{$utang_pem < 0 ? '(' . rupiah($utang_pem * -1) . ')' : rupiah($utang_pem)}}</td>                         
                    <td align="right" valign="top">{{$utang_pem_lalu < 0 ? '(' . rupiah($utang_pem_lalu * -1) . ')' : rupiah($utang_pem_lalu)}}</td> 
                    <td align="right" valign="top">{{$bm_tdk_kapit < 0 ? '(' . rupiah($bm_tdk_kapit * -1) . ')' : rupiah($bm_tdk_kapit)}}</td>
                    <td align="right" valign="top">{{$pem_aset < 0 ? '(' . rupiah($pem_aset * -1) . ')' : rupiah($pem_aset)}}</td>
                    <td align="right" valign="top">{{$pem_btt < 0 ? '(' . rupiah($pem_btt * -1) . ')' : rupiah($pem_btt)}}</td> 
                    <td align="right" valign="top">{{$pem_lo < 0 ? '(' . rupiah($pem_lo * -1) . ')' : rupiah($pem_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>
                <td align="right" valign="top">{{$tot_lra_pem < 0 ? '(' . rupiah($tot_lra_pem * -1) . ')' : rupiah($tot_lra_pem)}}</td>                         
                <td align="right" valign="top">{{$tot_utang_pem < 0 ? '(' . rupiah($tot_utang_pem * -1) . ')' : rupiah($tot_utang_pem)}}</td>                         
                <td align="right" valign="top">{{$tot_utang_pem_lalu < 0 ? '(' . rupiah($tot_utang_pem_lalu * -1) . ')' : rupiah($tot_utang_pem_lalu)}}</td> 
                <td align="right" valign="top">{{$tot_bm_tdk_kapit < 0 ? '(' . rupiah($tot_bm_tdk_kapit * -1) . ')' : rupiah($tot_bm_tdk_kapit)}}</td>
                <td align="right" valign="top">{{$tot_pem_aset < 0 ? '(' . rupiah($tot_pem_aset * -1) . ')' : rupiah($tot_pem_aset)}}</td>
                <td align="right" valign="top">{{$tot_pem_btt < 0 ? '(' . rupiah($tot_pem_btt * -1) . ')' : rupiah($tot_pem_btt)}}</td> 
                <td align="right" valign="top">{{$tot_pem_lo < 0 ? '(' . rupiah($tot_pem_lo * -1) . ')' : rupiah($tot_pem_lo)}}</td>
            </tr>
        @elseif($jenis=="8")
            @php
                $tot_lra_prj            =0;
                $tot_bel_tmb_kapit      =0;
                $tot_bel_mdl_tdk_aset   =0;
                $tot_perjadin_btt       =0;
                $tot_brgjsa_lo          =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd           =$row->kd_skpd;
                    $nm_skpd           =$row->nm_skpd;
                    $lra_prj           =$row->lra_prj;
                    $bel_tmb_kapit     =$row->bel_tmb_kapit;
                    $bel_mdl_tdk_aset  =$row->bel_mdl_tdk_aset;
                    $perjadin_btt  =$row->perjadin_btt;
                                
                    $brgjsa_lo = $lra_prj+$bel_tmb_kapit+$bel_mdl_tdk_aset+$perjadin_btt;

                    $tot_lra_prj            =$tot_lra_prj+$lra_prj;
                    $tot_bel_tmb_kapit      =$tot_bel_tmb_kapit+$bel_tmb_kapit;
                    $tot_bel_mdl_tdk_aset   =$tot_bel_mdl_tdk_aset+$bel_mdl_tdk_aset;
                    $tot_perjadin_btt       =$tot_perjadin_btt+$perjadin_btt;
                    $tot_brgjsa_lo          =$tot_brgjsa_lo+$brgjsa_lo;
                    
                @endphp
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>                         
                    <td align="right" valign="top">{{$lra_prj < 0 ? '(' . rupiah($lra_prj * -1) . ')' : rupiah($lra_prj)}}</td>                  
                    <td align="right" valign="top">{{$bel_tmb_kapit < 0 ? '(' . rupiah($bel_tmb_kapit * -1) . ')' : rupiah($bel_tmb_kapit)}}</td> 
                    <td align="right" valign="top">{{$bel_mdl_tdk_aset < 0 ? '(' . rupiah($bel_mdl_tdk_aset * -1) . ')' : rupiah($bel_mdl_tdk_aset)}}</td>
                    <td align="right" valign="top">{{$perjadin_btt < 0 ? '(' . rupiah($perjadin_btt * -1) . ')' : rupiah($perjadin_btt)}}</td>
                    <td align="right" valign="top">{{$brgjsa_lo < 0 ? '(' . rupiah($brgjsa_lo * -1) . ')' : rupiah($brgjsa_lo)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" valign="top">TOTAL</td>
                <td align="right" valign="top">{{$tot_lra_prj < 0 ? '(' . rupiah($tot_lra_prj * -1) . ')' : rupiah($tot_lra_prj)}}</td>
                <td align="right" valign="top">{{$tot_bel_tmb_kapit < 0 ? '(' . rupiah($tot_bel_tmb_kapit * -1) . ')' : rupiah($tot_bel_tmb_kapit)}}</td> 
                <td align="right" valign="top">{{$tot_bel_mdl_tdk_aset < 0 ? '(' . rupiah($tot_bel_mdl_tdk_aset * -1) . ')' : rupiah($tot_bel_mdl_tdk_aset)}}</td>
                <td align="right" valign="top">{{$tot_perjadin_btt < 0 ? '(' . rupiah($tot_perjadin_btt * -1) . ')' : rupiah($tot_perjadin_btt)}}</td>
                <td align="right" valign="top">{{$tot_brgjsa_lo < 0 ? '(' . rupiah($tot_brgjsa_lo * -1) . ')' : rupiah($tot_brgjsa_lo)}}</td>
            </tr>
        @endif
    </table>
</body>
</html>