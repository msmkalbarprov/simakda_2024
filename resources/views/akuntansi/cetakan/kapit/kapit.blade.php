<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kapitalisasi</title>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>KAPITALISASI</strong></td>
            </tr>
    </table>
    
    <TABLE width="100%">
        <TR>
            <TD align="left" width="20%" >SKPD</TD>
            <TD align="left" width="100%" >: {{$kd_skpd}} - {{nama_skpd($kd_skpd)}}</TD>
        </TR>
    </TABLE>
    
    <TABLE style="border-collapse:collapse" width="100%" border="1" cellspacing="0" cellpadding="1" align=center>
        @if($cetakan==2)
            <thead>
                <TR>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Kode</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Uraian</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Kode Barang</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Nama Barang</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Klasifikasi Aset</TD>
                    <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" >Pegawai</TD>
                    <TD colspan="2" width="150"  bgcolor="#CCCCCC" align="center" >Barang dan Jasa</TD>
                    <TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" >Belanja Modal</TD>
                    <TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" >Belanja Modal dan Administrasi Pengadaan</TD>
                    <TD colspan="3" width="150" bgcolor="#CCCCCC" align="center" >Kapitalisasi</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Qty</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Sat.</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Ket</TD>
                </TR>
                <TR>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >Realisasi</TD>                       
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Penghitungan Kapitalisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                </TR>
                <TR>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >1</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >2</TD>                       
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >3</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >4</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >5</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >6</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >7</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >8</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >9</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >10</TD>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >11</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >12</TD>                      
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >13</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >14</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >15</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >16</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >17</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >18</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >19</TD>
                </TR>
            </thead>
        @else
            <thead>
                <TR>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Kode</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Uraian</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Nama Barang</TD>
                    <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >Klasifikasi Aset</TD>
                    <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" >Pegawai</TD>
                    <TD colspan="2" width="150"  bgcolor="#CCCCCC" align="center" >Barang dan Jasa</TD>
                    <TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" >Belanja Modal</TD>
                    <TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" >Belanja Modal dan Administrasi Pengadaan</TD>
                    <TD colspan="3" width="150" bgcolor="#CCCCCC" align="center" >Kapitalisasi</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Qty</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Sat.</TD>
                    <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Ket</TD>
                </TR>
                <TR>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >Realisasi</TD>                       
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Anggaran</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Penghitungan Kapitalisasi</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >Realisasi</TD>
                </TR>
                <TR>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >1</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >2</TD>                       
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >3</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >4</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >5</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >6</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >7</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >8</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >9</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >10</TD>
                    <TD width="90"  bgcolor="#CCCCCC" align="center" >11</TD>
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >12</TD>                      
                    <TD width="150"  bgcolor="#CCCCCC" align="center" >13</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >14</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >15</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >16</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >17</TD>
                    <TD width="150" bgcolor="#CCCCCC" align="center" >18</TD>
                </TR>
            </thead>
        @endif
    
        @php
            $total_kap = 0;
            $total_ang_peg = 0;
            $total_real_peg = 0;
            $total_ang_brg = 0;
            $total_real_brg = 0;
            $total_ang_mod = 0;
            $total_real_mod = 0;
            $total_ang_peng = 0;
            $total_real_peng = 0;
            $total_ang_kap = 0;
            $total_nil_kap = 0;
            $total_tot_kap = 0;
            $total_ang_x = 0;
            $total_real_x = 0;
            $total_ang_pengx = 0;
            $total_real_pengx = 0;
        @endphp
        @foreach($rincian as $row)
            @php
                $kode = $row->kode; 
                $uraian = $row->uraian;
                $kd_barang = $row->kd_barang;                   
                $nama_barang = $row->nama_barang;                   
                $klasifikasi = $row->klasifikasi;
                $ang_peg =$row->ang_peg;
                $real_peg=$row->real_peg;
                $ang_brg = $row->ang_brg;
                $real_brg = $row->real_brg;
                $ang_mod = $row->ang_mod;
                $real_mod = $row->real_mod;
                $ang_x  = $row->ang_x;
                $real_x  = $row->real_x;
                $ang_peng  = $row->ang_peng;
                $real_peng  = $row->real_peng;
                $ang_pengx  = $row->ang_pengx;
                $real_pengx  = $row->real_pengx;
                $ang_kap  = $row->ang_kap;
                $nil_kap  = $row->nil_kap;
                $tot_kap  = $row->tot_kap;
                $qty  = $row->qty;
                $sat  = $row->sat;
                $jen  = $row->jen;
                $ket  = $row->ket;

                if (substr($tot_kap, -3)!=',00') {
                    $color_tot_kapr="#ff0000";
                }else{
                    $color_tot_kapr='';
                }

                $nilket=@($tot_kap/$qty);
                
                $panjang = strlen($kode);
                $ang_peg1  = empty($ang_peg) || $ang_peg == 0 ? '' :number_format($ang_peg,"2",",",".");
                $real_peg1 = empty($real_peg) || $real_peg == 0 ? '' :number_format($real_peg,"2",",",".");
                $ang_brg1  = empty($ang_brg) || $ang_brg == 0 ? '' :number_format($ang_brg,"2",",",".");
                $real_brg1 = empty($real_brg) || $real_brg == 0 ? '' :number_format($real_brg,"2",",",".");
                $ang_mod1  = empty($ang_mod) || $ang_mod == 0 ? '' :number_format($ang_mod,"2",",",".");
                $real_mod1 = empty($real_mod) || $real_mod == 0 ? '' :number_format($real_mod,"2",",",".");
                $ang_kap1  =  $ang_kap == 0 ? '' :number_format($ang_kap,"2",",",".");
                $nil_kap1 = empty($nil_kap) || $nil_kap == 0 ? '' :number_format($nil_kap,"2",",",".");
                $tot_kap1  = $tot_kap == 0 ? '' :number_format($tot_kap,"2",",",".");
                $ang_peng1  = empty($ang_pengx) || $ang_pengx == 0 ? '' :number_format($ang_pengx,"2",",",".");
                $real_peng1  = empty($real_pengx) || $real_pengx == 0 ? '' :number_format($real_pengx,"2",",",".");
                $qty1  = empty($qty) || $qty == 0 ? '' :number_format($qty,"0",",",".");
                $nilket  = number_format($nilket,"2",",",".");
                
                if (substr($nilket, -3)!=',00'  || $nilket=='0' || $ket=='Extracomptable') {
                    $color_nilket="#ff0000";
                    if (substr($nilket, -3)!=',00'  || $nilket=='0'){
                        $ketkom = ", Masih ada nilai di belakang koma ";
                    }else{
                        $ketkom = "";
                    }

                }else{
                    $color_nilket="";
                    $ketkom = "";
                }


                if($panjang==18){
                $total_ang_peg=$total_ang_peg+$ang_peg;
                $total_real_peg=$total_real_peg+$real_peg;
                $total_ang_brg=$total_ang_brg+$ang_brg;
                $total_real_brg=$total_real_brg+$real_brg;
                $total_ang_mod=$total_ang_mod+$ang_mod;
                $total_real_mod=$total_real_mod+$real_mod;
                $total_ang_peng=$total_ang_x+$ang_x;
                $total_real_peng=$total_real_x+$real_x;
                $total_ang_kap=$total_ang_kap+$ang_kap;
                $total_nil_kap=$total_nil_kap+$nil_kap;
                $total_tot_kap=$total_tot_kap+$tot_kap;
                }
            @endphp
            @if($jen=="Y")
                <TR>
                    <TD width="90"  >{{$kode}}</TD>
                    <TD width="150" >{{$uraian}}</TD>
                    @if($cetakan==2)
                    <TD width="90"  >{{$kd_barang}}</TD>
                    @else
                    @endif
                    <TD width="150" >{{$nama_barang}}</TD>
                    <TD width="150" >{{$klasifikasi}}</TD>
                    <TD width="150" align="right" >{{$ang_peg1}}</TD>
                    <TD width="150" align="right" >{{$real_peg1}}</TD>
                    <TD width="150" align="right" >{{$ang_brg1}}</TD>
                    <TD width="150" align="right" >{{$real_brg1}}</TD>
                    <TD width="150" align="right" >{{$ang_mod1}}</TD>
                    <TD width="150" align="right" >{{$real_mod1}}</TD>
                    <TD width="90"  align="right" >{{$ang_peng1}}</TD>
                    <TD width="150" align="right" >{{$real_peng1}}</TD>                     
                    <TD width="150" align="right" >{{$ang_kap1}}</TD>
                    <TD width="150" align="right" >{{$nil_kap1}}</TD>
                    <TD width="150" align="right" >{{$tot_kap1}}</TD>
                    <TD width="150" align="center">{{$qty1}}</TD>
                    <TD width="150" >{{$sat}}</TD>
                    <TD width="150" bgcolor="{{$color_nilket}}" >{{$ket}} {{$ketkom}}</TD>
                </TR>
            @else
                <TR>
                    <TD width="90"  >{{$kode}}</TD>
                    <TD width="150" >{{$uraian}}</TD>
                    @if($cetakan==2)
                    <TD width="90"  >{{$kd_barang}}</TD>
                    @else
                    @endif
                    <TD width="150" >{{$nama_barang}}</TD>
                    <TD width="150" >{{$klasifikasi}}</TD>
                    <TD width="150" align="right" >{{$ang_peg1}}</TD>
                    <TD width="150" align="right" >{{$real_peg1}}</TD>
                    <TD width="150" align="right" >{{$ang_brg1}}</TD>
                    <TD width="150" align="right" >{{$real_brg1}}</TD>
                    <TD width="150" align="right" >{{$ang_mod1}}</TD>
                    <TD width="150" align="right" >{{$real_mod1}}</TD>
                    <TD width="90"  align="right" >{{$ang_peng1}}</TD>
                    <TD width="150" align="right" >{{$real_peng1}}</TD>                     
                    <TD width="150" align="right" ></TD>
                    <TD width="150" align="right" ></TD>
                    <TD width="150" align="right" ></TD>
                    <TD width="150" align="center"></TD>
                    <TD width="150" align="right" ></TD>
                    <TD width="150" ></TD>
                </TR>
            @endif
            
        @endforeach
        @php
            $total_ang_peg =$total->ang_peg;
            $total_real_peg=$total->real_peg;
            $total_ang_brg = $total->ang_brg;
            $total_real_brg = $total->real_brg;
            $total_ang_mod = $total->ang_mod;
            $total_real_mod = $total->real_mod;
            $total_ang_x  = $total->ang_x;
            $total_real_x  = $total->real_x;
            $total_ang_peng  = $total->ang_peng;
            $total_real_peng  = $total->real_peng;
            $total_ang_pengx  = $total->ang_pengx;
            $total_real_pengx  = $total->real_pengx;
            $total_ang_kap  = $total->ang_kap;
            $total_nil_kap  = $total->nil_kap;
            $total_tot_kap  = $total->tot_kap;
        

            if (substr($total_ang_peg, -3)!=".00") {
                $color_ang_peg="#ff0000";
            }else{
                $color_ang_peg="";
            }
            if (substr($total_real_peg, -3)!=".00") {
                $color_real_peg="#ff0000";
            }else{
                $color_real_peg="";
            }
            if (substr($total_ang_brg, -3)!=".00") {
                $color_ang_brg="#ff0000";
            }else{
                $color_ang_brg="";
            }
            if (substr($total_real_brg, -3)!=".00") {
                $color_real_brg="#ff0000";
            }else{
                $color_real_brg="";
            }
            if (substr($total_ang_mod, -3)!=".00") {
                $color_ang_mod="#ff0000";
            }else{
                $color_ang_mod="";
            }
            if (substr($total_real_mod, -3)!=".00") {
                $color_real_mod="#ff0000";
            }else{
                $color_real_mod="";
            }
            if (substr($total_real_mod, -3)!=".00") {
                $color_real_mod="#ff0000";
            }else{
                $color_real_mod="";
            }
            if (substr($total_ang_x, -3)!=".00") {
                $color_ang_x="#ff0000";
            }else{
                $color_ang_x="";
            }
            if (substr($total_real_x, -3)!=".00") {
                $color_real_x="#ff0000";
            }else{
                $color_real_x="";
            }
            if (substr($total_ang_peng, -3)!=".00") {
                $color_ang_peng="#ff0000";
            }else{
                $color_ang_peng="";
            }
            if (substr($total_real_peng, -3)!=".00") {
                $color_real_peng="#ff0000";
            }else{
                $color_real_peng="";
            }
            if (substr($total_ang_pengx, -3)!=".00") {
                $color_ang_pengx="#ff0000";
            }else{
                $color_ang_pengx="";
            }
            if (substr($total_real_pengx, -3)!=".00") {
                $color_real_pengx="#ff0000";
            }else{
                $color_real_pengx="";
            }
            if (substr($total_ang_kap, -3)!=".00") {
                $color_ang_kap="#ff0000";
            }else{
                $color_ang_kap="";
            }
            if (substr($total_nil_kap, -3)!=".00") {
                $color_nil_kap="#ff0000";
            }else{
                $color_nil_kap="";
            }
            if (substr($total_tot_kap, -3)!=".00") {
                $color_tot_kap="#ff0000";
            }else{
                $color_tot_kap="";
            }
        @endphp
        <TR>
            @if($cetakan==2)
            <TD width="90" colspan="5" align="center" >TOTAL</TD>
            @else
            <TD width="90" colspan="4" align="center" >TOTAL</TD>
            @endif
            <TD width="150" bgcolor="{{$color_ang_peg}}" align="right" >{{rupiah($total_ang_peg)}}</TD>
            <TD width="150" bgcolor="{{$color_real_peg}}" align="right" >{{rupiah($total_real_peg)}}</TD>
            <TD width="150" bgcolor="{{$color_ang_brg}}" align="right" >{{rupiah($total_ang_brg)}}</TD>
            <TD width="150" bgcolor="{{$color_real_brg}}" align="right" >{{rupiah($total_real_brg)}}</TD>
            <TD width="150" bgcolor="{{$color_ang_mod}}" align="right" >{{rupiah($total_ang_mod)}}</TD>
            <TD width="150" bgcolor="{{$color_real_mod}}" align="right" >{{rupiah($total_real_mod)}}</TD>
            <TD width="90"  bgcolor="{{$color_ang_pengx}}" align="right" >{{rupiah($total_ang_pengx)}}</TD>
            <TD width="150" bgcolor="{{$color_real_pengx}}" align="right" >{{rupiah($total_real_pengx)}}</TD>                        
            <TD width="150" bgcolor="{{$color_ang_kap}}" align="right" >{{rupiah($total_ang_kap)}}</TD>
            <TD width="150" bgcolor="{{$color_nil_kap}}" align="right" >{{rupiah($total_nil_kap)}}</TD>
            <TD width="150" bgcolor="{{$color_tot_kap}}" align="right" >{{rupiah($total_tot_kap)}}</TD>
            <TD width="150" align="center"></TD>
            <TD width="150" align="center"></TD>
            <TD width="150" ></TD>
        </TR>
        @php
            $total_realang_peg =$total_real->ang_peg;
            $total_realreal_peg=$total_real->real_peg;
            $total_realang_brg = $total_real->ang_brg;
            $total_realreal_brg = $total_real->real_brg;
            $total_realang_mod = $total_real->ang_mod;
            $total_realreal_mod = $total_real->real_mod;
            $total_realang_pengx  = $total_real->ang_pengx;
            $total_realreal_pengx  = $total_real->real_pengx;
            $total_realang_kap  = $total_real->ang_kap;
            $total_realnil_kap  = $total_real->nil_kap;
            $total_realtot_kap  = $total_real->tot_kap;    

            if (substr($total_realang_peg, -3)!=".00") {
                $color_realang_peg="#ff0000";
            }else{
                $color_realang_peg='';
            }
            if (substr($total_realreal_peg, -3)!=".00") {
                $color_realreal_peg="#ff0000";
            }else{
                $color_realreal_peg='';
            }
            if (substr($total_realang_brg, -3)!=".00") {
                $color_realang_brg="#ff0000";
            }else{
                $color_realang_brg='';
            }
            if (substr($total_realreal_brg, -3)!=".00") {
                $color_realreal_brg="#ff0000";
            }else{
                $color_realreal_brg='';
            }
            if (substr($total_realang_mod, -3)!=".00") {
                $color_realang_mod="#ff0000";
            }else{
                $color_realang_mod='';
            }
            if (substr($total_realreal_mod, -3)!=".00") {
                $color_realreal_mod="#ff0000";
            }else{
                $color_realreal_mod='';
            }
            if (substr($total_realreal_mod, -3)!=".00") {
                $color_realreal_mod="#ff0000";
            }else{
                $color_realreal_mod='';
            }
            
            if (substr($total_realang_pengx, -3)!=".00") {
                $color_realang_pengx="#ff0000";
            }else{
                $color_realang_pengx='';
            }
            if (substr($total_realreal_pengx, -3)!=".00") {
                $color_realreal_pengx="#ff0000";
            }else{
                $color_realreal_pengx='';
            }
            if (substr($total_realang_kap, -3)!=".00") {
                $color_realang_kap="#ff0000";
            }else{
                $color_realang_kap='';
            }
            if (substr($total_realnil_kap, -3)!=".00") {
                $color_realnil_kap="#ff0000";
            }else{
                $color_realnil_kap='';
            }
            if (substr($total_realtot_kap, -3)!=".00") {
                $color_realtot_kap="#ff0000";
            }else{
                $color_realtot_kap='';
            }
        @endphp
        <TR>
            @if($cetakan==2)
            <TD width="90" colspan="5" align="center" >TOTAL REALISASI</TD>
            @else
            <TD width="90" colspan="4" align="center" >TOTAL REALISASI</TD>
            @endif
            <TD width="150" bgcolor="{{$color_realang_peg}}" align="right" >{{rupiah($total_realang_peg)}}</TD>
            <TD width="150" bgcolor="{{$color_realreal_peg}}" align="right" >{{rupiah($total_realreal_peg)}}</TD>
            <TD width="150" bgcolor="{{$color_realang_brg}}" align="right" >{{rupiah($total_realang_brg)}}</TD>
            <TD width="150" bgcolor="{{$color_realreal_brg}}" align="right" >{{rupiah($total_realreal_brg)}}</TD>
            <TD width="150" bgcolor="{{$color_realang_mod}}" align="right" >{{rupiah($total_realang_mod)}}</TD>
            <TD width="150" bgcolor="{{$color_realreal_mod}}" align="right" >{{rupiah($total_realreal_mod)}}</TD>
            <TD width="90"  bgcolor="{{$color_realang_pengx}}" align="right" >{{rupiah($total_realang_pengx)}}</TD>
            <TD width="150" bgcolor="{{$color_realreal_pengx}}" align="right" >{{rupiah($total_realreal_pengx)}}</TD>                        
            <TD width="150" bgcolor="{{$color_realang_kap}}" align="right" >{{rupiah($total_realang_kap)}}</TD>
            <TD width="150" bgcolor="{{$color_realnil_kap}}" align="right" >{{rupiah($total_realnil_kap)}}</TD>
            <TD width="150" bgcolor="{{$color_realtot_kap}}" align="right" >{{rupiah($total_realtot_kap)}}</TD>
            <TD width="150" align="center"></TD>
            <TD width="150" align="center"></TD>
            <TD width="150" ></TD>
        </TR>
    </TABLE>

    
    
</body>

</html>
