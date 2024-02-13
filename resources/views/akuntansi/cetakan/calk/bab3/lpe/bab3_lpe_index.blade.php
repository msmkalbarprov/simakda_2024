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
                <td align="left" width="10%"><strong>3.1.4.</strong></td>                         
                <td align="left"><strong>Penjelasan atas Laporan Perubahan Ekuitas</strong></td>                         
            </tr>
        </table><br>
    @else
        <table style="border-collapse:collapse;{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="left" width="5%"><strong>3.1</strong></td>                         
                <td align="left" colspan="2"><strong>Rincian dan Penjelasan masing-masing pos-pos laporan Keuangan SKPD.</strong></td>                         
            </tr>
            <tr>
                <td align="left" width="5%"><strong>&nbsp;</strong></td>                         
                <td align="left" width="10%"><strong>3.1.4.</strong></td>                         
                <td align="left"><strong>Penjelasan atas Laporan Perubahan Ekuitas</strong></td>                         
            </tr>
        </table>
    @endif
    <table style="{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <!-- 1 ekuitas awal -->   
            @php
                $reff_eku_awal         = $ekuitas_awal->reff;
                $uraian_eku_awal         = $ekuitas_awal->uraian;
                $sal_eku_awal    = $ekuitas_awal->sal;
                if ($sal_eku_awal < 0){
                    $a_eku_awal="("; 
                    $real_sal_eku_awal=$sal_eku_awal*-1; 
                    $b_eku_awal=")";
                }else {
                    $a_eku_awal=""; 
                    $real_sal_eku_awal=$sal_eku_awal; 
                    $b_eku_awal="";
                }
            @endphp
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="left"><strong></strong></td>                         
                <td align="left"><strong>{{$reff_eku_awal}}</strong></td>
                <td align="left"><strong>{{$uraian_eku_awal}}</strong></td>
                <td align="right"><strong>{{$a_eku_awal}}{{rupiah($real_sal_eku_awal)}}{{$b_eku_awal}}</strong></td>
            </tr>
        <!---->

        <!-- 2 Surplus/ Defisit - LO -->   
            @foreach($surplus_defisit as $surdef)    
                @php
                    $reff_surdef         = $surdef->reff;
                    $uraian_surdef         = $surdef->uraian;
                    $sal_surdef    = $surdef->sal;
                    if ($sal_surdef < 0){
                        $a_surdef="("; 
                        $real_sal_surdef=$sal_surdef*-1; 
                        $b_surdef=")";
                    }else {
                        $a_surdef=""; 
                        $real_sal_surdef=$sal_surdef; 
                        $b_surdef="";
                    }
                @endphp
                @if($reff_surdef=="2")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong></strong></td>                         
                        <td align="left"><strong>{{$reff_surdef}}</strong></td>
                        <td align="left"><strong>{{$uraian_surdef}}</strong></td>
                        <td align="right"><strong>{{$a_surdef}}{{rupiah($real_sal_surdef)}}{{$b_surdef}}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="left"></td>                         
                        <td align="left"></td>
                        <td align="left">{{$reff_surdef}} {{$uraian_surdef}}</td>
                        <td align="right">{{$a_surdef}}{{rupiah($real_sal_surdef)}}{{$b_surdef}}</td>
                    </tr>
                @endif
            @endforeach
        <!---->
        
        <!-- 3  Dampak Kumulatif Perubahan Kebijakan/ Kesalahan mendasar :-->
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="left"><strong></strong></td>                         
                <td align="left"><strong>3</strong></td>
                <td align="left" colspan="4"><strong>Dampak Kumulatif Perubahan Kebijakan/ Kesalahan mendasar :</strong></td>
            </tr>
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="left"><strong></strong></td>                         
                <td align="left"></td>
                <td align="justify" colspan="4">Koreksi yang langsung menambah/mengurangi ekuitas, yang antara lain berasal dari dampak kumulatif yang disebabkan oleh perubahan kebijakan akuntansi dan koreksi kesalahan mendasar, seperti:</td>
            </tr>
            @if($jenis==1)
                <tr>
                     <td align="left"><strong>&nbsp;</strong></td>
                     <td align="left"><strong>&nbsp;</strong></td>
                     <td align="justify" colspan="7">
                        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','3143')">Edit</button>
                    </td>                         
                </tr>
            @else
            @endif
            @foreach($kode_3 as $kd_3)    
                @php
                    $reff_3         = $kd_3->reff;
                    $uraian_3         = $kd_3->uraian;
                    $ket_3         = $kd_3->ket;
                    $det_3         = $kd_3->det;
                    $sal_3    = $kd_3->sal;
                    if ($sal_3 < 0){
                        $a_3="("; 
                        $real_sal_3=$sal_3*-1; 
                        $b_3=")";
                    }else {
                        $a_3=""; 
                        $real_sal_3=$sal_3; 
                        $b_3="";
                    }
                @endphp
                <tr>
                    <td align="left">&nbsp;</td>                         
                    <td align="left"></td>                         
                    <td align="left"></td>
                    <td align="left"><strong>{{$reff_3}} {{$uraian_3}}</strong></td>
                    <td align="right"><strong>{{$a_3}}{{rupiah($real_sal_3)}}{{$b_3}}</strong></td>
                </tr>
                <tr>
                    <td align="left">&nbsp;</td>                         
                    <td align="left"></td>                         
                    <td align="left"></td>
                    <td align="justify" colspan="3">{{$ket_3}}</td>                             
                </tr>
                @php
                    $det_ket_3 = DB::select("SELECT x.kd_rek, x.nm_rek, y.ket FROM (
                        SELECT kd_rek, nm_rek FROM ket_neraca_calk WHERE kd_rek='$det_3') x 
                        LEFT JOIN (
                        SELECT kd_rek, ket FROM isi_neraca_calk where $skpd_clause) y
                        on x.kd_rek=y.kd_rek");

                @endphp
                @foreach($det_ket_3 as $dk_3)
                    @php
                        $ket_det_3 = $dk_3->ket;
                    @endphp
                    @if($jenis==1)
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="3" bgcolor="yellow">{{$ket_det_3}}</td>                         
                        </tr>
                    @else
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="3">{{$ket_det_3}}</td>                         
                        </tr>
                    @endif
                @endforeach
            @endforeach

            @php
                $az_3 = "a";
            @endphp
            @foreach($rincian_3 as $rc_3)
                @php
                    $reff_rc_3         = $rc_3->reff;
                    $uraian_rc_3         = $rc_3->uraian;
                    $sal_rc_3    = $rc_3->sal;
                    if ($sal_rc_3 < 0){
                        $a_rc_2="("; 
                        $real_sal_rc_3=$sal_rc_3*-1; 
                        $b_rc_3=")";
                    }else {
                        $a_rc_2=""; 
                        $real_sal_rc_3=$sal_rc_3; 
                        $b_rc_3="";
                    }
                @endphp
                <tr>
                    <td align="left">&nbsp;</td>                         
                    <td align="left"></td>                         
                    <td align="left"></td>
                    <td align="left">{{$az_3++}}. {{$uraian_rc_3}}</td>
                    <td align="center">{{$a_rc_2}}{{rupiah($real_sal_rc_3)}}{{$b_rc_3}}</td>
                </tr>
            @endforeach
        <!-- -->

        <!-- 4 ekuitas akhir -->   
            @php
                $reff_eku_akhir         = $ekuitas_akhir->reff;
                $uraian_eku_akhir         = $ekuitas_akhir->uraian;
                $sal_eku_akhir    = $ekuitas_akhir->sal;
                if ($sal_eku_akhir < 0){
                    $a_eku_akhir="("; 
                    $real_sal_eku_akhir=$sal_eku_akhir*-1; 
                    $b_eku_akhir=")";
                }else {
                    $a_eku_akhir=""; 
                    $real_sal_eku_akhir=$sal_eku_akhir; 
                    $b_eku_akhir="";
                }
            @endphp
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="left"><strong></strong></td>                         
                <td align="left"><strong>{{$reff_eku_akhir}}</strong></td>
                <td align="left"><strong>{{$uraian_eku_akhir}}</strong></td>
                <td align="right"><strong>{{$a_eku_akhir}}{{rupiah($real_sal_eku_akhir)}}{{$b_eku_akhir}}</strong></td>
            </tr>
        <!---->
    </table>

</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_lpe') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>