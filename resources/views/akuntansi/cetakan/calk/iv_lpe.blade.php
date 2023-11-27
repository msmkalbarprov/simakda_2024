<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - III.LPE</title>
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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="1" cellpadding="1" align=center>
        <TR>
            <TD align="center" ><b>IV. LAPORAN PERUBAHAN EKUITAS (LPE)</b></TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="4" align=center> 
        <tr>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
        </tr>
        <tr>
            <td align="center"><strong>{{$nm_skpd}}</strong></td>                         
        </tr>                   
        <tr>
            <td align="center"><strong>LAPORAN PERUBAHAN EKUITAS</strong></td>
        </tr>                    
        <tr>
            <td align="center"><strong>UNTUK PERIODE YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER {{$thn_ang}} DAN {{$thn_ang_1}}</strong></td>
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
        </tr>
        <tr>
            <td align="right">(dalam rupiah)</td>
        </tr>
    </TABLE>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>                         
                <td  bgcolor="#CCCCCC" width="40%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang}}</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang_1}}</b></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>                        
            </tr>
        </tfoot>
                   
            <tr>                          
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            </tr>
    @php
        $no = 0;
        $ekuitas_awal = $ekuitas_awal;
        $ekuitas_awal_lalu = $ekuitas_awal_lalu;

        if ($ekuitas_awal < 0){
            $aeku = "("; 
            $ekuitas_awal1 = $ekuitas_awal * -1; 
            $beku = ")";
        }else{
            $aeku = ""; 
            $ekuitas_awal1 = $ekuitas_awal; 
            $beku = "";
        }
        if ($ekuitas_awal_lalu < 0){
            $aekul = "("; 
            $ekuitas_awal_lalu1 = $ekuitas_awal_lalu * -1; 
            $bekul = ")";
        }else{
            $aekul = ""; 
            $ekuitas_awal_lalu1 = $ekuitas_awal_lalu; 
            $bekul = "";
        }

        $surdef = $surdef;
        $surdef_lalu = $surdef_lalu;
        if ($surdef < 0){
            $asd = "("; 
            $surdef1 = $surdef * -1; 
            $bsd = ")";
        }else{
            $asd = ""; 
            $surdef1 = $surdef; 
            $bsd = "";
        }
        if ($surdef_lalu < 0){
            $asdl = "("; 
            $surdef_lalu1 = $surdef_lalu * -1; 
            $bsdl = ")";
        }else{
            $asdl = ""; 
            $surdef_lalu1 = $surdef_lalu; 
            $bsdl = "";
        }

        $koreksi = $koreksi;
        $koreksi_lalu = $koreksi_lalu;
        if ($koreksi < 0){
            $ak = "("; 
            $koreksi1 = $koreksi * -1; 
            $bk = ")";
        }else{
            $ak = ""; 
            $koreksi1 = $koreksi; 
            $bk = "";
        }
        if ($koreksi_lalu < 0){
            $akl = "("; 
            $koreksi_lalu1 = $koreksi_lalu * -1; 
            $bkl = ")";
        }else{
            $akl = ""; 
            $koreksi_lalu1 = $koreksi_lalu; 
            $bkl = "";
        }

        $selisih = $selisih;
        $selisih_lalu = $selisih_lalu;
        if ($selisih < 0){
            $as = "("; 
            $selisih1 = $selisih * -1; 
            $bs = ")";
        }else{
            $as = ""; 
            $selisih1 = $selisih; 
            $bs = "";
        }
        if ($selisih_lalu < 0){
            $asl = "("; 
            $selisih_lalu1 = $selisih_lalu * -1; 
            $bsl = ")";
        }else{
            $asl = ""; 
            $selisih_lalu1 = $selisih_lalu; 
            $bsl = "";
        }

        $lain = $lain;
        $lain_lalu = $lain_lalu;
        if ($lain < 0){
            $al = "("; 
            $lain1 = $lain * -1; 
            $bl = ")";
        }else{
            $al = ""; 
            $lain1 = $lain; 
            $bl = "";
        }
        if ($lain_lalu < 0){
            $all = "("; 
            $lain_lalu1 = $lain_lalu * -1; 
            $bll = ")";
        }else{
            $all = ""; 
            $lain_lalu1 = $lain_lalu; 
            $bll = "";
        }

        $ekuitas_akhir = $ekuitas_awal+$surdef+$koreksi+$selisih+$lain;
        if ($ekuitas_akhir < 0){
            $aeka = "("; 
            $ekuitas_akhir1 = $ekuitas_akhir * -1; 
            $beka = ")";
        }else{
            $aeka = ""; 
            $ekuitas_akhir1 = $ekuitas_akhir; 
            $beka = "";
        }
        
    @endphp
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">EKUITAS AWAL</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none"> {{$aeku}}{{rupiah($ekuitas_awal1)}}{{$beku}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$aekul}}{{rupiah($ekuitas_awal_lalu1)}}{{$bekul}}</td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">SURPLUS/DEFISIT-LO</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none"> {{$asd}}{{rupiah($surdef1)}}{{$bsd}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$asdl}}{{rupiah($surdef_lalu1)}}{{$bsdl}}</td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">DAMPAK KUMULATIF PERUBAHAN KEBIJAKAN/KESALAHAN MENDASAR :</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none"></td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none"></td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KOREKSI NILAI PERSEDIAAN</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$ak}}{{rupiah($koreksi1)}}{{$bk}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$akl}}{{rupiah($koreksi_lalu1)}}{{$bkl}}</td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELISIH REVALUASI ASET TETAP</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$as}}{{rupiah($selisih1)}}{{$bs}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$asl}}{{rupiah($selisih_lalu1)}}{{$bsl}}</td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">LAIN LAIN</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$al}}{{rupiah($lain1)}}{{$bl}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$all}}{{rupiah($lain_lalu1)}}{{$bll}}</td>
        </tr>
        <tr>
            <td valign="top"  width="65%"  align="left" style="font-size:14px;border-bottom:none;border-top:none">EKUITAS AKHIR</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$aeka}}{{rupiah($ekuitas_akhir1)}}{{$beka}}</td>
            <td valign="top"  width="15%" align="right" style="font-size:14px;border-bottom:none;border-top:none">{{$aeku}}{{rupiah($ekuitas_awal1)}}{{$beku}}</td>
        </tr>

    
    </TABLE>
    {{-- tanda tangan --}}
        <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="1" align=center> 
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$tempat_tanggal}}</TD>
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->jabatan}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center"><b><u>{{$ttd_nih->nama}}</u></b></TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->pangkat}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->nip}}</TD> 
            </TR>                 
        </TABLE>
    
</body>

</html>
