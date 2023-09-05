<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LPE</title>
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

<body">
{{-- <body> --}}

    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
          
            <TR>
                <td align="center"><strong>LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>Per {{$nm_bln}} {{$thn_ang}} DAN  {{$thn_ang1}} </strong></td>
            </TR>
            
                
    </TABLE>

    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>                            
                <td  bgcolor="#CCCCCC" width="40%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang}}</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang1}}</b></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>                        
            </tr>
        </tfoot>
                   
            <tr>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%" align="center">&nbsp;</td>                            
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            </tr>
    @php
        $no = 0;
        if ($kas_lalu < 0){
                        $sal01="("; $kas_lalu=$kas_lalu*-1; $sal02=")";
                        }else {
                        $sal01=""; $sal02="";
                        }

        if ($kas > 0){
                        $sal03="("; $kas=$kas*-1; $sal04=")";
                        }else {
                        $sal03=""; $sal04="";
                        }
                        
        if ($sub01 < 0){
                        $sal05="("; $sub01=$sub01*-1; $sal06=")";
                        }else {
                        $sal05=""; $sal06="";
                        }
        
        if ($sub02 < 0){
                        $sal07="("; $sub02=$sub02*-1; $sal08=")";
                        }else {
                        $sal07=""; $sal08="";
                        }

        if ($salnil < 0){
                        $sal09="(";$salnil=$salnil*-1  ; $sal10=")";
                        }else {
                        $sal09=""; $sal10="";
                        }

        if ($silpa < 0){
                        $sal11="("; $silpa=$silpa*-1; $sal12=")";
                        }else {
                        $sal11=""; $sal12="";
                        }

        if ($sub03 < 0){
                        $sal13="("; $sub03=$sub03*-1; $sal14=")";
                        }else {
                        $sal13=""; $sal14="";
                        }
        $sql = DB::select("SELECT * FROM map_lpsal_permen_77 ORDER BY seq");

    @endphp
    @foreach($sql as $row)
        @php
            $kd_rek   =$row->nor ;
            $parent   =$row->parent;
            $nama     =$row->uraian;
            $nilai_1_    =$row->thn_m1;
            if ($nilai_1_ < 0)
            {
                $tx = "(";
                $nilai_1a = $nilai_1_ * -1;
                $ty = ")";
            } else
            {
                $tx = "";
                $nilai_1a = $nilai_1_;
                $ty = "";
            }
        @endphp
        @if($kd_rek==1)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal01}}{{rupiah($kas_lalu)}}{{$sal02}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==2)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal09}}{{rupiah($salnil)}}{{$sal10}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==3)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($sub01)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==4)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($silpa)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==5)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($sub02)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==6)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($lain)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==7)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($koreksi)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @elseif($kd_rek==8)
            <tr>
                <td valign="top"  width="5%" align="center" style="font-size:14px;border-bottom:none;border-top:none">{{$kd_rek}}</td>
                <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">{{$nama}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$sal05}}{{rupiah($sub03)}}{{$sal06}}</td>
                <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$tx}}{{rupiah($nilai_1a)}}{{$ty}}</td>
            </tr>
        @endif
    @endforeach

    
    </TABLE>
    
</body>

</html>
