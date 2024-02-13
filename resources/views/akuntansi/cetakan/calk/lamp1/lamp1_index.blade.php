<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - LAMP 1</title>
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
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="right">Lampiran 1</td>
        </tr>                         
        <tr>
            <td align="center"><strong>ANALISIS</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>PEMERIKSAAN ATAS LAPORAN KEUANGAN</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>TAHUN ANGGARAN {{$thn_ang}}</strong></td>
        </tr>                         
        <tr>
            <td align="left"><strong>SKPD : {{$kd_skpd}} - {{$nm_skpd}}</strong></td>
        </tr>                         
  </table>
  <br>

  <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td align="left" colspan="3"><b>a. ANALISIS VERTIKAL</b></td>
        </tr>
        <!-- 1 Vertical LRA-->
            <tr>
                <td align="left" colspan="3"><b>1) Analisis vertikal dalam LRA</b></td>
            </tr>                         
            <tr>
                <td align="center"><b>Uraian</b></td>
                <td align="center">&nbsp;</td>
                <td align="center"><b>Persamaan</b></td>
            </tr> 
            <tr>
                <td align="left">Surplus/Defisit LRA tahun berjalan harus sama dengan total pendapatan dikurangi total belanja</td>
                <td align="center">&nbsp;</td>
                <td align="left">Surplus/Defisit = Total Pendapatan - Total Belanja</td>
            </tr>
            <tr>
                <td align="left">RUMUS</td>
                <td align="center">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            @php
                $pendapatan_vlra = $vlra->pendapatan;
                $belanja_vlra = $vlra->belanja;
                $surdef_vlra = $vlra->surdef;
                $selisih_vlra = $vlra->selisih;
                if($surdef_vlra<0){
                    $a_vlra="(";
                    $surdeff_vlra = $surdef_vlra*-1;
                    $b_vlra=")";
                }else{
                    $a_vlra="";
                    $surdeff_vlra = $surdef_vlra;
                    $b_vlra="";
                }
            @endphp
            <tr>
                <td align="left">SURPLUS/DEFISIT LRA THN BERJALAN</td>
                <td align="center">:</td>
                <td align="right">{{$a_vlra}}{{rupiah($surdeff_vlra)}}{{$b_vlra}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL PENDAPATAN</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($pendapatan_vlra)}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL BELANJA DAN TRANSFER</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($belanja_vlra)}}</td>
            </tr>
            <tr>
                <td align="center"><b>Selisih</b></td>
                <td align="center"><b>:</b></td>
                <td align="right"><b>{{rupiah($selisih_vlra)}}</b></td>
            </tr>
            @php
                $totsur_vlra_ket = 0;
            @endphp
            @foreach($vlra_ket as $ket_a11)
                @php
                    $ket1_vlra_ket = $ket_a11->ket;
                    $nilai_vlra_ket = $ket_a11->nilai;
                    $totsur_vlra_ket = $totsur_vlra_ket+$nilai_vlra_ket;

                    if ($nilai_vlra_ket<0) {
                        $nilais_vlra_ket=($nilai_vlra_ket)*-1;
                        $sa_vlra_ket="(";
                        $sb_vlra_ket=")";
                    }else{
                        $nilais_vlra_ket=($nilai_vlra_ket);
                        $sa_vlra_ket="";
                        $sb_vlra_ket="";
                    }   
                    if($ket1_vlra_ket<>''){
                        $ket_vlra_ket = $ket1_vlra_ket;
                    }else{
                        $ket_vlra_ket = $ket1_vlra_ket;
                    }
                @endphp
                <tr>       
                    <td coslpan="2" align="left"><b>{!! $ket_vlra_ket !!}</td>
                    <td align="left"></td>
                    <td align="right">{{$sa_vlra_ket}}{{rupiah($nilais_vlra_ket)}}{{$sb_vlra_ket}}</td>
                </tr>
                <tr></tr>
            @endforeach
            @php
                if ($totsur_vlra_ket<0) {
                    $tot_sur_vlra_ket=($totsur_vlra_ket)*-1;
                    $as_vlra_ket="(";
                    $bs_vlra_ket=")";
                }else{
                    $tot_sur_vlra_ket=($totsur_vlra_ket);
                    $as_vlra_ket="";
                    $bs_vlra_ket="";
                }   
                $cek_selisisur_vlra_ket=$selisih_vlra-($totsur_vlra_ket);
            @endphp
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="center"><b>:</b></td>
                @if($cek_selisisur_vlra_ket<>0)
                    <td align="right" bgcolor="red"><b>{{$as_vlra_ket}}{{rupiah($tot_sur_vlra_ket)}}{{$bs_vlra_ket}}</b></td>
                @else
                    <td align="right"><b>{{$as_vlra_ket}}{{rupiah($tot_sur_vlra_ket)}}{{$bs_vlra_ket}}</b></td>
                @endif
                
            </tr>
            @if($jenis==1)
                <tr>
                    <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','a11')">Edit Penjelasan LRA Vertical</button>
                    </td>                         
                </tr>
            @else
            @endif
        <!---->
        <!-- 2 Vertical Neraca -->
            <tr>
                <td align="left" colspan="3"><b>2) Analisis vertikal dalam Neraca</b></td>
            </tr>                         
            <tr>
                <td align="center"><b>Uraian</b></td>
                <td align="center">&nbsp;</td>
                <td align="center"><b>Persamaan</b></td>
            </tr> 
            <tr>
                <td align="left">Aset harus sama dengan total kewajiban ditambah dengan total ekuitas</td>
                <td align="center">&nbsp;</td>
                <td align="left">Aset = Kewajiban + Ekuitas</td>
            </tr>
            <tr>
                <td align="left">RUMUS</td>
                <td align="center">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            @php
                $kewajiban_vneraca = $vneraca->kewajiban;
                $ekuitas_vneraca = $vneraca->ekuitas;
                $selisih_vneraca = $vneraca->selisih;
                $aset_vneraca = $vneraca->aset;
                if($selisih_vneraca<0){
                    $a_vneraca="(";
                    $selisihh_vneracaa = $selisih_vneraca*-1;
                    $b_vneraca=")";
                }else{
                    $a_vneraca="";
                    $selisihh_vneracaa = $selisih_vneraca;
                    $b_vneraca="";
                }
            @endphp
            <tr>
                <td align="left">ASET</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($aset_vneraca)}}</td>
            </tr>
            <tr>
                <td align="left">KEWAJIBAN</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($kewajiban_vneraca)}}</td>
            </tr>
            <tr>
                <td align="left">EKUITAS</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($ekuitas_vneraca)}}</td>
            </tr>
            <tr>
                <td align="center"><b>Selisih</b></td>
                <td align="center"><b>:</b></td>
                <td align="right"><b>{{$a_vneraca}}{{rupiah($selisihh_vneracaa)}}{{$b_vneraca}}</b></td>
            </tr>
            @php
                $tot_vneraca_ket = 0;
            @endphp
            @foreach($vneraca_ket as $ket_a21)
                @php
                    $ket1_vneraca_ket = $ket_a21->ket;
                    $nilai_vneraca_ket = $ket_a21->nilai;
                    $tot_vneraca_ket = $tot_vneraca_ket+$nilai_vneraca_ket;

                    if ($nilai_vneraca_ket<0) {
                        $nilais_vneraca_ket=($nilai_vneraca_ket)*-1;
                        $sa_vneraca_ket="(";
                        $sb_vneraca_ket=")";
                    }else{
                        $nilais_vneraca_ket=($nilai_vneraca_ket);
                        $sa_vneraca_ket="";
                        $sb_vneraca_ket="";
                    }   
                    if($ket1_vneraca_ket<>''){
                        $ket_vneraca_ket = $ket1_vneraca_ket;
                    }else{
                        $ket_vneraca_ket = $ket1_vneraca_ket;
                    }
                @endphp
                <tr>       
                    <td coslpan="2" align="left"><b>{!! $ket_vneraca_ket !!}</td>
                    <td align="left"></td>
                    <td align="right">{{$sa_vneraca_ket}}{{rupiah($nilais_vneraca_ket)}}{{$sb_vneraca_ket}}</td>
                </tr>
                <tr></tr>
            @endforeach
            @php
                if ($tot_vneraca_ket<0) {
                    $tot_vneraca_ket=($tot_vneraca_ket)*-1;
                    $as_vneraca_ket="(";
                    $bs_vneraca_ket=")";
                }else{
                    $tot_vneraca_ket=($tot_vneraca_ket);
                    $as_vneraca_ket="";
                    $bs_vneraca_ket="";
                }   
                $cek_selisih_vneraca_ket=$selisih_vneraca-($tot_vneraca_ket);
            @endphp
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="center"><b>:</b></td>
                @if($cek_selisih_vneraca_ket<>0)
                    <td align="right" bgcolor="red"><b>{{$as_vneraca_ket}}{{rupiah($tot_vneraca_ket)}}{{$bs_vneraca_ket}}</b></td>
                @else
                    <td align="right"><b>{{$as_vneraca_ket}}{{rupiah($tot_vneraca_ket)}}{{$bs_vneraca_ket}}</b></td>
                @endif
                
            </tr>
            @if($jenis==1)
                <tr>
                    <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','a21')">Edit Penjelasan Neraca Vertical</button>
                    </td>                         
                </tr>
            @else
            @endif
        <!-- -->
        <!-- 2 kas Di Bendahara-->
            <tr>
                <td align="left">Kas di Bendahara Pengeluaran harus sama dengan sisa Uang Persediaan yang belum disetor ke kasda ditambah dengan Utang PFK di Bendahara Pengeluaran yang belum disetor ke kas negara.</td>
                <td align="center">&nbsp;</td>
                <td align="left">Kas di Bendahara Pengeluaran = Sisa Uang Persediaan yang Belum Disetor + Utang PFK di Bendahara Pengeluaran</td>
            </tr>
            <tr>
                <td align="left">RUMUS</td>
                <td align="center">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            @php
                $kas_keluar_vkasben = $vkasben->kas_keluar;
                $sisa_kas_vkasben   = $vkasben->sisa_kas;
                $utang_pfk_vkasben  = $vkasben->utang_pfk;
                
                $selisih_vkasben=$kas_keluar_vkasben-($sisa_kas_vkasben+$utang_pfk_vkasben);
                
                if($kas_keluar_vkasben<0){
                    $kas_keluar_vkasbenn = $kas_keluar_vkasben*-1;
                    $r_vkasben="(";
                    $s=")";
                }else{
                    $kas_keluar_vkasbenn = $kas_keluar_vkasben;
                    $r_vkasben="";
                    $s_vkasben="";
                }
                
                if($selisih_vkasben<0){
                    $selisih_vkasbenn = $selisih_vkasben*-1;
                    $a_vkasben="(";
                    $b_vkasben=")";
                }else{
                    $selisih_vkasbenn = $selisih_vkasben;
                    $a_vkasben="";
                    $b_vkasben="";
                }
            @endphp
            <tr>
                <td align="left">KAS DI BENDAHARA PENGELUARAN</td>
                <td align="center">:</td>
                <td align="right">{{$r_vkasben}}{{rupiah($kas_keluar_vkasbenn)}}{{$s_vkasben}}</td>
            </tr>
            <tr>
                <td align="left">SISA UANG PERSEDIAAN BELUM SETOR</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($sisa_kas_vkasben)}}</td>
            </tr>
            <tr>
                <td align="left">UTANG PFK DI BENDAHARA PENGELUARAN</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($utang_pfk_vkasben)}}</td>
            </tr>
            <tr>
                <td align="center"><b>Selisih</b></td>
                <td align="center"><b>:</b></td>
                <td align="right"><b>{{$a_vkasben}}{{rupiah($selisih_vkasbenn)}}{{$b_vkasben}}</b></td>
            </tr>
            @php
                $tot_vkasben_ket = 0;
            @endphp
            @foreach($vkasben_ket as $ket_a22)
                @php
                    $ket1_vkasben_ket = $ket_a22->ket;
                    $nilai_vkasben_ket = $ket_a22->nilai;
                    $tot_vkasben_ket = $tot_vkasben_ket+$nilai_vkasben_ket;

                    if ($nilai_vkasben_ket<0) {
                        $nilais_vkasben_ket=($nilai_vkasben_ket)*-1;
                        $sa_vkasben_ket="(";
                        $sb_vkasben_ket=")";
                    }else{
                        $nilais_vkasben_ket=($nilai_vkasben_ket);
                        $sa_vkasben_ket="";
                        $sb_vkasben_ket="";
                    }   
                    if($ket1_vkasben_ket<>''){
                        $ket_vkasben_ket = $ket1_vkasben_ket;
                    }else{
                        $ket_vkasben_ket = $ket1_vkasben_ket;
                    }
                @endphp
                <tr>       
                    <td coslpan="2" align="left"><b>{!! $ket_vkasben_ket !!}</td>
                    <td align="left"></td>
                    <td align="right">{{$sa_vkasben_ket}}{{rupiah($nilais_vkasben_ket)}}{{$sb_vkasben_ket}}</td>
                </tr>
                <tr></tr>
            @endforeach
            @php
                if ($tot_vkasben_ket<0) {
                    $tot_vkasben_ket=($tot_vkasben_ket)*-1;
                    $as_vkasben_ket="(";
                    $bs_vkasben_ket=")";
                }else{
                    $tot_vkasben_ket=($tot_vkasben_ket);
                    $as_vkasben_ket="";
                    $bs_vkasben_ket="";
                }   
                $cek_selisih_vkasben_ket=$selisih_vkasben-($tot_vkasben_ket);
            @endphp
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="center"><b>:</b></td>
                @if($cek_selisih_vkasben_ket<>0)
                    <td align="right" bgcolor="red"><b>{{$as_vkasben_ket}}{{rupiah($tot_vkasben_ket)}}{{$bs_vkasben_ket}}</b></td>
                @else
                    <td align="right"><b>{{$as_vkasben_ket}}{{rupiah($tot_vkasben_ket)}}{{$bs_vkasben_ket}}</b></td>
                @endif
                
            </tr>
            @if($jenis==1)
                <tr>
                    <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','a22')">Edit Penjelasan Kasben Vertical</button>
                    </td>                         
                </tr>
            @else
            @endif
        <!---->
        <!-- 3 Vertical LO -->
            <tr>
                <td align="justify" colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" colspan="3"><b>3) Analisis vertikal dalam Laporan Operasional(LO)</b></td>
            </tr>                         
            <tr>
                <td align="center"><b>Uraian</b></td>
                <td align="center">&nbsp;</td>
                <td align="center"><b>Persamaan</b></td>
            </tr> 
            <tr>
                <td align="left">Surplus/Defisit LO harus sama dengan total Pendapatan(LO) dikurangi total Beban(LO) ditambah (dikurangi) total Surplus (Defisit) Kegaitan Non Operasional(LO) ditambah (dikurangi) Pos Luar Biasa(LO)</td>
                <td align="center">&nbsp;</td>
                <td align="left">Surplus/Defisit LO= Total Pendapatan(LO) - Total Beban(LO)+/- Total Surplus/Defisit Kegiatan Non Operasional(LO) +/- Pos Luas Biasa(LO)</td>
            </tr>
            <tr>
                <td align="left">RUMUS</td>
                <td align="center">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            @php
                $sur_def_vlo = $vlo->sur_def;
                $pend_lo_vlo   = $vlo->pend_lo;
                $beban_lo_vlo  = $vlo->beban_lo*-1;
                $keg_non_op_vlo  = $vlo->keg_non_op;
                $pos_lb_vlo  = $vlo->pos_lb;
                $selisih_vlo  = $vlo->selisih;
                
                
                if($sur_def_vlo<0){
                    $sur_def_vloo = $sur_def_vlo*-1;
                    $a_sur_def_vlo="(";
                    $b_sur_def_vlo=")";
                }else{
                    $sur_def_vloo = $sur_def_vlo;
                    $a_sur_def_vlo="";
                    $b_sur_def_vlo="";
                }
                
                if($selisih_vlo<0){
                    $selisih_vloo = $selisih_vlo*-1;
                    $a_selisih_vlo="(";
                    $b_selisih_vlo=")";
                }else{
                    $selisih_vloo = $selisih_vlo;
                    $a_selisih_vlo="";
                    $b_selisih_vlo="";
                }
                
                if($beban_lo_vlo<0){
                    $beban_lo_vloo = $beban_lo_vlo*-1;
                    $a_beban_vlo="(";
                    $b_beban_vlo=")";
                }else{
                    $beban_lo_vloo = $beban_lo_vlo;
                    $a_beban_vlo="";
                    $b_beban_vlo="";
                }
            @endphp
            <tr>
                <td align="left">SURPLUS(DEFISIT) LO</td>
                <td align="center">:</td>
                <td align="right">{{$a_sur_def_vlo}}{{rupiah($sur_def_vloo)}}{{$b_sur_def_vlo}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL PENDAPATAN(LO)</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($pend_lo_vlo)}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL BEBAN(LO)</td>
                <td align="center">:</td>
                <td align="right">{{$a_beban_vlo}}{{rupiah($beban_lo_vloo)}}{{$b_beban_vlo}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL SURPLUS(DEFISIT)KEGIATAN NON OPERASIONAL</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($keg_non_op_vlo)}}</td>
            </tr>
            <tr>
                <td align="left">TOTAL POS LUAR BIASA</td>
                <td align="center">:</td>
                <td align="right">{{rupiah($pos_lb_vlo)}}</td>
            </tr>
            <tr>
                <td align="center"><b>Selisih</b></td>
                <td align="center"><b>:</b></td>
                <td align="right"><b>{{$a_selisih_vlo}}{{rupiah($selisih_vloo)}}{{$b_selisih_vlo}}</b></td>
            </tr>
            @php
                $tot_vlo_ket = 0;
            @endphp
            @foreach($vlo_ket as $ket_a31)
                @php
                    $ket1_vlo_ket = $ket_a31->ket;
                    $nilai_vlo_ket = $ket_a31->nilai;
                    $tot_vlo_ket = $tot_vlo_ket+$nilai_vlo_ket;

                    if ($nilai_vlo_ket<0) {
                        $nilais_vlo_ket=($nilai_vlo_ket)*-1;
                        $sa_vlo_ket="(";
                        $sb_vlo_ket=")";
                    }else{
                        $nilais_vlo_ket=($nilai_vlo_ket);
                        $sa_vlo_ket="";
                        $sb_vlo_ket="";
                    }   
                    if($ket1_vlo_ket<>''){
                        $ket_vlo_ket = $ket1_vlo_ket;
                    }else{
                        $ket_vlo_ket = $ket1_vlo_ket;
                    }
                @endphp
                <tr>       
                    <td coslpan="2" align="left"><b>{!! $ket_vlo_ket !!}</td>
                    <td align="left"></td>
                    <td align="right">{{$sa_vlo_ket}}{{rupiah($nilais_vlo_ket)}}{{$sb_vlo_ket}}</td>
                </tr>
                <tr></tr>
            @endforeach
            @php
                if ($tot_vlo_ket<0) {
                    $tot_vlo_ket=($tot_vlo_ket)*-1;
                    $as_vlo_ket="(";
                    $bs_vlo_ket=")";
                }else{
                    $tot_vlo_ket=($tot_vlo_ket);
                    $as_vlo_ket="";
                    $bs_vlo_ket="";
                }   
                $cek_selisih_vlo_ket=$selisih_vlo-($tot_vlo_ket);
            @endphp
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="center"><b>:</b></td>
                @if($selisih_vlo != $tot_vlo_ket)
                    <td align="right" bgcolor="red"><b>{{$as_vlo_ket}}{{rupiah($tot_vlo_ket)}}{{$bs_vlo_ket}}</b></td>
                @else
                    <td align="right"><b>{{$as_vlo_ket}}{{rupiah($tot_vlo_ket)}}{{$bs_vlo_ket}}</b></td>
                @endif
                
            </tr>
            @if($jenis==1)
                <tr>
                    <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','a31')">Edit Penjelasan LO Vertical</button>
                    </td>                         
                </tr>
            @else
            @endif
        <!-- -->
        <!-- 4 Vertical LPE tanpa SELISIH REVALUASI ASET TETAP-->
            <tr>
                <td align="justify" colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" colspan="3"><b>4) Analisis vertikal dalam Laporan Perubahan Ekuitas</b></td>
            </tr>                         
            <tr>
                <td align="center"><b>Uraian</b></td>
                <td align="center">&nbsp;</td>
                <td align="center"><b>Persamaan</b></td>
            </tr> 
            <tr>
                <td align="left">Ekuitas akhir harus sama dengan ekuitas awal ditambah(dikurangi) surplus/defisit LO ditambah(dikurangi) koreksi berdampak ke ekuitas</td>
                <td align="center">&nbsp;</td>
                <td align="left">Ekuitas akhir = ekuitas awal(+/-) surplus/defisit LO(+/-) koreksi berdampak ke ekuitas</td>
            </tr>
            <tr>
                <td align="left">RUMUS</td>
                <td align="center">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            @php
                $ek_ak_vlpe = $vlpe->eq_akhir;
                $ek_aw_vlpe = $vlpe->eq_awal;
                $surdef_vlpe = $vlpe->sur_def;
                $koreksi_vlpe = $vlpe->koreksi;
                $selisih_vlpe = $vlpe->selisih;
                
                $selisih=$ek_ak_vlpe-($ek_aw_vlpe+$surdef_vlpe+$koreksi_vlpe);
                
                if($ek_ak_vlpe<0){
                    $ek_ak_vlpee = $ek_ak_vlpe*-1;
                    $r_ek_ak_vlpe="(";
                    $s_ek_ak_vlpe=")";
                }else{
                    $ek_ak_vlpee = $ek_ak_vlpe;
                    $r_ek_ak_vlpe="";
                    $s_ek_ak_vlpe="";
                }
                
                if($ek_aw_vlpe<0){
                    $ek_aw_vlpee = $ek_aw_vlpe*-1;
                    $t_ek_aw_vlpe="(";
                    $u_ek_aw_vlpe=")";
                }else{
                    $ek_aw_vlpee = $ek_aw_vlpe;
                    $t_ek_aw_vlpe="";
                    $u_ek_aw_vlpe="";
                }
                
                if($surdef_vlpe<0){
                    $surdef_vlpee = $surdef_vlpe*-1;
                    $v_surdef_vlpe="(";
                    $w_surdef_vlpe=")";
                }else{
                    $surdef_vlpee = $surdef_vlpe;
                    $v_surdef_vlpe="";
                    $w_surdef_vlpe="";
                }
                
                if($koreksi_vlpe<0){
                    $koreksi_vlpee = $koreksi_vlpe*-1;
                    $x_koreksi_vlpe="(";
                    $y_koreksi_vlpe=")";
                }else{
                    $koreksi_vlpee = $koreksi_vlpe;
                    $x_koreksi_vlpe="";
                    $y_koreksi_vlpe="";
                }
                
                if($selisih_vlpe<0){
                    $selisih_vlpee = $selisih_vlpe*-1;
                    $ax_selisih_vlpe="(";
                    $bx_selisih_vlpe=")";
                }else{
                    $selisih_vlpee = $selisih_vlpe;
                    $ax_selisih_vlpe="";
                    $bx_selisih_vlpe="";
                }
            @endphp
            <tr>
                <td align="left">Ekuitas Akhir</td>
                <td align="center">:</td>
                <td align="right">{{$r_ek_ak_vlpe}}{{rupiah($ek_ak_vlpee)}}{{$s_ek_ak_vlpe}}</td>
            </tr>
            <tr>
                <td align="left">Ekuitas Awal</td>
                <td align="center">:</td>
                <td align="right">{{$t_ek_aw_vlpe}}{{rupiah($ek_aw_vlpee)}}{{$u_ek_aw_vlpe}}</td>
            </tr>
            <tr>
                <td align="left">Surplus/defisit LO</td>
                <td align="center">:</td>
                <td align="right">{{$v_surdef_vlpe}}{{rupiah($surdef_vlpee)}}{{$w_surdef_vlpe}}</td>
            </tr>
            <tr>
                <td align="left">Koreksi</td>
                <td align="center">:</td>
                <td align="right">{{$x_koreksi_vlpe}}{{rupiah($koreksi_vlpee)}}{{$y_koreksi_vlpe}}</td>
            </tr>
            <tr>
                <td align="center"><b>Selisih</b></td>
                <td align="center"><b>:</b></td>
                <td align="right"><b>{{$ax_selisih_vlpe}}{{rupiah($selisih_vlpee)}}{{$bx_selisih_vlpe}}</b></td>
            </tr>
            @php
                $tot_vlpe_ket = 0;
            @endphp
            @foreach($vlpe_ket as $ket_a41)
                @php
                    $ket1_vlpe_ket = $ket_a41->ket;
                    $nilai_vlpe_ket = $ket_a41->nilai;
                    $tot_vlpe_ket = $tot_vlpe_ket+$nilai_vlpe_ket;

                    if ($nilai_vlpe_ket<0) {
                        $nilais_vlpe_ket=($nilai_vlpe_ket)*-1;
                        $sa_vlpe_ket="(";
                        $sb_vlpe_ket=")";
                    }else{
                        $nilais_vlpe_ket=($nilai_vlpe_ket);
                        $sa_vlpe_ket="";
                        $sb_vlpe_ket="";
                    }   
                    if($ket1_vlpe_ket<>''){
                        $ket_vlpe_ket = $ket1_vlpe_ket;
                    }else{
                        $ket_vlpe_ket = $ket1_vlpe_ket;
                    }
                @endphp
                <tr>       
                    <td coslpan="2" align="left"><b>{!! $ket_vlpe_ket !!}</td>
                    <td align="left"></td>
                    <td align="right">{{$sa_vlpe_ket}}{{rupiah($nilais_vlpe_ket)}}{{$sb_vlpe_ket}}</td>
                </tr>
                <tr></tr>
            @endforeach
            @php
                if ($tot_vlpe_ket<0) {
                    $tot_vlpe_ket=($tot_vlpe_ket)*-1;
                    $as_vlpe_ket="(";
                    $bs_vlpe_ket=")";
                }else{
                    $tot_vlpe_ket=($tot_vlpe_ket);
                    $as_vlpe_ket="";
                    $bs_vlpe_ket="";
                }   
                $cek_selisih_vlpe_ket=$selisih_vlpe-($tot_vlpe_ket);
            @endphp
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="center"><b>:</b></td>
                @if($cek_selisih_vlpe_ket<>0)
                    <td align="right" bgcolor="red"><b>{{$as_vlpe_ket}}{{rupiah($tot_vlpe_ket)}}{{$bs_vlpe_ket}}</b></td>
                @else
                    <td align="right"><b>{{$as_vlpe_ket}}{{rupiah($tot_vlpe_ket)}}{{$bs_vlpe_ket}}</b></td>
                @endif
                
            </tr>
            @if($jenis==1)
                <tr>
                    <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','a41')">Edit Penjelasan LPE Vertical</button>
                    </td>                         
                </tr>
            @else
            @endif
        <!-- -->
        <tr>
            <td align="justify" colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" colspan="3"><b>b. ANALISIS HORIZONTAL</b></td>
        </tr>
        <!-- 1 Horizontal LRA & Neraca -->
            <tr>
                <td align="left" colspan="3"><b>1) Analisis horizontal antara LRA dan Neraca</b></td>
            </tr>
            <!-- Tanah -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_tanah = $h_1_tanah->realisasi;
                    $tamkur_h_1_tanah = $h_1_tanah->tamkur;
                    $aset_h_1_tanah = $h_1_tanah->aset;
                    $aset_lalu_h_1_tanah = $h_1_tanah->aset_lalu;
                    $selisih_h_1_tanah = $h_1_tanah->selisih;
                    $mutasi_h_1_tanah = $h_1_tanah->mutasi;

                    if($tamkur_h_1_tanah<0){
                        $at_h_1_tanah = "(";
                        $tamkur_h_1_tanahh = $tamkur_h_1_tanah*-1;
                        $bt_h_1_tanah = ")";
                    }else{
                        $at_h_1_tanah = "";
                        $tamkur_h_1_tanahh = $tamkur_h_1_tanah;
                        $bt_h_1_tanah = "";
                    }
                    if($selisih_h_1_tanah<0){
                        $as_h_1_tanah = "(";
                        $selisih_h_1_tanahh = $selisih_h_1_tanah*-1;
                        $bs_h_1_tanah = ")";
                    }else{
                        $as_h_1_tanah = "";
                        $selisih_h_1_tanahh = $selisih_h_1_tanah;
                        $bs_h_1_tanah = "";
                    }
                    if($mutasi_h_1_tanah<0){
                        $am_h_1_tanah = "(";
                        $mutasi_h_1_tanahh = $mutasi_h_1_tanah*-1;
                        $bm_h_1_tanah = ")";
                    }else{
                        $am_h_1_tanah = "";
                        $mutasi_h_1_tanahh = $mutasi_h_1_tanah;
                        $bm_h_1_tanah = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL TANAH</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_tanah)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_tanah}}{{rupiah($tamkur_h_1_tanahh)}}{{$bt_h_1_tanah}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET TANAH {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_tanah)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET TANAH {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_tanah)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_tanah != $mutasi_h_1_tanah)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_tanah}}{{rupiah($selisih_h_1_tanahh)}}{{$bs_h_1_tanah}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_tanah}}{{rupiah($selisih_h_1_tanahh)}}{{$bs_h_1_tanah}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_tanah_ket as $h1_tanah_ket)
                        @php
                            $kd_rek_h1_tanah_ket = $h1_tanah_ket->kd_rek;
                            $nm_rek_h1_tanah_ket = $h1_tanah_ket->nm_rek;
                            $ket_h1_tanah_ket = $h1_tanah_ket->ket;
                            $nilai_h1_tanah_ket = $h1_tanah_ket->nilai;

                            $kode_4_h1_tanah_ket = substr($kd_rek_h1_tanah_ket,0,4);
                        @endphp
                        @if($kode_4_h1_tanah_ket=="1312")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_tanah_ket}} :</b> <br> {{$ket_h1_tanah_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_tanah_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_tanah_ket as $h1_tanah_ket)
                        @php
                            $kd_rek_h1_tanah_ket = $h1_tanah_ket->kd_rek;
                            $nm_rek_h1_tanah_ket = $h1_tanah_ket->nm_rek;
                            $ket_h1_tanah_ket = $h1_tanah_ket->ket;
                            $nilai_h1_tanah_ket = $h1_tanah_ket->nilai;

                            $kode_4_h1_tanah_ket = substr($kd_rek_h1_tanah_ket,0,4);
                        @endphp
                        @if($kode_4_h1_tanah_ket=="1313")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_tanah_ket}} :</b> <br> {{$ket_h1_tanah_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_tanah_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_tanah}}{{rupiah($mutasi_h_1_tanahh)}}{{$bm_h_1_tanah}}</b></td>
                    
                </tr>
            <!-- -->

            <!-- PERALATAN DAN MESIN -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_pmesin = $h_1_pmesin->realisasi;
                    $tamkur_h_1_pmesin = $h_1_pmesin->tamkur;
                    $aset_h_1_pmesin = $h_1_pmesin->aset;
                    $aset_lalu_h_1_pmesin = $h_1_pmesin->aset_lalu;
                    $selisih_h_1_pmesin = $h_1_pmesin->selisih;
                    $mutasi_h_1_pmesin = $h_1_pmesin->mutasi;

                    if($tamkur_h_1_pmesin<0){
                        $at_h_1_pmesin = "(";
                        $tamkur_h_1_pmesinh = $tamkur_h_1_pmesin*-1;
                        $bt_h_1_pmesin = ")";
                    }else{
                        $at_h_1_pmesin = "";
                        $tamkur_h_1_pmesinh = $tamkur_h_1_pmesin;
                        $bt_h_1_pmesin = "";
                    }
                    if($selisih_h_1_pmesin<0){
                        $as_h_1_pmesin = "(";
                        $selisih_h_1_pmesinh = $selisih_h_1_pmesin*-1;
                        $bs_h_1_pmesin = ")";
                    }else{
                        $as_h_1_pmesin = "";
                        $selisih_h_1_pmesinh = $selisih_h_1_pmesin;
                        $bs_h_1_pmesin = "";
                    }
                    if($mutasi_h_1_pmesin<0){
                        $am_h_1_pmesin = "(";
                        $mutasi_h_1_pmesinh = $mutasi_h_1_pmesin*-1;
                        $bm_h_1_pmesin = ")";
                    }else{
                        $am_h_1_pmesin = "";
                        $mutasi_h_1_pmesinh = $mutasi_h_1_pmesin;
                        $bm_h_1_pmesin = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL PERALATAN DAN MESIN</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_pmesin)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_pmesin}}{{rupiah($tamkur_h_1_pmesinh)}}{{$bt_h_1_pmesin}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET PERALATAN DAN MESIN {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_pmesin)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET PERALATAN DAN MESIN {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_pmesin)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_pmesin != $mutasi_h_1_pmesin)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_pmesin}}{{rupiah($selisih_h_1_pmesinh)}}{{$bs_h_1_pmesin}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_pmesin}}{{rupiah($selisih_h_1_pmesinh)}}{{$bs_h_1_pmesin}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_pmesin_ket as $h1_pmesin_ket)
                        @php
                            $kd_rek_h1_pmesin_ket = $h1_pmesin_ket->kd_rek;
                            $nm_rek_h1_pmesin_ket = $h1_pmesin_ket->nm_rek;
                            $ket_h1_pmesin_ket = $h1_pmesin_ket->ket;
                            $nilai_h1_pmesin_ket = $h1_pmesin_ket->nilai;

                            $kode_4_h1_pmesin_ket = substr($kd_rek_h1_pmesin_ket,0,4);
                        @endphp
                        @if($kode_4_h1_pmesin_ket=="1322")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_pmesin_ket}} :</b> <br> {{$ket_h1_pmesin_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_pmesin_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_pmesin_ket as $h1_pmesin_ket)
                        @php
                            $kd_rek_h1_pmesin_ket = $h1_pmesin_ket->kd_rek;
                            $nm_rek_h1_pmesin_ket = $h1_pmesin_ket->nm_rek;
                            $ket_h1_pmesin_ket = $h1_pmesin_ket->ket;
                            $nilai_h1_pmesin_ket = $h1_pmesin_ket->nilai;

                            $kode_4_h1_pmesin_ket = substr($kd_rek_h1_pmesin_ket,0,4);
                        @endphp
                        @if($kode_4_h1_pmesin_ket=="1323")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_pmesin_ket}} :</b> <br> {{$ket_h1_pmesin_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_pmesin_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_pmesin}}{{rupiah($mutasi_h_1_pmesinh)}}{{$bm_h_1_pmesin}}</b></td>
                    
                </tr>
            <!-- -->

            <!-- GEDUNG DAN BANGUNAN -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_gbangunan = $h_1_gbangunan->realisasi;
                    $tamkur_h_1_gbangunan = $h_1_gbangunan->tamkur;
                    $aset_h_1_gbangunan = $h_1_gbangunan->aset;
                    $aset_lalu_h_1_gbangunan = $h_1_gbangunan->aset_lalu;
                    $selisih_h_1_gbangunan = $h_1_gbangunan->selisih;
                    $mutasi_h_1_gbangunan = $h_1_gbangunan->mutasi;

                    if($tamkur_h_1_gbangunan<0){
                        $at_h_1_gbangunan = "(";
                        $tamkur_h_1_gbangunanh = $tamkur_h_1_gbangunan*-1;
                        $bt_h_1_gbangunan = ")";
                    }else{
                        $at_h_1_gbangunan = "";
                        $tamkur_h_1_gbangunanh = $tamkur_h_1_gbangunan;
                        $bt_h_1_gbangunan = "";
                    }
                    if($selisih_h_1_gbangunan<0){
                        $as_h_1_gbangunan = "(";
                        $selisih_h_1_gbangunanh = $selisih_h_1_gbangunan*-1;
                        $bs_h_1_gbangunan = ")";
                    }else{
                        $as_h_1_gbangunan = "";
                        $selisih_h_1_gbangunanh = $selisih_h_1_gbangunan;
                        $bs_h_1_gbangunan = "";
                    }
                    if($mutasi_h_1_gbangunan<0){
                        $am_h_1_gbangunan = "(";
                        $mutasi_h_1_gbangunanh = $mutasi_h_1_gbangunan*-1;
                        $bm_h_1_gbangunan = ")";
                    }else{
                        $am_h_1_gbangunan = "";
                        $mutasi_h_1_gbangunanh = $mutasi_h_1_gbangunan;
                        $bm_h_1_gbangunan = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL GEDUNG DAN BANGUNAN</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_gbangunan)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_gbangunan}}{{rupiah($tamkur_h_1_gbangunanh)}}{{$bt_h_1_gbangunan}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET GEDUNG DAN BANGUNAN {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_gbangunan)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET GEDUNG DAN BANGUNAN {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_gbangunan)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_gbangunan != $mutasi_h_1_gbangunan)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_gbangunan}}{{rupiah($selisih_h_1_gbangunanh)}}{{$bs_h_1_gbangunan}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_gbangunan}}{{rupiah($selisih_h_1_gbangunanh)}}{{$bs_h_1_gbangunan}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_gbangunan_ket as $h1_gbangunan_ket)
                        @php
                            $kd_rek_h1_gbangunan_ket = $h1_gbangunan_ket->kd_rek;
                            $nm_rek_h1_gbangunan_ket = $h1_gbangunan_ket->nm_rek;
                            $ket_h1_gbangunan_ket = $h1_gbangunan_ket->ket;
                            $nilai_h1_gbangunan_ket = $h1_gbangunan_ket->nilai;

                            $kode_4_h1_gbangunan_ket = substr($kd_rek_h1_gbangunan_ket,0,4);
                        @endphp
                        @if($kode_4_h1_gbangunan_ket=="1332")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_gbangunan_ket}} :</b> <br> {{$ket_h1_gbangunan_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_gbangunan_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_gbangunan_ket as $h1_gbangunan_ket)
                        @php
                            $kd_rek_h1_gbangunan_ket = $h1_gbangunan_ket->kd_rek;
                            $nm_rek_h1_gbangunan_ket = $h1_gbangunan_ket->nm_rek;
                            $ket_h1_gbangunan_ket = $h1_gbangunan_ket->ket;
                            $nilai_h1_gbangunan_ket = $h1_gbangunan_ket->nilai;

                            $kode_4_h1_gbangunan_ket = substr($kd_rek_h1_gbangunan_ket,0,4);
                        @endphp
                        @if($kode_4_h1_gbangunan_ket=="1333")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_gbangunan_ket}} :</b> <br> {{$ket_h1_gbangunan_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_gbangunan_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_gbangunan}}{{rupiah($mutasi_h_1_gbangunanh)}}{{$bm_h_1_gbangunan}}</b></td>
                    
                </tr>
            <!-- -->

            <!-- JALAN, IRIGASI DAN JARINGAN -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_jij = $h_1_jij->realisasi;
                    $tamkur_h_1_jij = $h_1_jij->tamkur;
                    $aset_h_1_jij = $h_1_jij->aset;
                    $aset_lalu_h_1_jij = $h_1_jij->aset_lalu;
                    $selisih_h_1_jij = $h_1_jij->selisih;
                    $mutasi_h_1_jij = $h_1_jij->mutasi;

                    if($tamkur_h_1_jij<0){
                        $at_h_1_jij = "(";
                        $tamkur_h_1_jijh = $tamkur_h_1_jij*-1;
                        $bt_h_1_jij = ")";
                    }else{
                        $at_h_1_jij = "";
                        $tamkur_h_1_jijh = $tamkur_h_1_jij;
                        $bt_h_1_jij = "";
                    }
                    if($selisih_h_1_jij<0){
                        $as_h_1_jij = "(";
                        $selisih_h_1_jijh = $selisih_h_1_jij*-1;
                        $bs_h_1_jij = ")";
                    }else{
                        $as_h_1_jij = "";
                        $selisih_h_1_jijh = $selisih_h_1_jij;
                        $bs_h_1_jij = "";
                    }
                    if($mutasi_h_1_jij<0){
                        $am_h_1_jij = "(";
                        $mutasi_h_1_jijh = $mutasi_h_1_jij*-1;
                        $bm_h_1_jij = ")";
                    }else{
                        $am_h_1_jij = "";
                        $mutasi_h_1_jijh = $mutasi_h_1_jij;
                        $bm_h_1_jij = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL JALAN, IRIGASI DAN JARINGAN</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_jij)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_jij}}{{rupiah($tamkur_h_1_jijh)}}{{$bt_h_1_jij}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET JALAN, IRIGASI DAN JARINGAN {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_jij)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET JALAN, IRIGASI DAN JARINGAN {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_jij)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_jij != $mutasi_h_1_jij)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_jij}}{{rupiah($selisih_h_1_jijh)}}{{$bs_h_1_jij}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_jij}}{{rupiah($selisih_h_1_jijh)}}{{$bs_h_1_jij}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_jij_ket as $h1_jij_ket)
                        @php
                            $kd_rek_h1_jij_ket = $h1_jij_ket->kd_rek;
                            $nm_rek_h1_jij_ket = $h1_jij_ket->nm_rek;
                            $ket_h1_jij_ket = $h1_jij_ket->ket;
                            $nilai_h1_jij_ket = $h1_jij_ket->nilai;

                            $kode_4_h1_jij_ket = substr($kd_rek_h1_jij_ket,0,4);
                        @endphp
                        @if($kode_4_h1_jij_ket=="1342")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_jij_ket}} :</b> <br> {{$ket_h1_jij_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_jij_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_jij_ket as $h1_jij_ket)
                        @php
                            $kd_rek_h1_jij_ket = $h1_jij_ket->kd_rek;
                            $nm_rek_h1_jij_ket = $h1_jij_ket->nm_rek;
                            $ket_h1_jij_ket = $h1_jij_ket->ket;
                            $nilai_h1_jij_ket = $h1_jij_ket->nilai;

                            $kode_4_h1_jij_ket = substr($kd_rek_h1_jij_ket,0,4);
                        @endphp
                        @if($kode_4_h1_jij_ket=="1343")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_jij_ket}} :</b> <br> {{$ket_h1_jij_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_jij_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_jij}}{{rupiah($mutasi_h_1_jijh)}}{{$bm_h_1_jij}}</b></td>
                    
                </tr>
            <!-- -->

            <!-- ASET TETAP LAINNYA -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_asettl = $h_1_asettl->realisasi;
                    $tamkur_h_1_asettl = $h_1_asettl->tamkur;
                    $aset_h_1_asettl = $h_1_asettl->aset;
                    $aset_lalu_h_1_asettl = $h_1_asettl->aset_lalu;
                    $selisih_h_1_asettl = $h_1_asettl->selisih;
                    $mutasi_h_1_asettl = $h_1_asettl->mutasi;

                    if($tamkur_h_1_asettl<0){
                        $at_h_1_asettl = "(";
                        $tamkur_h_1_asettlh = $tamkur_h_1_asettl*-1;
                        $bt_h_1_asettl = ")";
                    }else{
                        $at_h_1_asettl = "";
                        $tamkur_h_1_asettlh = $tamkur_h_1_asettl;
                        $bt_h_1_asettl = "";
                    }
                    if($selisih_h_1_asettl<0){
                        $as_h_1_asettl = "(";
                        $selisih_h_1_asettlh = $selisih_h_1_asettl*-1;
                        $bs_h_1_asettl = ")";
                    }else{
                        $as_h_1_asettl = "";
                        $selisih_h_1_asettlh = $selisih_h_1_asettl;
                        $bs_h_1_asettl = "";
                    }
                    if($mutasi_h_1_asettl<0){
                        $am_h_1_asettl = "(";
                        $mutasi_h_1_asettlh = $mutasi_h_1_asettl*-1;
                        $bm_h_1_asettl = ")";
                    }else{
                        $am_h_1_asettl = "";
                        $mutasi_h_1_asettlh = $mutasi_h_1_asettl;
                        $bm_h_1_asettl = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL ASET TETAP LAINNYA</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_asettl)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_asettl}}{{rupiah($tamkur_h_1_asettlh)}}{{$bt_h_1_asettl}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET TETAP LAINNYA {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_asettl)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET TETAP LAINNYA {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_asettl)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_asettl != $mutasi_h_1_asettl)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_asettl}}{{rupiah($selisih_h_1_asettlh)}}{{$bs_h_1_asettl}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_asettl}}{{rupiah($selisih_h_1_asettlh)}}{{$bs_h_1_asettl}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_asettl_ket as $h1_asettl_ket)
                        @php
                            $kd_rek_h1_asettl_ket = $h1_asettl_ket->kd_rek;
                            $nm_rek_h1_asettl_ket = $h1_asettl_ket->nm_rek;
                            $ket_h1_asettl_ket = $h1_asettl_ket->ket;
                            $nilai_h1_asettl_ket = $h1_asettl_ket->nilai;

                            $kode_4_h1_asettl_ket = substr($kd_rek_h1_asettl_ket,0,4);
                        @endphp
                        @if($kode_4_h1_asettl_ket=="1352")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_asettl_ket}} :</b> <br> {{$ket_h1_asettl_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_asettl_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_asettl_ket as $h1_asettl_ket)
                        @php
                            $kd_rek_h1_asettl_ket = $h1_asettl_ket->kd_rek;
                            $nm_rek_h1_asettl_ket = $h1_asettl_ket->nm_rek;
                            $ket_h1_asettl_ket = $h1_asettl_ket->ket;
                            $nilai_h1_asettl_ket = $h1_asettl_ket->nilai;

                            $kode_4_h1_asettl_ket = substr($kd_rek_h1_asettl_ket,0,4);
                        @endphp
                        @if($kode_4_h1_asettl_ket=="1353")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_asettl_ket}} :</b> <br> {{$ket_h1_asettl_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_asettl_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_asettl}}{{rupiah($mutasi_h_1_asettlh)}}{{$bm_h_1_asettl}}</b></td>
                    
                </tr>
            <!-- -->

            <!-- Konstruksi Dalam Pengerjaan -->
                <tr>
                    <td align="justify">Realisasi belanja modal harus sama dengan penambahan aset tetap, jika selisih harus dijelaskan di CALK</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Teliti apakah pengungkapan selisih dalam CaLK sudah cukup memadai. Mungkin ada penerimaan hibah berupa aset dan kapitalisasi biaya. Atau ada kesalahan berupa: salah anggaran selain BM ternyata menghasilkan aset atau aset daerah yang baru ditemukan</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $realisasi_h_1_kontruksi = $h_1_kontruksi->realisasi;
                    $tamkur_h_1_kontruksi = $h_1_kontruksi->tamkur;
                    $aset_h_1_kontruksi = $h_1_kontruksi->aset;
                    $aset_lalu_h_1_kontruksi = $h_1_kontruksi->aset_lalu;
                    $selisih_h_1_kontruksi = $h_1_kontruksi->selisih;
                    $mutasi_h_1_kontruksi = $h_1_kontruksi->mutasi;

                    if($tamkur_h_1_kontruksi<0){
                        $at_h_1_kontruksi = "(";
                        $tamkur_h_1_kontruksih = $tamkur_h_1_kontruksi*-1;
                        $bt_h_1_kontruksi = ")";
                    }else{
                        $at_h_1_kontruksi = "";
                        $tamkur_h_1_kontruksih = $tamkur_h_1_kontruksi;
                        $bt_h_1_kontruksi = "";
                    }
                    if($selisih_h_1_kontruksi<0){
                        $as_h_1_kontruksi = "(";
                        $selisih_h_1_kontruksih = $selisih_h_1_kontruksi*-1;
                        $bs_h_1_kontruksi = ")";
                    }else{
                        $as_h_1_kontruksi = "";
                        $selisih_h_1_kontruksih = $selisih_h_1_kontruksi;
                        $bs_h_1_kontruksi = "";
                    }
                    if($mutasi_h_1_kontruksi<0){
                        $am_h_1_kontruksi = "(";
                        $mutasi_h_1_kontruksih = $mutasi_h_1_kontruksi*-1;
                        $bm_h_1_kontruksi = ")";
                    }else{
                        $am_h_1_kontruksi = "";
                        $mutasi_h_1_kontruksih = $mutasi_h_1_kontruksi;
                        $bm_h_1_kontruksi = "";
                    }
                @endphp
                <tr>
                    <td align="left">REALISASI BELANJA MODAL Konstruksi Dalam Pengerjaan</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($realisasi_h_1_kontruksi)}}</td>
                </tr>
                <tr>
                    <td align="left">PENAMBAHAN(PENURUNAN)</td>
                    <td align="center">:</td>
                    <td align="right">{{$at_h_1_kontruksi}}{{rupiah($tamkur_h_1_kontruksih)}}{{$bt_h_1_kontruksi}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET Konstruksi Dalam Pengerjaan {{$thn_ang}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_h_1_kontruksi)}}</td>
                </tr>
                <tr>
                    <td align="left">- ASET Konstruksi Dalam Pengerjaan {{$thn_ang_1}}</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($aset_lalu_h_1_kontruksi)}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_1_kontruksi != $mutasi_h_1_kontruksi)
                        <td align="right" bgcolor="red" ><b>{{$as_h_1_kontruksi}}{{rupiah($selisih_h_1_kontruksih)}}{{$bs_h_1_kontruksi}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_1_kontruksi}}{{rupiah($selisih_h_1_kontruksih)}}{{$bs_h_1_kontruksi}}</b></td>
                    @endif
                </tr>
                <!-- Mutasi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_1_kontruksi_ket as $h1_kontruksi_ket)
                        @php
                            $kd_rek_h1_kontruksi_ket = $h1_kontruksi_ket->kd_rek;
                            $nm_rek_h1_kontruksi_ket = $h1_kontruksi_ket->nm_rek;
                            $ket_h1_kontruksi_ket = $h1_kontruksi_ket->ket;
                            $nilai_h1_kontruksi_ket = $h1_kontruksi_ket->nilai;

                            $kode_4_h1_kontruksi_ket = substr($kd_rek_h1_kontruksi_ket,0,4);
                        @endphp
                        @if($kode_4_h1_kontruksi_ket=="1362")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_kontruksi_ket}} :</b> <br> {{$ket_h1_kontruksi_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_kontruksi_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Mutasi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Mutasi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_1_kontruksi_ket as $h1_kontruksi_ket)
                        @php
                            $kd_rek_h1_kontruksi_ket = $h1_kontruksi_ket->kd_rek;
                            $nm_rek_h1_kontruksi_ket = $h1_kontruksi_ket->nm_rek;
                            $ket_h1_kontruksi_ket = $h1_kontruksi_ket->ket;
                            $nilai_h1_kontruksi_ket = $h1_kontruksi_ket->nilai;

                            $kode_4_h1_kontruksi_ket = substr($kd_rek_h1_kontruksi_ket,0,4);
                        @endphp
                        @if($kode_4_h1_kontruksi_ket=="1363")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h1_kontruksi_ket}} :</b> <br> {{$ket_h1_kontruksi_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h1_kontruksi_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$am_h_1_kontruksi}}{{rupiah($mutasi_h_1_kontruksih)}}{{$bm_h_1_kontruksi}}</b></td>
                    
                </tr>
            <!-- -->
        <!-- -->

        <!-- 2 horizontal antara LO,Laporan Perubahan Equitas dan Neraca-->
            <!-- Ekuitas Awal -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" colspan="3"><b>2) Analisis horizontal antara LO,Laporan Perubahan Equitas dan Neraca</b></td>
                </tr>                         
                <tr>
                    <td align="justify">Ekuitas Awal pada Laporan Perubahan Ekuitas harus sama dengan Ekuitas Akhir pada Neraca Tahun Sebelumnya</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Ekuitas Awal pada Laporan Perubahan Ekuitas = Ekuitas Akhir pada Neraca Tahun Sebelumnya</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $ek_aw_h_2_eku_awal = $h_2_eku_awal->ek_aw;
                    $ek_sbl_h_2_eku_awal = $h_2_eku_awal->ek_sbl;
                    
                    $selisih_h_2_eku_awal = $ek_aw_h_2_eku_awal-$ek_sbl_h_2_eku_awal;
                    
                    if($ek_aw_h_2_eku_awal<0){
                        $ek_aw_h_2_eku_awall = $ek_aw_h_2_eku_awal*-1;
                        $r_h_2_eku_awal="(";
                        $s_h_2_eku_awal=")";
                    }else{
                        $ek_aw_h_2_eku_awall = $ek_aw_h_2_eku_awal;
                        $r_h_2_eku_awal="";
                        $s_h_2_eku_awal="";
                    }
                    
                    if($ek_sbl_h_2_eku_awal<0){
                        $ek_sbl_h_2_eku_awall = $ek_sbl_h_2_eku_awal*-1;
                        $t_h_2_eku_awal="(";
                        $u_h_2_eku_awal=")";
                    }else{
                        $ek_sbl_h_2_eku_awall = $ek_sbl_h_2_eku_awal;
                        $t_h_2_eku_awal="";
                        $u_h_2_eku_awal="";
                    }
                    
                    if($selisih_h_2_eku_awal<0){
                        $selisih_h_2_eku_awall = $selisih_h_2_eku_awal*-1;
                        $a_h_2_eku_awal="(";
                        $b_h_2_eku_awal=")";
                    }else{
                        $selisih_h_2_eku_awall = $selisih_h_2_eku_awal;
                        $a_h_2_eku_awal="";
                        $b_h_2_eku_awal="";
                    }
                @endphp
                <tr>
                    <td align="left">EKUITAS AWAL(LAPORAN PERUBAHAN EKUITAS)</td>
                    <td align="center">:</td>
                    <td align="right">{{$r_h_2_eku_awal}}{{rupiah($ek_aw_h_2_eku_awall)}}{{$s_h_2_eku_awal}}</td>
                </tr>
                <tr>
                    <td align="left">EKUITAS AKHIR TAHUN SEBELUMNYA (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{$t_h_2_eku_awal}}{{rupiah($ek_sbl_h_2_eku_awall)}}{{$u_h_2_eku_awal}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$a_h_2_eku_awal}}{{rupiah($selisih_h_2_eku_awall)}}{{$b_h_2_eku_awal}}</b></td>
                </tr>
                @php
                    $totsur_h_2_eku_awal_ket = 0;
                @endphp
                @foreach($h_2_eku_awal_ket as $ket_b21)
                    @php
                        $ket1_h_2_eku_awal_ket = $ket_b21->ket;
                        $nilai_h_2_eku_awal_ket = $ket_b21->nilai;
                        $totsur_h_2_eku_awal_ket = $totsur_h_2_eku_awal_ket+$nilai_h_2_eku_awal_ket;

                        if ($nilai_h_2_eku_awal_ket<0) {
                            $nilais_h_2_eku_awal_ket=($nilai_h_2_eku_awal_ket)*-1;
                            $sa_h_2_eku_awal_ket="(";
                            $sb_h_2_eku_awal_ket=")";
                        }else{
                            $nilais_h_2_eku_awal_ket=($nilai_h_2_eku_awal_ket);
                            $sa_h_2_eku_awal_ket="";
                            $sb_h_2_eku_awal_ket="";
                        }   
                        if($ket1_h_2_eku_awal_ket<>''){
                            $ket_h_2_eku_awal_ket = $ket1_h_2_eku_awal_ket;
                        }else{
                            $ket_h_2_eku_awal_ket = $ket1_h_2_eku_awal_ket;
                        }
                    @endphp
                    <tr>       
                        <td coslpan="2" align="left"><b>{!! $ket_h_2_eku_awal_ket !!}</td>
                        <td align="left"></td>
                        <td align="right">{{$sa_h_2_eku_awal_ket}}{{rupiah($nilais_h_2_eku_awal_ket)}}{{$sb_h_2_eku_awal_ket}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totsur_h_2_eku_awal_ket<0) {
                        $tot_sur_h_2_eku_awal_ket=($totsur_h_2_eku_awal_ket)*-1;
                        $as_h_2_eku_awal_ket="(";
                        $bs_h_2_eku_awal_ket=")";
                    }else{
                        $tot_sur_h_2_eku_awal_ket=($totsur_h_2_eku_awal_ket);
                        $as_h_2_eku_awal_ket="";
                        $bs_h_2_eku_awal_ket="";
                    }   
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_2_eku_awal != $totsur_h_2_eku_awal_ket)
                        <td align="right" bgcolor="red"><b>{{$as_h_2_eku_awal_ket}}{{rupiah($tot_sur_h_2_eku_awal_ket)}}{{$bs_h_2_eku_awal_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_2_eku_awal_ket}}{{rupiah($tot_sur_h_2_eku_awal_ket)}}{{$bs_h_2_eku_awal_ket}}</b></td>
                    @endif
                    
                </tr>
                @if($jenis==1)
                    <tr>
                        <td align="justify" colspan="7">
                            <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','b21')">Edit Penjelasan Ekuitas Horizontal</button>
                        </td>                         
                    </tr>
                @else
                @endif
            <!-- -->
            <!-- surdef lo lpe -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Surplus/Defisit pada Laporan Operasional harus sama dengan Surplus/Defisit pada Laporan Perubahan Ekuitas</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Surplus/Defisit pada Laporan Operasional = Surplus/Defisit pada Laporan Perubahan Ekuitas</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $sur_def_lo_h_2_surdef_lolpe = $h_2_surdef_lolpe->sur_def_lo;
                    
                    $selisih_h_2_surdef_lolpe = $sur_def_lo_h_2_surdef_lolpe-$sur_def_lo_h_2_surdef_lolpe;
                    
                    if($sur_def_lo_h_2_surdef_lolpe<0){
                        $sur_def_lo_h_2_surdef_lolpel = $sur_def_lo_h_2_surdef_lolpe*-1;
                        $r_h_2_surdef_lolpe="(";
                        $s_h_2_surdef_lolpe=")";
                    }else{
                        $sur_def_lo_h_2_surdef_lolpel = $sur_def_lo_h_2_surdef_lolpe;
                        $r_h_2_surdef_lolpe="";
                        $s_h_2_surdef_lolpe="";
                    }

                    if($selisih_h_2_surdef_lolpe<0){
                        $selisih_h_2_surdef_lolpel = $selisih_h_2_surdef_lolpe*-1;
                        $a_h_2_surdef_lolpe="(";
                        $b_h_2_surdef_lolpe=")";
                    }else{
                        $selisih_h_2_surdef_lolpel = $selisih_h_2_surdef_lolpe;
                        $a_h_2_surdef_lolpe="";
                        $b_h_2_surdef_lolpe="";
                    }
                @endphp
                <tr>
                    <td align="left">SURPLUS/DEFISIT (LAPORAN OPERASIONAL)</td>
                    <td align="center">:</td>
                    <td align="right">{{$r_h_2_surdef_lolpe}}{{rupiah($sur_def_lo_h_2_surdef_lolpel)}}{{$s_h_2_surdef_lolpe}}</td>
                </tr>
                <tr>
                    <td align="left">SURPLUS/DEFISIT (LAPORAN PERUBAHAN EKUITAS)</td>
                    <td align="center">:</td>
                    <td align="right">{{$r_h_2_surdef_lolpe}}{{rupiah($sur_def_lo_h_2_surdef_lolpel)}}{{$s_h_2_surdef_lolpe}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$a_h_2_surdef_lolpe}}{{rupiah($selisih_h_2_surdef_lolpel)}}{{$b_h_2_surdef_lolpe}}</b></td>
                </tr>
                @php
                    $totsur_h_2_surdef_lolpe_ket = 0;
                @endphp
                @foreach($h_2_surdef_lolpe_ket as $ket_b22)
                    @php
                        $ket1_h_2_surdef_lolpe_ket = $ket_b22->ket;
                        $nilai_h_2_surdef_lolpe_ket = $ket_b22->nilai;
                        $totsur_h_2_surdef_lolpe_ket = $totsur_h_2_surdef_lolpe_ket+$nilai_h_2_surdef_lolpe_ket;

                        if ($nilai_h_2_surdef_lolpe_ket<0) {
                            $nilais_h_2_surdef_lolpe_ket=($nilai_h_2_surdef_lolpe_ket)*-1;
                            $sa_h_2_surdef_lolpe_ket="(";
                            $sb_h_2_surdef_lolpe_ket=")";
                        }else{
                            $nilais_h_2_surdef_lolpe_ket=($nilai_h_2_surdef_lolpe_ket);
                            $sa_h_2_surdef_lolpe_ket="";
                            $sb_h_2_surdef_lolpe_ket="";
                        }   
                        if($ket1_h_2_surdef_lolpe_ket<>''){
                            $ket_h_2_surdef_lolpe_ket = $ket1_h_2_surdef_lolpe_ket;
                        }else{
                            $ket_h_2_surdef_lolpe_ket = $ket1_h_2_surdef_lolpe_ket;
                        }
                    @endphp
                    <tr>       
                        <td coslpan="2" align="left"><b>{!! $ket_h_2_surdef_lolpe_ket !!}</td>
                        <td align="left"></td>
                        <td align="right">{{$sa_h_2_surdef_lolpe_ket}}{{rupiah($nilais_h_2_surdef_lolpe_ket)}}{{$sb_h_2_surdef_lolpe_ket}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totsur_h_2_surdef_lolpe_ket<0) {
                        $tot_sur_h_2_surdef_lolpe_ket=($totsur_h_2_surdef_lolpe_ket)*-1;
                        $as_h_2_surdef_lolpe_ket="(";
                        $bs_h_2_surdef_lolpe_ket=")";
                    }else{
                        $tot_sur_h_2_surdef_lolpe_ket=($totsur_h_2_surdef_lolpe_ket);
                        $as_h_2_surdef_lolpe_ket="";
                        $bs_h_2_surdef_lolpe_ket="";
                    }   
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_2_surdef_lolpe != $totsur_h_2_surdef_lolpe_ket)
                        <td align="right" bgcolor="red"><b>{{$as_h_2_surdef_lolpe_ket}}{{rupiah($tot_sur_h_2_surdef_lolpe_ket)}}{{$bs_h_2_surdef_lolpe_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_2_surdef_lolpe_ket}}{{rupiah($tot_sur_h_2_surdef_lolpe_ket)}}{{$bs_h_2_surdef_lolpe_ket}}</b></td>
                    @endif
                    
                </tr>
                @if($jenis==1)
                    <tr>
                        <td align="justify" colspan="7">
                            <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','b22')">Edit Penjelasan Surplus Horizontal</button>
                        </td>                         
                    </tr>
                @else
                @endif
            <!-- -->
            <!-- Ekuitas Akhir  -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td align="justify">Ekuitas Akhir pada Laporan Perubahan Ekuitas harus sama dengan Ekuitas pada Neraca</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Ekuitas Akhir pada Laporan Perubahan Ekuitas = Ekuitas pada Neraca</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $ekuitas_akhir_h_2_eku_akhir = $h_2_eku_akhir->ekuitas;
                    
                    $selisih_h_2_eku_akhir = $ekuitas_akhir_h_2_eku_akhir-$ekuitas_akhir_h_2_eku_akhir;
                    
                    if($selisih_h_2_eku_akhir<0){
                        $selisih_h_2_eku_akhirr = $selisih_h_2_eku_akhir*-1;
                        $a_h_2_eku_akhir="(";
                        $b=")";
                    }else{
                        $selisih_h_2_eku_akhirr = $selisih_h_2_eku_akhir;
                        $a_h_2_eku_akhir="";
                        $b_h_2_eku_akhir="";
                    }
                    
                    if($ekuitas_akhir_h_2_eku_akhir<0){
                        $ekuitas_akhir_h_2_eku_akhirr = $ekuitas_akhir_h_2_eku_akhir*-1;
                        $c_h_2_eku_akhir="(";
                        $d_h_2_eku_akhir=")";
                    }else{
                        $ekuitas_akhir_h_2_eku_akhirr = $ekuitas_akhir_h_2_eku_akhir;
                        $c_h_2_eku_akhir="";
                        $d_h_2_eku_akhir="";
                    }
                @endphp
                <tr>
                    <td align="left">EKUITAS AKHIR(LAPORAN PERUBAHAN EKUITAS)</td>
                    <td align="center">:</td>
                    <td align="right">{{$c_h_2_eku_akhir}}{{rupiah($ekuitas_akhir_h_2_eku_akhirr)}}{{$d_h_2_eku_akhir}}</td>
                </tr>
                <tr>
                    <td align="left">EKUITAS (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{$c_h_2_eku_akhir}}{{rupiah($ekuitas_akhir_h_2_eku_akhirr)}}{{$d_h_2_eku_akhir}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$a_h_2_eku_akhir}}{{rupiah($selisih_h_2_eku_akhirr)}}{{$b_h_2_eku_akhir}}</b></td>
                </tr>
                @php
                    $totsur_h_2_eku_akhir_ket = 0;
                @endphp
                @foreach($h_2_eku_akhir_ket as $ket_b23)
                    @php
                        $ket1_h_2_eku_akhir_ket = $ket_b23->ket;
                        $nilai_h_2_eku_akhir_ket = $ket_b23->nilai;
                        $totsur_h_2_eku_akhir_ket = $totsur_h_2_eku_akhir_ket+$nilai_h_2_eku_akhir_ket;

                        if ($nilai_h_2_eku_akhir_ket<0) {
                            $nilais_h_2_eku_akhir_ket=($nilai_h_2_eku_akhir_ket)*-1;
                            $sa_h_2_eku_akhir_ket="(";
                            $sb_h_2_eku_akhir_ket=")";
                        }else{
                            $nilais_h_2_eku_akhir_ket=($nilai_h_2_eku_akhir_ket);
                            $sa_h_2_eku_akhir_ket="";
                            $sb_h_2_eku_akhir_ket="";
                        }   
                        if($ket1_h_2_eku_akhir_ket<>''){
                            $ket_h_2_eku_akhir_ket = $ket1_h_2_eku_akhir_ket;
                        }else{
                            $ket_h_2_eku_akhir_ket = $ket1_h_2_eku_akhir_ket;
                        }
                    @endphp
                    <tr>       
                        <td coslpan="2" align="left"><b>{!! $ket_h_2_eku_akhir_ket !!}</td>
                        <td align="left"></td>
                        <td align="right">{{$sa_h_2_eku_akhir_ket}}{{rupiah($nilais_h_2_eku_akhir_ket)}}{{$sb_h_2_eku_akhir_ket}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totsur_h_2_eku_akhir_ket<0) {
                        $tot_sur_h_2_eku_akhir_ket=($totsur_h_2_eku_akhir_ket)*-1;
                        $as_h_2_eku_akhir_ket="(";
                        $bs_h_2_eku_akhir_ket=")";
                    }else{
                        $tot_sur_h_2_eku_akhir_ket=($totsur_h_2_eku_akhir_ket);
                        $as_h_2_eku_akhir_ket="";
                        $bs_h_2_eku_akhir_ket="";
                    }   
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_2_eku_akhir != $totsur_h_2_eku_akhir_ket)
                        <td align="right" bgcolor="red"><b>{{$as_h_2_eku_akhir_ket}}{{rupiah($tot_sur_h_2_eku_akhir_ket)}}{{$bs_h_2_eku_akhir_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_2_eku_akhir_ket}}{{rupiah($tot_sur_h_2_eku_akhir_ket)}}{{$bs_h_2_eku_akhir_ket}}</b></td>
                    @endif
                    
                </tr>
                @if($jenis==1)
                    <tr>
                        <td align="justify" colspan="7">
                            <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','b23')">Edit Penjelasan Ekuitas Akhir Horizontal</button>
                        </td>                         
                    </tr>
                @else
                @endif
            <!-- -->
        <!-- -->

        <!-- 3 horizontal antara LO,LRA dan Neraca -->
            <tr>
                <td align="justify" colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" colspan="3"><b>3) Analisis horizontal antara LO,LRA dan Neraca</b></td>
            </tr>
            <!-- PENDAPATAN PAJAK -->
                <tr>
                    <td align="justify">Pendapatan Pajak (LO) harus sama dengan Pendapatan Pajak (LRA) dikurangi Piutang Pajak Awal Tahun ditambah Piutang Pajak Akhir Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Pendapatan Pajak (LO) = Pendapatan Pajak (LRA) - Piutang Pajak Awal Tahun + Piutang Pajak Akhir Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $pend_pajak_lo_h_3_pen_pajak = $h_3_pen_pajak->pend_pajak_lo;
                    $pend_pajak_lra_h_3_pen_pajak = $h_3_pen_pajak->pend_pajak_lra;
                    $piutang_pajak_akhir_h_3_pen_pajak = $h_3_pen_pajak->piutang_pajak_akhir;
                    $piutang_pajak_awal_h_3_pen_pajak = $h_3_pen_pajak->piutang_pajak_awal;
                    
                    $selisih_h_3_pen_pajak = $pend_pajak_lo_h_3_pen_pajak-($pend_pajak_lra_h_3_pen_pajak+$piutang_pajak_awal_h_3_pen_pajak+$piutang_pajak_akhir_h_3_pen_pajak);
                    
                    if($selisih_h_3_pen_pajak<0){
                        $selisih_h_3_pen_pajakk = $selisih_h_3_pen_pajak*-1;
                        $a_h_3_pen_pajak="(";
                        $b_h_3_pen_pajak=")";
                    }else{
                        $selisih_h_3_pen_pajakk = $selisih_h_3_pen_pajak;
                        $a_h_3_pen_pajak="";
                        $b_h_3_pen_pajak="";
                    }
                    
                    if($piutang_pajak_awal_h_3_pen_pajak<0){
                        $piutang_pajak_awal_h_3_pen_pajakk = $piutang_pajak_awal_h_3_pen_pajak*-1;
                        $c_h_3_pen_pajak="(";
                        $d_h_3_pen_pajak=")";
                    }else{
                        $piutang_pajak_awal_h_3_pen_pajakk = $piutang_pajak_awal_h_3_pen_pajak;
                        $c_h_3_pen_pajak="";
                        $d_h_3_pen_pajak="";
                    }
                @endphp
                <tr>
                    <td align="left">PENDAPATAN PAJAK (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($pend_pajak_lo_h_3_pen_pajak)}}</td>
                </tr>
                <tr>
                    <td align="left">PENDAPATAN PAJAK (LRA)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($pend_pajak_lra_h_3_pen_pajak)}}</td>
                </tr>
                <tr>
                    <td align="left">PIUTANG PAJAK AKHIR TAHUN (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($piutang_pajak_akhir_h_3_pen_pajak)}}</td>
                </tr>
                <tr>
                    <td align="left">PIUTANG PAJAK AWAL TAHUN (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{$c_h_3_pen_pajak}}{{rupiah($piutang_pajak_awal_h_3_pen_pajakk)}}{{$d_h_3_pen_pajak}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$a_h_3_pen_pajak}}{{rupiah($selisih_h_3_pen_pajakk)}}{{$b_h_3_pen_pajak}}</b></td>
                </tr>
                @php
                    $totsur_h_3_pen_pajak_ket = 0;
                @endphp
                @foreach($h_3_pen_pajak_ket as $ket_b23)
                    @php
                        $ket1_h_3_pen_pajak_ket = $ket_b23->ket;
                        $nilai_h_3_pen_pajak_ket = $ket_b23->nilai;
                        $totsur_h_3_pen_pajak_ket = $totsur_h_3_pen_pajak_ket+$nilai_h_3_pen_pajak_ket;

                        if ($nilai_h_3_pen_pajak_ket<0) {
                            $nilais_h_3_pen_pajak_ket=($nilai_h_3_pen_pajak_ket)*-1;
                            $sa_h_3_pen_pajak_ket="(";
                            $sb_h_3_pen_pajak_ket=")";
                        }else{
                            $nilais_h_3_pen_pajak_ket=($nilai_h_3_pen_pajak_ket);
                            $sa_h_3_pen_pajak_ket="";
                            $sb_h_3_pen_pajak_ket="";
                        }   
                        if($ket1_h_3_pen_pajak_ket<>''){
                            $ket_h_3_pen_pajak_ket = $ket1_h_3_pen_pajak_ket;
                        }else{
                            $ket_h_3_pen_pajak_ket = $ket1_h_3_pen_pajak_ket;
                        }
                    @endphp
                    <tr>       
                        <td coslpan="2" align="left"><b>{!! $ket_h_3_pen_pajak_ket !!}</td>
                        <td align="left"></td>
                        <td align="right">{{$sa_h_3_pen_pajak_ket}}{{rupiah($nilais_h_3_pen_pajak_ket)}}{{$sb_h_3_pen_pajak_ket}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totsur_h_3_pen_pajak_ket<0) {
                        $tot_sur_h_3_pen_pajak_ket=($totsur_h_3_pen_pajak_ket)*-1;
                        $as_h_3_pen_pajak_ket="(";
                        $bs_h_3_pen_pajak_ket=")";
                    }else{
                        $tot_sur_h_3_pen_pajak_ket=($totsur_h_3_pen_pajak_ket);
                        $as_h_3_pen_pajak_ket="";
                        $bs_h_3_pen_pajak_ket="";
                    }   
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_pen_pajak != $totsur_h_3_pen_pajak_ket)
                        <td align="right" bgcolor="red"><b>{{$as_h_3_pen_pajak_ket}}{{rupiah($tot_sur_h_3_pen_pajak_ket)}}{{$bs_h_3_pen_pajak_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_pen_pajak_ket}}{{rupiah($tot_sur_h_3_pen_pajak_ket)}}{{$bs_h_3_pen_pajak_ket}}</b></td>
                    @endif
                    
                </tr>
                @if($jenis==1)
                    <tr>
                        <td align="justify" colspan="7">
                            <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','b31')">Edit Penjelasan Pendapatan Pajak Horizontal</button>
                        </td>                         
                    </tr>
                @else
                @endif
            <!-- -->
            <!-- PENDAPATAN RETRIBUSI -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Pendapatan Retribusi (LO) harus sama dengan Pendapatan Retribusi (LRA) dikurangi Piutang Retribusi Awal Tahun ditambah Piutang Retribusi Akhir Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Pendapatan Retribusi (LO) = Pendapatan Retribusi (LRA) - Piutang Retribusi Awal Tahun + Piutang Retribusi Akhir Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $pend_retri_lo_h_3_pen_retribusi = $h_3_pen_retribusi->pend_retri_lo;
                    $pend_retri_lra_h_3_pen_retribusi = $h_3_pen_retribusi->pend_retri_lra;
                    $piutang_retri_akhir_h_3_pen_retribusi = $h_3_pen_retribusi->piutang_retri_akhir;
                    $piutang_retri_awal_h_3_pen_retribusi = $h_3_pen_retribusi->piutang_retri_awal;

                    $selisih_h_3_pen_retribusi = $pend_retri_lo_h_3_pen_retribusi-($pend_retri_lra_h_3_pen_retribusi+$piutang_retri_awal_h_3_pen_retribusi+$piutang_retri_akhir_h_3_pen_retribusi);
                    
                    if($selisih_h_3_pen_retribusi<0){
                        $selisih_h_3_pen_retribusii = $selisih_h_3_pen_retribusi*-1;
                        $a_h_3_pen_retribusi="(";
                        $b_h_3_pen_retribusi=")";
                    }else{
                        $selisih_h_3_pen_retribusii = $selisih_h_3_pen_retribusi;
                        $a_h_3_pen_retribusi="";
                        $b_h_3_pen_retribusi="";
                    }
                    
                    if($piutang_retri_awal_h_3_pen_retribusi<0){
                        $piutang_retri_awal_h_3_pen_retribusii = $piutang_retri_awal_h_3_pen_retribusi*-1;
                        $c_h_3_pen_retribusi="(";
                        $d_h_3_pen_retribusi=")";
                    }else{
                        $piutang_retri_awal_h_3_pen_retribusii = $piutang_retri_awal_h_3_pen_retribusi;
                        $c_h_3_pen_retribusi="";
                        $d_h_3_pen_retribusi="";
                    }
                @endphp
                <tr>
                    <td align="left">PENDAPATAN RETRIBUSI (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($pend_retri_lo_h_3_pen_retribusi)}}</td>
                </tr>
                <tr>
                    <td align="left">PENDAPATAN RETRIBUSI (LRA)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($pend_retri_lra_h_3_pen_retribusi)}}</td>
                </tr>
                <tr>
                    <td align="left">PIUTANG RETRIBUSI AKHIR TAHUN (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($piutang_retri_akhir_h_3_pen_retribusi)}}</td>
                </tr>
                <tr>
                    <td align="left">PIUTANG RETRIBUSI AWAL TAHUN (NERACA)</td>
                    <td align="center">:</td>
                    <td align="right">{{$c_h_3_pen_retribusi}}{{rupiah($piutang_retri_awal_h_3_pen_retribusii)}}{{$d_h_3_pen_retribusi}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$a_h_3_pen_retribusi}}{{rupiah($selisih_h_3_pen_retribusii)}}{{$b_h_3_pen_retribusi}}</b></td>
                </tr>
                @php
                    $totsur_h_3_pen_retribusi_ket = 0;
                @endphp
                @foreach($h_3_pen_retribusi_ket as $ket_b23)
                    @php
                        $ket1_h_3_pen_retribusi_ket = $ket_b23->ket;
                        $nilai_h_3_pen_retribusi_ket = $ket_b23->nilai;
                        $totsur_h_3_pen_retribusi_ket = $totsur_h_3_pen_retribusi_ket+$nilai_h_3_pen_retribusi_ket;

                        if ($nilai_h_3_pen_retribusi_ket<0) {
                            $nilais_h_3_pen_retribusi_ket=($nilai_h_3_pen_retribusi_ket)*-1;
                            $sa_h_3_pen_retribusi_ket="(";
                            $sb_h_3_pen_retribusi_ket=")";
                        }else{
                            $nilais_h_3_pen_retribusi_ket=($nilai_h_3_pen_retribusi_ket);
                            $sa_h_3_pen_retribusi_ket="";
                            $sb_h_3_pen_retribusi_ket="";
                        }   
                        if($ket1_h_3_pen_retribusi_ket<>''){
                            $ket_h_3_pen_retribusi_ket = $ket1_h_3_pen_retribusi_ket;
                        }else{
                            $ket_h_3_pen_retribusi_ket = $ket1_h_3_pen_retribusi_ket;
                        }
                    @endphp
                    <tr>       
                        <td coslpan="2" align="left"><b>{!! $ket_h_3_pen_retribusi_ket !!}</td>
                        <td align="left"></td>
                        <td align="right">{{$sa_h_3_pen_retribusi_ket}}{{rupiah($nilais_h_3_pen_retribusi_ket)}}{{$sb_h_3_pen_retribusi_ket}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totsur_h_3_pen_retribusi_ket<0) {
                        $tot_sur_h_3_pen_retribusi_ket=($totsur_h_3_pen_retribusi_ket)*-1;
                        $as_h_3_pen_retribusi_ket="(";
                        $bs_h_3_pen_retribusi_ket=")";
                    }else{
                        $tot_sur_h_3_pen_retribusi_ket=($totsur_h_3_pen_retribusi_ket);
                        $as_h_3_pen_retribusi_ket="";
                        $bs_h_3_pen_retribusi_ket="";
                    }   
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_pen_retribusi != $totsur_h_3_pen_retribusi_ket)
                        <td align="right" bgcolor="red"><b>{{$as_h_3_pen_retribusi_ket}}{{rupiah($tot_sur_h_3_pen_retribusi_ket)}}{{$bs_h_3_pen_retribusi_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_pen_retribusi_ket}}{{rupiah($tot_sur_h_3_pen_retribusi_ket)}}{{$bs_h_3_pen_retribusi_ket}}</b></td>
                    @endif
                    
                </tr>
                @if($jenis==1)
                    <tr>
                        <td align="justify" colspan="7">
                            <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','b32')">Edit Penjelasan Pendapatan Retribusi Horizontal</button>
                        </td>                         
                    </tr>
                @else
                @endif
            <!-- -->
            <!-- PERSEDIAAN -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Persediaan (LO) harus sama dengan Belanja Barang dan Jasa Persediaan (LRA) ditambah Persediaan Awal Tahun dikurangi Persdiaan Akhir Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Persediaan(LO)=Belanja Barang dan Jasa Persediaan (LRA)+Persediaan Awal Tahun-Persediaan Akhir Tahun.Perhatikan cara penilaian persediaan: FIFO atau <i>weighted average</i></td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_persediaan_h_3_persediaan = $h_3_persediaan->beban_persediaan;
                    $belanja_persediaan_h_3_persediaan = $h_3_persediaan->belanja_persediaan;
                    $persediaan_awal_h_3_persediaan = $h_3_persediaan->persediaan_awal;
                    $persediaan_akhir_h_3_persediaan = $h_3_persediaan->persediaan_akhir;
                    $persediaan_lain_awal_h_3_persediaan = $h_3_persediaan->persediaan_lain_awal;
                    $persediaan_lain_akhir_h_3_persediaan = $h_3_persediaan->persediaan_lain_akhir;
                    $selisih_h_3_persediaan = $h_3_persediaan->selisih;
                    if($selisih_h_3_persediaan<0){
                        $selisih_h_3_persediaann = $selisih_h_3_persediaan*-1;
                        $a_h_3_persediaan="(";
                        $b_h_3_persediaan=")";
                    }else{
                        $selisih_h_3_persediaann = $selisih_h_3_persediaan;
                        $a_h_3_persediaan="";
                        $b_h_3_persediaan="";
                    }
                    
                    if($persediaan_akhir_h_3_persediaan<0){
                        $persediaan_akhir_h_3_persediaann = $persediaan_akhir_h_3_persediaan*-1;
                        $c_h_3_persediaan="(";
                        $d_h_3_persediaan=")";
                    }else{
                        $persediaan_akhir_h_3_persediaann = $persediaan_akhir_h_3_persediaan;
                        $c_h_3_persediaan="";
                        $d_h_3_persediaan="";
                    }
                    
                    if($persediaan_lain_akhir_h_3_persediaan<0){
                        $persediaan_lain_akhir_h_3_persediaann = $persediaan_lain_akhir_h_3_persediaan*-1;
                        $e_h_3_persediaan="(";
                        $f_h_3_persediaan=")";
                    }else{
                        $persediaan_lain_akhir_h_3_persediaann = $persediaan_lain_akhir_h_3_persediaan;
                        $e_h_3_persediaan="";
                        $f_h_3_persediaan="";
                    }
                @endphp
                <tr>
                    <td align="left">BEBAN PERSEDIAAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($beban_persediaan_h_3_persediaan)}}</td>
                </tr>
                <tr>
                    <td align="left">BELANJA BARANG DAN JASA - PERSEDIAAN (LRA)</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($belanja_persediaan_h_3_persediaan)}}</td>
                </tr>
                <tr>
                    <td align="left">PERSEDIAAN AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{rupiah($persediaan_awal_h_3_persediaan)}}</td>
                </tr>
                <tr>
                    <td align="left">PERSEDIAAN AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$c_h_3_persediaan}}{{rupiah($persediaan_akhir_h_3_persediaann)}}{{$d_h_3_persediaan}}</td>
                </tr>
                
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right" ><b>{{$a_h_3_persediaan}}{{rupiah($selisih_h_3_persediaann)}}{{$b_h_3_persediaan}}</b></td>
                </tr>
                @php
                    $arr_az_h_3_persediaan_ket = "a";
                    $tot_lo_pers_h_3_persediaan_ket = 0;
                    $totalp_h_3_persediaan_ket=0;
                @endphp
                @foreach($h_3_persediaan_ket as $h3_persediaan_ket)
                    @php
                        $kd_rek_h_3_persediaan_ket        = $h3_persediaan_ket->kd_rek;
                        $nm_rek_h_3_persediaan_ket        = $h3_persediaan_ket->nm_rek;
                        $nilai_h_3_persediaan_ket         = $h3_persediaan_ket->nilai;
                        $totalp_h_3_persediaan_ket        = $totalp_h_3_persediaan_ket+$nilai_h_3_persediaan_ket;
                    @endphp
                    <tr>
                        <td coslpan="2" align="left">{{$nm_rek_h_3_persediaan_ket}}</td>
                        <td align="left"></td>
                        <td align="right">{{rupiah($nilai_h_3_persediaan_ket)}}</td>
                    </tr>
                    <tr></tr>
                @endforeach
                @php
                    if ($totalp_h_3_persediaan_ket<0) {
                        $totalp_h_3_persediaan_kett=($totalp_h_3_persediaan_ket)*-1;
                        $ap_h_3_persediaan_ket="(";
                        $bp_h_3_persediaan_ket=")";
                    }else{
                        $totalp_h_3_persediaan_kett=($totalp_h_3_persediaan_ket);
                        $ap_h_3_persediaan_ket="";
                        $bp_h_3_persediaan_ket="";
                    }
                @endphp
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_persediaan != $totalp_h_3_persediaan_ket)
                        <td align="right" bgcolor="red"><b>{{$ap_h_3_persediaan_ket}}{{rupiah($totalp_h_3_persediaan_kett)}}{{$bp_h_3_persediaan_ket}}</b></td>
                    @else
                        <td align="right"><b>{{$ap_h_3_persediaan_ket}}{{rupiah($totalp_h_3_persediaan_kett)}}{{$bp_h_3_persediaan_ket}}</b></td>
                    @endif
                </tr>
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
            <!-- -->
            <!-- AKUMULASI PENYUSUTAN PERALATAN DAN MESIN -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan (LO) harus sama dengan Akumulasi Penyusutan Peralatan dan Mesin Akhir Tahun dikurangi Akumulasi Penyusutan Peralatan dan Mesin Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Penyusutan Peralatan dan Mesin Akhir Tahun - Akumulasi Penyusutan Peralatan dan Mesin Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_ppm = $h_3_akum_ppm->beban;
                    $sal_awal_h_3_akum_ppm = $h_3_akum_ppm->sal_awal;
                    $sal_akhir_h_3_akum_ppm = $h_3_akum_ppm->sal_akhir;
                    $selisih_h_3_akum_ppm = $h_3_akum_ppm->selisih;
                    $sal_ket_h_3_akum_ppm = $h_3_akum_ppm->sal_ket;
                    $penyusutan_h_3_akum_ppm = $h_3_akum_ppm->penyusutan;
                    $total_h_3_akum_ppm = $penyusutan_h_3_akum_ppm + $sal_ket_h_3_akum_ppm;
                    
                    if($total_h_3_akum_ppm < 0){
                        $at_h_3_akum_ppm = "(";
                        $total_h_3_akum_ppmm = $total_h_3_akum_ppm*-1;
                        $bt_h_3_akum_ppm = ")";
                    }else{
                        $at_h_3_akum_ppm = "";
                        $total_h_3_akum_ppmm = $total_h_3_akum_ppm;
                        $bt_h_3_akum_ppm = "";
                    }
                    
                    if($beban_h_3_akum_ppm < 0){
                        $ab_h_3_akum_ppm = "(";
                        $beban_h_3_akum_ppmm = $beban_h_3_akum_ppm*-1;
                        $bb_h_3_akum_ppm = ")";
                    }else{
                        $ab_h_3_akum_ppm = "";
                        $beban_h_3_akum_ppmm = $beban_h_3_akum_ppm;
                        $bb_h_3_akum_ppm = "";
                    }
                    
                    if($sal_awal_h_3_akum_ppm < 0){
                        $aaw_h_3_akum_ppm = "(";
                        $sal_awal_h_3_akum_ppmm = $sal_awal_h_3_akum_ppm*-1;
                        $baw_h_3_akum_ppm = ")";
                    }else{
                        $aaw_h_3_akum_ppm = "";
                        $sal_awal_h_3_akum_ppmm = $sal_awal_h_3_akum_ppm;
                        $baw_h_3_akum_ppm = "";
                    }
                    
                    if($sal_akhir_h_3_akum_ppm < 0){
                        $aak_h_3_akum_ppm = "(";
                        $sal_akhir_h_3_akum_ppmm = $sal_akhir_h_3_akum_ppm*-1;
                        $bak_h_3_akum_ppm = ")";
                    }else{
                        $aak_h_3_akum_ppm = "";
                        $sal_akhir_h_3_akum_ppmm = $sal_akhir_h_3_akum_ppm;
                        $bak_h_3_akum_ppm = "";
                    }
                    
                    if($selisih_h_3_akum_ppm < 0){
                        $as_h_3_akum_ppm = "(";
                        $selisih_h_3_akum_ppmm = $selisih_h_3_akum_ppm*-1;
                        $bs_h_3_akum_ppm = ")";
                    }else{
                        $as_h_3_akum_ppm = "";
                        $selisih_h_3_akum_ppmm = $selisih_h_3_akum_ppm;
                        $bs_h_3_akum_ppm = "";
                    }
                    
                    if($sal_ket_h_3_akum_ppm < 0){
                        $ask_h_3_akum_ppm = "(";
                        $sal_ket_h_3_akum_ppmm = $sal_ket_h_3_akum_ppm*-1;
                        $bsk_h_3_akum_ppm = ")";
                    }else{
                        $ask_h_3_akum_ppm = "";
                        $sal_ket_h_3_akum_ppmm = $sal_ket_h_3_akum_ppm;
                        $bsk_h_3_akum_ppm = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_ppm}}{{rupiah($beban_h_3_akum_ppmm)}}{{$bb_h_3_akum_ppm}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN PERALATAN DAN MESIN AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_ppm}}{{rupiah($sal_akhir_h_3_akum_ppmm)}}{{$bak_h_3_akum_ppm}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN PERALATAN DAN MESIN AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_ppm}}{{rupiah($sal_awal_h_3_akum_ppmm)}}{{$baw_h_3_akum_ppm}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_ppm != $total_h_3_akum_ppm)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_ppm}}{{rupiah($selisih_h_3_akum_ppmm)}}{{$bs_h_3_akum_ppm}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_ppm}}{{rupiah($selisih_h_3_akum_ppmm)}}{{$bs_h_3_akum_ppm}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_ppm_ket as $h3_akum_ppm_ket)
                        @php
                            $kd_rek_h3_akum_ppm_ket = $h3_akum_ppm_ket->kd_rek;
                            $nm_rek_h3_akum_ppm_ket = $h3_akum_ppm_ket->nm_rek;
                            $ket_h3_akum_ppm_ket = $h3_akum_ppm_ket->ket;
                            $nilai_h3_akum_ppm_ket = $h3_akum_ppm_ket->nilai;

                            $kode_h3_akum_ppm_ket = substr($kd_rek_h3_akum_ppm_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_ppm_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_ppm_ket}} :</b> <br> {{$ket_h3_akum_ppm_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_ppm_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_ppm_ket as $h3_akum_ppm_ket)
                        @php
                            $kd_rek_h3_akum_ppm_ket = $h3_akum_ppm_ket->kd_rek;
                            $nm_rek_h3_akum_ppm_ket = $h3_akum_ppm_ket->nm_rek;
                            $ket_h3_akum_ppm_ket = $h3_akum_ppm_ket->ket;
                            $nilai_h3_akum_ppm_ket = $h3_akum_ppm_ket->nilai;

                            $kode_h3_akum_ppm_ket = substr($kd_rek_h3_akum_ppm_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_ppm_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_ppm_ket}} :</b> <br> {{$ket_h3_akum_ppm_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_ppm_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_ppm < 0 ? '(' . rupiah($penyusutan_h_3_akum_ppm * -1) . ')' : rupiah($penyusutan_h_3_akum_ppm) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_ppm}}{{rupiah($total_h_3_akum_ppmm)}}{{$bt_h_3_akum_ppm}}</b></td>
                </tr>
            <!-- -->
            <!-- Akumulasi Penyusutan Gedung dan Bangunan -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan (LO) harus sama dengan Akumulasi Penyusutan Gedung dan Bangunan Akhir Tahun dikurangi Akumulasi Penyusutan Gedung dan Bangunan Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Penyusutan Gedung dan Bangunan Akhir Tahun - Akumulasi Penyusutan Gedung dan Bangunan Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_pgb = $h_3_akum_pgb->beban;
                    $sal_awal_h_3_akum_pgb = $h_3_akum_pgb->sal_awal;
                    $sal_akhir_h_3_akum_pgb = $h_3_akum_pgb->sal_akhir;
                    $selisih_h_3_akum_pgb = $h_3_akum_pgb->selisih;
                    $sal_ket_h_3_akum_pgb = $h_3_akum_pgb->sal_ket;
                    $penyusutan_h_3_akum_pgb = $h_3_akum_pgb->penyusutan;
                    $total_h_3_akum_pgb = $penyusutan_h_3_akum_pgb + $sal_ket_h_3_akum_pgb;
                    
                    if($total_h_3_akum_pgb < 0){
                        $at_h_3_akum_pgb = "(";
                        $total_h_3_akum_pgbm = $total_h_3_akum_pgb*-1;
                        $bt_h_3_akum_pgb = ")";
                    }else{
                        $at_h_3_akum_pgb = "";
                        $total_h_3_akum_pgbm = $total_h_3_akum_pgb;
                        $bt_h_3_akum_pgb = "";
                    }
                    
                    if($beban_h_3_akum_pgb < 0){
                        $ab_h_3_akum_pgb = "(";
                        $beban_h_3_akum_pgbm = $beban_h_3_akum_pgb*-1;
                        $bb_h_3_akum_pgb = ")";
                    }else{
                        $ab_h_3_akum_pgb = "";
                        $beban_h_3_akum_pgbm = $beban_h_3_akum_pgb;
                        $bb_h_3_akum_pgb = "";
                    }
                    
                    if($sal_awal_h_3_akum_pgb < 0){
                        $aaw_h_3_akum_pgb = "(";
                        $sal_awal_h_3_akum_pgbm = $sal_awal_h_3_akum_pgb*-1;
                        $baw_h_3_akum_pgb = ")";
                    }else{
                        $aaw_h_3_akum_pgb = "";
                        $sal_awal_h_3_akum_pgbm = $sal_awal_h_3_akum_pgb;
                        $baw_h_3_akum_pgb = "";
                    }
                    
                    if($sal_akhir_h_3_akum_pgb < 0){
                        $aak_h_3_akum_pgb = "(";
                        $sal_akhir_h_3_akum_pgbm = $sal_akhir_h_3_akum_pgb*-1;
                        $bak_h_3_akum_pgb = ")";
                    }else{
                        $aak_h_3_akum_pgb = "";
                        $sal_akhir_h_3_akum_pgbm = $sal_akhir_h_3_akum_pgb;
                        $bak_h_3_akum_pgb = "";
                    }
                    
                    if($selisih_h_3_akum_pgb < 0){
                        $as_h_3_akum_pgb = "(";
                        $selisih_h_3_akum_pgbm = $selisih_h_3_akum_pgb*-1;
                        $bs_h_3_akum_pgb = ")";
                    }else{
                        $as_h_3_akum_pgb = "";
                        $selisih_h_3_akum_pgbm = $selisih_h_3_akum_pgb;
                        $bs_h_3_akum_pgb = "";
                    }
                    
                    if($sal_ket_h_3_akum_pgb < 0){
                        $ask_h_3_akum_pgb = "(";
                        $sal_ket_h_3_akum_pgbm = $sal_ket_h_3_akum_pgb*-1;
                        $bsk_h_3_akum_pgb = ")";
                    }else{
                        $ask_h_3_akum_pgb = "";
                        $sal_ket_h_3_akum_pgbm = $sal_ket_h_3_akum_pgb;
                        $bsk_h_3_akum_pgb = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_pgb}}{{rupiah($beban_h_3_akum_pgbm)}}{{$bb_h_3_akum_pgb}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN GEDUNG DAN BANGUNAN AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_pgb}}{{rupiah($sal_akhir_h_3_akum_pgbm)}}{{$bak_h_3_akum_pgb}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN GEDUNG DAN BANGUNAN AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_pgb}}{{rupiah($sal_awal_h_3_akum_pgbm)}}{{$baw_h_3_akum_pgb}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_pgb != $total_h_3_akum_pgb)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_pgb}}{{rupiah($selisih_h_3_akum_pgbm)}}{{$bs_h_3_akum_pgb}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_pgb}}{{rupiah($selisih_h_3_akum_pgbm)}}{{$bs_h_3_akum_pgb}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pgb_ket as $h3_akum_pgb_ket)
                        @php
                            $kd_rek_h3_akum_pgb_ket = $h3_akum_pgb_ket->kd_rek;
                            $nm_rek_h3_akum_pgb_ket = $h3_akum_pgb_ket->nm_rek;
                            $ket_h3_akum_pgb_ket = $h3_akum_pgb_ket->ket;
                            $nilai_h3_akum_pgb_ket = $h3_akum_pgb_ket->nilai;

                            $kode_h3_akum_pgb_ket = substr($kd_rek_h3_akum_pgb_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pgb_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pgb_ket}} :</b> <br> {{$ket_h3_akum_pgb_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pgb_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pgb_ket as $h3_akum_pgb_ket)
                        @php
                            $kd_rek_h3_akum_pgb_ket = $h3_akum_pgb_ket->kd_rek;
                            $nm_rek_h3_akum_pgb_ket = $h3_akum_pgb_ket->nm_rek;
                            $ket_h3_akum_pgb_ket = $h3_akum_pgb_ket->ket;
                            $nilai_h3_akum_pgb_ket = $h3_akum_pgb_ket->nilai;

                            $kode_h3_akum_pgb_ket = substr($kd_rek_h3_akum_pgb_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pgb_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pgb_ket}} :</b> <br> {{$ket_h3_akum_pgb_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pgb_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_pgb < 0 ? '(' . rupiah($penyusutan_h_3_akum_pgb * -1) . ')' : rupiah($penyusutan_h_3_akum_pgb) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_pgb}}{{rupiah($total_h_3_akum_pgbm)}}{{$bt_h_3_akum_pgb}}</b></td>
                </tr>
            <!-- -->
            <!-- Akumulasi Penyusutan Jalan, Jaringan, danIrigasi -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan (LO) harus sama dengan Akumulasi Penyusutan Jalan, Irigasi dan Jaringan Akhir Tahun dikurangi Akumulasi Penyusutan Jalan, Irigasi dan Jaringan Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Penyusutan Jalan, Irigasi dan Jaringan Akhir Tahun - Akumulasi Penyusutan Jalan, Irigasi dan Jaringan Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_pjji = $h_3_akum_pjji->beban;
                    $sal_awal_h_3_akum_pjji = $h_3_akum_pjji->sal_awal;
                    $sal_akhir_h_3_akum_pjji = $h_3_akum_pjji->sal_akhir;
                    $selisih_h_3_akum_pjji = $h_3_akum_pjji->selisih;
                    $sal_ket_h_3_akum_pjji = $h_3_akum_pjji->sal_ket;
                    $penyusutan_h_3_akum_pjji = $h_3_akum_pjji->penyusutan;
                    $total_h_3_akum_pjji = $penyusutan_h_3_akum_pjji + $sal_ket_h_3_akum_pjji;
                    
                    if($total_h_3_akum_pjji < 0){
                        $at_h_3_akum_pjji = "(";
                        $total_h_3_akum_pjjim = $total_h_3_akum_pjji*-1;
                        $bt_h_3_akum_pjji = ")";
                    }else{
                        $at_h_3_akum_pjji = "";
                        $total_h_3_akum_pjjim = $total_h_3_akum_pjji;
                        $bt_h_3_akum_pjji = "";
                    }
                    
                    if($beban_h_3_akum_pjji < 0){
                        $ab_h_3_akum_pjji = "(";
                        $beban_h_3_akum_pjjim = $beban_h_3_akum_pjji*-1;
                        $bb_h_3_akum_pjji = ")";
                    }else{
                        $ab_h_3_akum_pjji = "";
                        $beban_h_3_akum_pjjim = $beban_h_3_akum_pjji;
                        $bb_h_3_akum_pjji = "";
                    }
                    
                    if($sal_awal_h_3_akum_pjji < 0){
                        $aaw_h_3_akum_pjji = "(";
                        $sal_awal_h_3_akum_pjjim = $sal_awal_h_3_akum_pjji*-1;
                        $baw_h_3_akum_pjji = ")";
                    }else{
                        $aaw_h_3_akum_pjji = "";
                        $sal_awal_h_3_akum_pjjim = $sal_awal_h_3_akum_pjji;
                        $baw_h_3_akum_pjji = "";
                    }
                    
                    if($sal_akhir_h_3_akum_pjji < 0){
                        $aak_h_3_akum_pjji = "(";
                        $sal_akhir_h_3_akum_pjjim = $sal_akhir_h_3_akum_pjji*-1;
                        $bak_h_3_akum_pjji = ")";
                    }else{
                        $aak_h_3_akum_pjji = "";
                        $sal_akhir_h_3_akum_pjjim = $sal_akhir_h_3_akum_pjji;
                        $bak_h_3_akum_pjji = "";
                    }
                    
                    if($selisih_h_3_akum_pjji < 0){
                        $as_h_3_akum_pjji = "(";
                        $selisih_h_3_akum_pjjim = $selisih_h_3_akum_pjji*-1;
                        $bs_h_3_akum_pjji = ")";
                    }else{
                        $as_h_3_akum_pjji = "";
                        $selisih_h_3_akum_pjjim = $selisih_h_3_akum_pjji;
                        $bs_h_3_akum_pjji = "";
                    }
                    
                    if($sal_ket_h_3_akum_pjji < 0){
                        $ask_h_3_akum_pjji = "(";
                        $sal_ket_h_3_akum_pjjim = $sal_ket_h_3_akum_pjji*-1;
                        $bsk_h_3_akum_pjji = ")";
                    }else{
                        $ask_h_3_akum_pjji = "";
                        $sal_ket_h_3_akum_pjjim = $sal_ket_h_3_akum_pjji;
                        $bsk_h_3_akum_pjji = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_pjji}}{{rupiah($beban_h_3_akum_pjjim)}}{{$bb_h_3_akum_pjji}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN JALAN, IRIGASI DAN JARINGAN AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_pjji}}{{rupiah($sal_akhir_h_3_akum_pjjim)}}{{$bak_h_3_akum_pjji}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN JALAN, IRIGASI DAN JARINGAN AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_pjji}}{{rupiah($sal_awal_h_3_akum_pjjim)}}{{$baw_h_3_akum_pjji}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_pjji != $total_h_3_akum_pjji)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_pjji}}{{rupiah($selisih_h_3_akum_pjjim)}}{{$bs_h_3_akum_pjji}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_pjji}}{{rupiah($selisih_h_3_akum_pjjim)}}{{$bs_h_3_akum_pjji}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pjji_ket as $h3_akum_pjji_ket)
                        @php
                            $kd_rek_h3_akum_pjji_ket = $h3_akum_pjji_ket->kd_rek;
                            $nm_rek_h3_akum_pjji_ket = $h3_akum_pjji_ket->nm_rek;
                            $ket_h3_akum_pjji_ket = $h3_akum_pjji_ket->ket;
                            $nilai_h3_akum_pjji_ket = $h3_akum_pjji_ket->nilai;

                            $kode_h3_akum_pjji_ket = substr($kd_rek_h3_akum_pjji_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pjji_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pjji_ket}} :</b> <br> {{$ket_h3_akum_pjji_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pjji_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pjji_ket as $h3_akum_pjji_ket)
                        @php
                            $kd_rek_h3_akum_pjji_ket = $h3_akum_pjji_ket->kd_rek;
                            $nm_rek_h3_akum_pjji_ket = $h3_akum_pjji_ket->nm_rek;
                            $ket_h3_akum_pjji_ket = $h3_akum_pjji_ket->ket;
                            $nilai_h3_akum_pjji_ket = $h3_akum_pjji_ket->nilai;

                            $kode_h3_akum_pjji_ket = substr($kd_rek_h3_akum_pjji_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pjji_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pjji_ket}} :</b> <br> {{$ket_h3_akum_pjji_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pjji_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_pjji < 0 ? '(' . rupiah($penyusutan_h_3_akum_pjji * -1) . ')' : rupiah($penyusutan_h_3_akum_pjji) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_pjji}}{{rupiah($total_h_3_akum_pjjim)}}{{$bt_h_3_akum_pjji}}</b></td>
                </tr>
            <!-- -->
            <!-- Akumulasi Penyusutan Aset Tetap Lainnya -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan (LO) harus sama dengan Akumulasi Penyusutan Aset Tetap Lainnya Akhir Tahun dikurangi Akumulasi Penyusutan Aset Tetap Lainnya Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Penyusutan Aset Tetap Lainnya Akhir Tahun - Akumulasi Penyusutan Aset Tetap Lainnya Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_patl = $h_3_akum_patl->beban;
                    $sal_awal_h_3_akum_patl = $h_3_akum_patl->sal_awal;
                    $sal_akhir_h_3_akum_patl = $h_3_akum_patl->sal_akhir;
                    $selisih_h_3_akum_patl = $h_3_akum_patl->selisih;
                    $sal_ket_h_3_akum_patl = $h_3_akum_patl->sal_ket;
                    $penyusutan_h_3_akum_patl = $h_3_akum_patl->penyusutan;
                    $total_h_3_akum_patl = $penyusutan_h_3_akum_patl + $sal_ket_h_3_akum_patl;
                    
                    if($total_h_3_akum_patl < 0){
                        $at_h_3_akum_patl = "(";
                        $total_h_3_akum_patlm = $total_h_3_akum_patl*-1;
                        $bt_h_3_akum_patl = ")";
                    }else{
                        $at_h_3_akum_patl = "";
                        $total_h_3_akum_patlm = $total_h_3_akum_patl;
                        $bt_h_3_akum_patl = "";
                    }
                    
                    if($beban_h_3_akum_patl < 0){
                        $ab_h_3_akum_patl = "(";
                        $beban_h_3_akum_patlm = $beban_h_3_akum_patl*-1;
                        $bb_h_3_akum_patl = ")";
                    }else{
                        $ab_h_3_akum_patl = "";
                        $beban_h_3_akum_patlm = $beban_h_3_akum_patl;
                        $bb_h_3_akum_patl = "";
                    }
                    
                    if($sal_awal_h_3_akum_patl < 0){
                        $aaw_h_3_akum_patl = "(";
                        $sal_awal_h_3_akum_patlm = $sal_awal_h_3_akum_patl*-1;
                        $baw_h_3_akum_patl = ")";
                    }else{
                        $aaw_h_3_akum_patl = "";
                        $sal_awal_h_3_akum_patlm = $sal_awal_h_3_akum_patl;
                        $baw_h_3_akum_patl = "";
                    }
                    
                    if($sal_akhir_h_3_akum_patl < 0){
                        $aak_h_3_akum_patl = "(";
                        $sal_akhir_h_3_akum_patlm = $sal_akhir_h_3_akum_patl*-1;
                        $bak_h_3_akum_patl = ")";
                    }else{
                        $aak_h_3_akum_patl = "";
                        $sal_akhir_h_3_akum_patlm = $sal_akhir_h_3_akum_patl;
                        $bak_h_3_akum_patl = "";
                    }
                    
                    if($selisih_h_3_akum_patl < 0){
                        $as_h_3_akum_patl = "(";
                        $selisih_h_3_akum_patlm = $selisih_h_3_akum_patl*-1;
                        $bs_h_3_akum_patl = ")";
                    }else{
                        $as_h_3_akum_patl = "";
                        $selisih_h_3_akum_patlm = $selisih_h_3_akum_patl;
                        $bs_h_3_akum_patl = "";
                    }
                    
                    if($sal_ket_h_3_akum_patl < 0){
                        $ask_h_3_akum_patl = "(";
                        $sal_ket_h_3_akum_patlm = $sal_ket_h_3_akum_patl*-1;
                        $bsk_h_3_akum_patl = ")";
                    }else{
                        $ask_h_3_akum_patl = "";
                        $sal_ket_h_3_akum_patlm = $sal_ket_h_3_akum_patl;
                        $bsk_h_3_akum_patl = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_patl}}{{rupiah($beban_h_3_akum_patlm)}}{{$bb_h_3_akum_patl}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN ASET TETAP LAINNYA AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_patl}}{{rupiah($sal_akhir_h_3_akum_patlm)}}{{$bak_h_3_akum_patl}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN ASET TETAP LAINNYA AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_patl}}{{rupiah($sal_awal_h_3_akum_patlm)}}{{$baw_h_3_akum_patl}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_patl != $total_h_3_akum_patl)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_patl}}{{rupiah($selisih_h_3_akum_patlm)}}{{$bs_h_3_akum_patl}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_patl}}{{rupiah($selisih_h_3_akum_patlm)}}{{$bs_h_3_akum_patl}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_patl_ket as $h3_akum_patl_ket)
                        @php
                            $kd_rek_h3_akum_patl_ket = $h3_akum_patl_ket->kd_rek;
                            $nm_rek_h3_akum_patl_ket = $h3_akum_patl_ket->nm_rek;
                            $ket_h3_akum_patl_ket = $h3_akum_patl_ket->ket;
                            $nilai_h3_akum_patl_ket = $h3_akum_patl_ket->nilai;

                            $kode_h3_akum_patl_ket = substr($kd_rek_h3_akum_patl_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_patl_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_patl_ket}} :</b> <br> {{$ket_h3_akum_patl_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_patl_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_patl_ket as $h3_akum_patl_ket)
                        @php
                            $kd_rek_h3_akum_patl_ket = $h3_akum_patl_ket->kd_rek;
                            $nm_rek_h3_akum_patl_ket = $h3_akum_patl_ket->nm_rek;
                            $ket_h3_akum_patl_ket = $h3_akum_patl_ket->ket;
                            $nilai_h3_akum_patl_ket = $h3_akum_patl_ket->nilai;

                            $kode_h3_akum_patl_ket = substr($kd_rek_h3_akum_patl_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_patl_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_patl_ket}} :</b> <br> {{$ket_h3_akum_patl_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_patl_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_patl < 0 ? '(' . rupiah($penyusutan_h_3_akum_patl * -1) . ')' : rupiah($penyusutan_h_3_akum_patl) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_patl}}{{rupiah($total_h_3_akum_patlm)}}{{$bt_h_3_akum_patl}}</b></td>
                </tr>
            <!-- -->
            <!-- Akumulasi Amortisasi Aset Tidak Berwujud -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan (LO) harus sama dengan Akumulasi Amortisasi Akhir Tahun dikurangi Akumulasi Amortisasi Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Amortisasi Akhir Tahun - Akumulasi Amortisasi Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_astb = $h_3_akum_astb->beban;
                    $sal_awal_h_3_akum_astb = $h_3_akum_astb->sal_awal;
                    $sal_akhir_h_3_akum_astb = $h_3_akum_astb->sal_akhir;
                    $selisih_h_3_akum_astb = $h_3_akum_astb->selisih;
                    $sal_ket_h_3_akum_astb = $h_3_akum_astb->sal_ket;
                    $penyusutan_h_3_akum_astb = $h_3_akum_astb->penyusutan;
                    $total_h_3_akum_astb = $penyusutan_h_3_akum_astb + $sal_ket_h_3_akum_astb;
                    
                    if($total_h_3_akum_astb < 0){
                        $at_h_3_akum_astb = "(";
                        $total_h_3_akum_astbm = $total_h_3_akum_astb*-1;
                        $bt_h_3_akum_astb = ")";
                    }else{
                        $at_h_3_akum_astb = "";
                        $total_h_3_akum_astbm = $total_h_3_akum_astb;
                        $bt_h_3_akum_astb = "";
                    }
                    
                    if($beban_h_3_akum_astb < 0){
                        $ab_h_3_akum_astb = "(";
                        $beban_h_3_akum_astbm = $beban_h_3_akum_astb*-1;
                        $bb_h_3_akum_astb = ")";
                    }else{
                        $ab_h_3_akum_astb = "";
                        $beban_h_3_akum_astbm = $beban_h_3_akum_astb;
                        $bb_h_3_akum_astb = "";
                    }
                    
                    if($sal_awal_h_3_akum_astb < 0){
                        $aaw_h_3_akum_astb = "(";
                        $sal_awal_h_3_akum_astbm = $sal_awal_h_3_akum_astb*-1;
                        $baw_h_3_akum_astb = ")";
                    }else{
                        $aaw_h_3_akum_astb = "";
                        $sal_awal_h_3_akum_astbm = $sal_awal_h_3_akum_astb;
                        $baw_h_3_akum_astb = "";
                    }
                    
                    if($sal_akhir_h_3_akum_astb < 0){
                        $aak_h_3_akum_astb = "(";
                        $sal_akhir_h_3_akum_astbm = $sal_akhir_h_3_akum_astb*-1;
                        $bak_h_3_akum_astb = ")";
                    }else{
                        $aak_h_3_akum_astb = "";
                        $sal_akhir_h_3_akum_astbm = $sal_akhir_h_3_akum_astb;
                        $bak_h_3_akum_astb = "";
                    }
                    
                    if($selisih_h_3_akum_astb < 0){
                        $as_h_3_akum_astb = "(";
                        $selisih_h_3_akum_astbm = $selisih_h_3_akum_astb*-1;
                        $bs_h_3_akum_astb = ")";
                    }else{
                        $as_h_3_akum_astb = "";
                        $selisih_h_3_akum_astbm = $selisih_h_3_akum_astb;
                        $bs_h_3_akum_astb = "";
                    }
                    
                    if($sal_ket_h_3_akum_astb < 0){
                        $ask_h_3_akum_astb = "(";
                        $sal_ket_h_3_akum_astbm = $sal_ket_h_3_akum_astb*-1;
                        $bsk_h_3_akum_astb = ")";
                    }else{
                        $ask_h_3_akum_astb = "";
                        $sal_ket_h_3_akum_astbm = $sal_ket_h_3_akum_astb;
                        $bsk_h_3_akum_astb = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_astb}}{{rupiah($beban_h_3_akum_astbm)}}{{$bb_h_3_akum_astb}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI AMORTISASI AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_astb}}{{rupiah($sal_akhir_h_3_akum_astbm)}}{{$bak_h_3_akum_astb}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI AMORTISASI AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_astb}}{{rupiah($sal_awal_h_3_akum_astbm)}}{{$baw_h_3_akum_astb}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_astb != $total_h_3_akum_astb)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_astb}}{{rupiah($selisih_h_3_akum_astbm)}}{{$bs_h_3_akum_astb}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_astb}}{{rupiah($selisih_h_3_akum_astbm)}}{{$bs_h_3_akum_astb}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_astb_ket as $h3_akum_astb_ket)
                        @php
                            $kd_rek_h3_akum_astb_ket = $h3_akum_astb_ket->kd_rek;
                            $nm_rek_h3_akum_astb_ket = $h3_akum_astb_ket->nm_rek;
                            $ket_h3_akum_astb_ket = $h3_akum_astb_ket->ket;
                            $nilai_h3_akum_astb_ket = $h3_akum_astb_ket->nilai;

                            $kode_h3_akum_astb_ket = substr($kd_rek_h3_akum_astb_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_astb_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_astb_ket}} :</b> <br> {{$ket_h3_akum_astb_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_astb_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_astb_ket as $h3_akum_astb_ket)
                        @php
                            $kd_rek_h3_akum_astb_ket = $h3_akum_astb_ket->kd_rek;
                            $nm_rek_h3_akum_astb_ket = $h3_akum_astb_ket->nm_rek;
                            $ket_h3_akum_astb_ket = $h3_akum_astb_ket->ket;
                            $nilai_h3_akum_astb_ket = $h3_akum_astb_ket->nilai;

                            $kode_h3_akum_astb_ket = substr($kd_rek_h3_akum_astb_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_astb_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_astb_ket}} :</b> <br> {{$ket_h3_akum_astb_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_astb_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_astb < 0 ? '(' . rupiah($penyusutan_h_3_akum_astb * -1) . ')' : rupiah($penyusutan_h_3_akum_astb) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_astb}}{{rupiah($total_h_3_akum_astbm)}}{{$bt_h_3_akum_astb}}</b></td>
                </tr>
            <!-- -->
            <!-- Akumulasi Penyusutan Aset Lainnya -->
                <tr>
                    <td align="justify" colspan="3">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="justify">Beban Penyusutan Aset Lainnya (LO) harus sama dengan Akumulasi Penyusutan Aset Lainnya Akhir Tahun dikurangi Akumulasi Penyusutan Aset Lainnya Awal Tahun</td>
                    <td align="center">&nbsp;</td>
                    <td align="justify">Beban Penyusutan(LO) = Akumulasi Penyusutan Aset Lainnya Akhir Tahun - Akumulasi Penyusutan Aset Lainnya Awal Tahun</td>
                </tr> 
                <tr>
                    <td align="left">RUMUS</td>
                    <td align="center">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                @php
                    $beban_h_3_akum_pal = $h_3_akum_pal->beban;
                    $sal_awal_h_3_akum_pal = $h_3_akum_pal->sal_awal;
                    $sal_akhir_h_3_akum_pal = $h_3_akum_pal->sal_akhir;
                    $selisih_h_3_akum_pal = $h_3_akum_pal->selisih;
                    $sal_ket_h_3_akum_pal = $h_3_akum_pal->sal_ket;
                    $penyusutan_h_3_akum_pal = $h_3_akum_pal->penyusutan;
                    $total_h_3_akum_pal = $penyusutan_h_3_akum_pal + $sal_ket_h_3_akum_pal;
                    
                    if($total_h_3_akum_pal < 0){
                        $at_h_3_akum_pal = "(";
                        $total_h_3_akum_palm = $total_h_3_akum_pal*-1;
                        $bt_h_3_akum_pal = ")";
                    }else{
                        $at_h_3_akum_pal = "";
                        $total_h_3_akum_palm = $total_h_3_akum_pal;
                        $bt_h_3_akum_pal = "";
                    }
                    
                    if($beban_h_3_akum_pal < 0){
                        $ab_h_3_akum_pal = "(";
                        $beban_h_3_akum_palm = $beban_h_3_akum_pal*-1;
                        $bb_h_3_akum_pal = ")";
                    }else{
                        $ab_h_3_akum_pal = "";
                        $beban_h_3_akum_palm = $beban_h_3_akum_pal;
                        $bb_h_3_akum_pal = "";
                    }
                    
                    if($sal_awal_h_3_akum_pal < 0){
                        $aaw_h_3_akum_pal = "(";
                        $sal_awal_h_3_akum_palm = $sal_awal_h_3_akum_pal*-1;
                        $baw_h_3_akum_pal = ")";
                    }else{
                        $aaw_h_3_akum_pal = "";
                        $sal_awal_h_3_akum_palm = $sal_awal_h_3_akum_pal;
                        $baw_h_3_akum_pal = "";
                    }
                    
                    if($sal_akhir_h_3_akum_pal < 0){
                        $aak_h_3_akum_pal = "(";
                        $sal_akhir_h_3_akum_palm = $sal_akhir_h_3_akum_pal*-1;
                        $bak_h_3_akum_pal = ")";
                    }else{
                        $aak_h_3_akum_pal = "";
                        $sal_akhir_h_3_akum_palm = $sal_akhir_h_3_akum_pal;
                        $bak_h_3_akum_pal = "";
                    }
                    
                    if($selisih_h_3_akum_pal < 0){
                        $as_h_3_akum_pal = "(";
                        $selisih_h_3_akum_palm = $selisih_h_3_akum_pal*-1;
                        $bs_h_3_akum_pal = ")";
                    }else{
                        $as_h_3_akum_pal = "";
                        $selisih_h_3_akum_palm = $selisih_h_3_akum_pal;
                        $bs_h_3_akum_pal = "";
                    }
                    
                    if($sal_ket_h_3_akum_pal < 0){
                        $ask_h_3_akum_pal = "(";
                        $sal_ket_h_3_akum_palm = $sal_ket_h_3_akum_pal*-1;
                        $bsk_h_3_akum_pal = ")";
                    }else{
                        $ask_h_3_akum_pal = "";
                        $sal_ket_h_3_akum_palm = $sal_ket_h_3_akum_pal;
                        $bsk_h_3_akum_pal = "";
                    }

                @endphp
                <tr>
                    <td align="left">BEBAN (LO)</td>
                    <td align="center">:</td>
                    <td align="right">{{$ab_h_3_akum_pal}}{{rupiah($beban_h_3_akum_palm)}}{{$bb_h_3_akum_pal}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN ASET LAINNYA AKHIR TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aak_h_3_akum_pal}}{{rupiah($sal_akhir_h_3_akum_palm)}}{{$bak_h_3_akum_pal}}</td>
                </tr>
                <tr>
                    <td align="left">AKUMULASI PENYUSUTAN ASET LAINNYA AWAL TAHUN</td>
                    <td align="center">:</td>
                    <td align="right">{{$aaw_h_3_akum_pal}}{{rupiah($sal_awal_h_3_akum_palm)}}{{$baw_h_3_akum_pal}}</td>
                </tr>
                <tr>
                    <td align="center"><b>Selisih</b></td>
                    <td align="center"><b>:</b></td>
                    @if($selisih_h_3_akum_pal != $total_h_3_akum_pal)
                        <td align="right" bgcolor="red" ><b>{{$as_h_3_akum_pal}}{{rupiah($selisih_h_3_akum_palm)}}{{$bs_h_3_akum_pal}}</b></td>
                    @else
                        <td align="right"><b>{{$as_h_3_akum_pal}}{{rupiah($selisih_h_3_akum_palm)}}{{$bs_h_3_akum_pal}}</b></td>
                    @endif
                </tr>
                <!-- Koreksi Bertambah -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Bertambah</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pal_ket as $h3_akum_pal_ket)
                        @php
                            $kd_rek_h3_akum_pal_ket = $h3_akum_pal_ket->kd_rek;
                            $nm_rek_h3_akum_pal_ket = $h3_akum_pal_ket->nm_rek;
                            $ket_h3_akum_pal_ket = $h3_akum_pal_ket->ket;
                            $nilai_h3_akum_pal_ket = $h3_akum_pal_ket->nilai;

                            $kode_h3_akum_pal_ket = substr($kd_rek_h3_akum_pal_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pal_ket=="1")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pal_ket}} :</b> <br> {{$ket_h3_akum_pal_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pal_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <!-- Koreksi Berkurang -->
                    <tr>
                        <td align="left" colspan="3"><b>Koreksi Berkurang</b></td>
                        
                    </tr>
                    @foreach($h_3_akum_pal_ket as $h3_akum_pal_ket)
                        @php
                            $kd_rek_h3_akum_pal_ket = $h3_akum_pal_ket->kd_rek;
                            $nm_rek_h3_akum_pal_ket = $h3_akum_pal_ket->nm_rek;
                            $ket_h3_akum_pal_ket = $h3_akum_pal_ket->ket;
                            $nilai_h3_akum_pal_ket = $h3_akum_pal_ket->nilai;

                            $kode_h3_akum_pal_ket = substr($kd_rek_h3_akum_pal_ket,1,1);
                        @endphp
                        @if($kode_h3_akum_pal_ket=="2")
                            <tr>
                                <td coslpan="2" align="left"><b>{{$nm_rek_h3_akum_pal_ket}} :</b> <br> {{$ket_h3_akum_pal_ket}}</td>
                                <td align="left"></td>
                                <td align="right">{{rupiah($nilai_h3_akum_pal_ket)}}</td>
                            </tr>
                            <tr></tr>
                        @else
                        @endif
                    @endforeach
                <!-- -->
                <tr>
                    <td align="left" colspan="3"><b>Penyusutan</b></td>
                </tr>
                <tr>
                    <td coslpan="2" align="left"><b>Penyusutan tahun {{$thn_ang}} </td>
                    <td align="left"></td>
                    <td align="right">{{$penyusutan_h_3_akum_pal < 0 ? '(' . rupiah($penyusutan_h_3_akum_pal * -1) . ')' : rupiah($penyusutan_h_3_akum_pal) }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td align="center"><b>TOTAL</b></td>
                    <td align="center"><b>:</b></td>
                    <td align="right"><b>{{$at_h_3_akum_pal}}{{rupiah($total_h_3_akum_palm)}}{{$bt_h_3_akum_pal}}</b></td>
                </tr>
            <!-- -->
        <!-- -->

</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calklamp1') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>