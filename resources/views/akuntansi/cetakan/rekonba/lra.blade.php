<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekon BA LRA</title>
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
    
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="4" align="center" style="border-right:hidden; border-bottom: hidden;">
                <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="font-size:16px; border-bottom: hidden;"><strong>BADAN KEUANGAN DAN ASET DAERAH</strong></tr>
        <tr>
            <td colspan="3" align="center" style="font-size:12px; border-bottom: hidden;"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Email: bkad@kalbarprov.go.id Website: bkad.kalbarprov.go.id</strong></tr>
        <tr>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PONTIANAK</strong></td></tr>
        <tr>
            <td colspan="4" align="right">Kode Pos: 78124 &nbsp; &nbsp;</td>
        </tr>
    </table>
    <hr  valign="top" color="black" size="3px" width="100%">

 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td rowspan="2" align="right" width="10%" height="50">&nbsp;</td>
            <td colspan="3" align="center" style="font-size:14px"><b>Laporan Realisasi Anggaran Tahun Anggaran $thn_ang</b></td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="font-size:14px"><b>Periode {{tgl_format_oyoy($periode1)}} - {{tgl_format_oyoy($periode2)}}</b></td>
        </tr>
        <tr>
            <td colspan="5" align="justify" style="font-size:12px">
                <br>
                SKPD : {{$kd_skpd}} - {{nama_skpd($kd_skpd)}}
                <br>
                <br>
            </td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td rowspan="2" bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>URAIAN</b></td>
                <td colspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>REALISASI {{$thn_ang}}</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>KETERANGAN</b></td> 
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" width="15%" align="center"><b>Akuntansi</b></td>
                <td bgcolor="#CCCCCC" width="15%" align="center"><b>SKPD</b></td>     
            </tr>
        </thead>
        <tr>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%"     align="center">&nbsp;</td>                            
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="40%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%">&nbsp;</td>
            <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%">&nbsp;</td>
        </tr>
        @php
        $no = 0;
        $ang_surplus = $sus->ang_surplus;
        $nil_surplus = $sus->nil_surplus;
        $sisa_surplus = $ang_surplus - $nil_surplus;
        if (($ang_surplus == 0) || ($ang_surplus == '')) {
            $persen_surplus = 0;
        } else {
            $persen_surplus = $nil_surplus / $ang_surplus * 100;
        }
        if ($ang_surplus < 0) {
            $ang_surplus = $ang_surplus * -1;
            $a = '(';
            $b = ')';
        }else {
            $ang_surplus = $ang_surplus;
            $a = '';
            $b = '';
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
        if ($sisa_surplus < 0) {
            $sisa_surplus = $sisa_surplus * -1;
            $i = '(';
            $j = ')';
        }else {
            $sisa_surplus = $sisa_surplus;
            $i = '';
            $j = '';
        }
    @endphp
    @foreach($sql as $row4)
        @php
            $normal      = $row4->cetak;    
            $bold      = $row4->bold;
            $parent      = $row4->parent;       
            $nama      = $row4->uraian;   
            $real_lalu = number_format($row4->lalu,"2",",",".");
            $n         = $row4->kode_1;
            $n         = ($n=="-"?"'-'":$n);
            $n2         = $row4->kode_2;
            $n2        = ($n2=="-"?"'-'":$n2);
            $n3         = $row4->kode_3;
            $n3        = ($n3=="-"?"'-'":$n3);

            $nilainya = collect(DB::select("SELECT isnull(SUM(case when LEFT(kd_rek6,2) in ('51','52','62') and $parent=1 then b.anggaran*-1 else b.anggaran end),0) as anggaran, ISNULL(SUM($normal),0) as nilai FROM data_realisasi_n_pemda_unit_tgl('$periode1','$periode2','$jns_ang','$kd_skpd') b WHERE (left(b.kd_rek6,4) in ($n) or left(b.kd_rek6,6) in ($n2) or left(b.kd_rek6,8) in ($n3))"))->first();


            $nilai=$nilainya->nilai;
            $anggaran=$nilainya->anggaran;
  
            
            $selisih = $nilai - $anggaran; 
            if ($selisih < 0){
                $sela="("; 
                $selisih1=$selisih*-1; 
                $selb=")";
            }else {
                $sela=""; 
                $selisih1=$selisih; 
                $selb="";
            }

            if( $anggaran=='' || $anggaran==0){
                $persen = 0;
            }else{
                $persen = ($nilai/$anggaran)*100;
            }

            

            $no=$no+1;
        @endphp

            @if ($bold == 0)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @elseif ($bold == 1)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">{{$nama}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @elseif ($bold == 2)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @elseif ($bold == 3)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{rupiah($nilai)}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @elseif ($bold == 4)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{rupiah($nilai)}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @elseif ($bold == 5)
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{$c}}{{rupiah($nil_surplus)}}{{$d}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>

            @else
                <tr>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="5%" align="center">{{$no}}</td>                                     
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right">{{rupiah($nilai)}}</td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"></td>
                    <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right"></td>
                </tr>
            @endif
        

    @endforeach
    </table>
    {{-- isi --}}
    
    <div style="padding-top:20px">
        <table style="border-collapse:collapse;font-family: Arial; font-size:14px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">Paraf .......................
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                         &nbsp; &nbsp; &nbsp;
                    </td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    </table>
    </div>

    {{-- tanda tangan --}}
    
</body>

</html>
