<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA SAP SEMESTER</title>
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
    <TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD  width="60%" valign="top" align="right" ></TD>
            <TD width="40%"  align="left" ></TD>
        </TR>
    </TABLE>
    <br/>
    <TABLE style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="3" align="center" style="border-right:hidden">
                <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td>
        </tr>
        @if($periodebulan=="bulan")
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden"><b>LAPORAN REALISASI {{$judul}} APBD DAN PROGNOSIS<BR> {{$bulan2}} BULAN BERIKUTNYA </b>
        </tr>
        @else
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden"><b>LAPORAN REALISASI {{tgl_format_oyoy($tanggal1)}} S.D {{tgl_format_oyoy($tanggal2)}} APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b>
        </tr>
        @endif
        <tr>
            <td align="center" style="border-left:hidden;border-top:hidden" ><b>TAHUN ANGGARAN {{$tahun_anggaran}}</b>
        </tr>
    </TABLE>
    @if($skpdunit=="skpd")
        <TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td width="15%" align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Urusan Pemerintahan </td>
                <td width="85%" align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,1)}} -  {{nama_urusan(left($kd_skpd,1))}} </td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Bidang Pemerintahan </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,4)}} - {{nama_bidang(left($kd_skpd,4))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,17)}} - {{nama_org(left($kd_skpd,17))}}</td>
            </tr>
        </TABLE>
    @elseif($skpdunit=="unit")
        <TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td width="15%" align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Urusan Pemerintahan </td>
                <td width="85%" align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,1)}} -  {{nama_urusan(left($kd_skpd,1))}} </td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Bidang Pemerintahan </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,4)}} - {{nama_bidang(left($kd_skpd,4))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,17)}} - {{nama_org(left($kd_skpd,17))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Sub Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,22)}} - {{nama_skpd(left($kd_skpd,22))}}</td>
            </tr>
        </TABLE>
    @else
    @endif
    

    <hr>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                <td width="32%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH ANGGARAN</b></td>
                @if($periodebulan=="bulan")
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI <br>S/D<br> {{$judul}}</b></td>
                @elseif($periodebulan=="periode")
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI <br>S/D<br> {{tgl_format_oyoy($tanggal1)}} S.D {{tgl_format_oyoy($tanggal2)}}</b></td>
                @else
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                @endif
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>SISA ANGGARAN</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>PROGNOSIS</b></td>
                <td width="7%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" >1</td> 
               <td align="center" bgcolor="#CCCCCC" >2</td> 
               <td align="center" bgcolor="#CCCCCC" >3</td> 
               <td align="center" bgcolor="#CCCCCC" >4</td> 
               <td align="center" bgcolor="#CCCCCC" >5</td> 
               <td align="center" bgcolor="#CCCCCC" >6</td> 
               <td align="center" bgcolor="#CCCCCC" >7</td> 
            </tr>
        </thead>
                @php
                    $ang_surplus=$sus->ang_surplus;
                    $nil_surplus=$sus->nil_surplus;
                    $selisih_surplus=$ang_surplus-$nil_surplus;
                    if($selisih_surplus<0){
                        $x_surplus="(";
                        $sel_surplus=$selisih_surplus*-1;
                        $y_surplus=")";
                    }else{
                        $x_surplus="";
                        $sel_surplus=$selisih_surplus;
                        $y_surplus="";
                    }
                        if (($ang_surplus == 0) || ($ang_surplus == '')) {
                        $persen_surplus= 0;
                    } else {
                        $persen_surplus= $nil_surplus / $ang_surplus * 100;
                    }

                    $ang_neto=$sus->ang_neto;
                    $nil_neto=$sus->nil_neto;
                    $selisih_neto=$ang_neto-$nil_neto;
                    if($selisih_neto<0){
                        $x_neto="(";
                        $sel_neto=$selisih_neto*-1;
                        $y_neto=")";
                    }else{
                        $x_neto="";
                        $sel_neto=$selisih_neto;
                        $y_neto="";
                    }
                        if (($ang_neto == 0) || ($ang_neto == '')) {
                        $persen_neto= 0;
                    } else {
                        $persen_neto= $nil_neto / $ang_neto * 100;
                    }
                @endphp
                    @foreach ($rincian as $row)
                        @php
                            $seq = $row->seq;
                            $bold = $row->bold;
                            $kode = $row->kode;
                            $nama = $row->nama;
                            $kode1 = $row->kode1;
                            $kode2 = $row->kode2;
                            $kode3 = $row->kode3;
                            $kode4 = $row->kode4;
                            $kode5 = $row->kode5;
                            $jenis = $row->jenis;

                            if ($kode1 == '') {
                                $kode1 = "'X'";
                            }
                            if ($kode2 == '') {
                                $kode2 = "'XX'";
                            }
                            if ($kode3 == '') {
                                $kode3 = "'XXXX'";
                            }
                            if ($kode4 == '') {
                                $kode4 = "'XXXXXX'";
                            }
                            if ($kode5 == '') {
                                $kode5 = "'XXXXXXXX'";
                            }
                            
                            $nilai = collect(DB::select("
                                    SELECT sum(anggaran) as anggaran, sum(realisasi) as realisasi 
                                    FROM
                                    (
                                    SELECT 
                                    sum(a.nilai) as anggaran, 0 realisasi
                                    FROM trdrka a where (LEFT(a.kd_rek6,1) in ($kode1) or LEFT(a.kd_rek6,2) in ($kode2) or LEFT(a.kd_rek6,4) in ($kode3) or LEFT(a.kd_rek6,6) in ($kode4) or LEFT(a.kd_rek6,8) in ($kode5))
                                    and jns_ang='$jns_ang' $skpd_clause_ang
                                    union all
                                    SELECT 0 anggaran ,SUM($jenis) realisasi
                                    FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
                                    WHERE MONTH(tgl_voucher)<=$bulan and YEAR(tgl_voucher)=$tahun_anggaran and (LEFT(a.kd_rek6,1) in ($kode1) or LEFT(a.kd_rek6,2) in ($kode2) or LEFT(a.kd_rek6,4) in ($kode3) or LEFT(a.kd_rek6,6) in ($kode4) or LEFT(a.kd_rek6,8) in ($kode5)) $skpd_clause
                                    )a
                                    "))->first();
                            $anggaran = $nilai->anggaran;
                            $realisasi = $nilai->realisasi;
                            $selisih=$anggaran-$realisasi;
                            if($selisih<0){
                                $x="(";
                                $sel=$selisih*-1;
                                $y=")";
                            }else{
                                $x="";
                                $sel=$selisih;
                                $y="";
                            }

                            if (($anggaran == 0) || ($anggaran == '')) {
                                $persen = 0;
                            } else {
                                $persen = $realisasi / $anggaran * 100;
                            }


                        @endphp
                        @if($bold=="")
                            <tr>
                               <td colspan="7" align="left" valign="top">&nbsp;</td> 
                            </tr>
                        @elseif($bold=="1")
                            <tr>
                               <td align="left" valign="top"><b>{{$kode}}</b></td> 
                               <td align="left"  valign="top"><b>{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td>
                            </tr>
                        @elseif($bold=="2")
                            <tr>
                               <td align="left" valign="top"><b>{{$kode}}</b></td> 
                               <td align="left"  valign="top"><b>&nbsp;&nbsp;{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td>
                            </tr>
                        @elseif($bold=="3")
                            <tr>
                               <td align="left" valign="top">{{$kode}}</td> 
                               <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td> 
                               <td align="right" valign="top">{{rupiah($anggaran)}}</td> 
                               <td align="right" valign="top">{{rupiah($realisasi)}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{rupiah($persen)}}</td>
                            </tr>
                        @elseif($bold=="33")
                            <tr>
                               <td align="left" valign="top"><b>{{$kode}}</b></td> 
                               <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td>
                            </tr>
                        @elseif($bold=="4")
                            <tr>
                               <td align="left" valign="top">{{$kode}}</td> 
                               <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td> 
                               <td align="right" valign="top">{{rupiah($anggaran)}}</td> 
                               <td align="right" valign="top">{{rupiah($realisasi)}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{rupiah($persen)}}</td>
                            </tr>
                        @elseif($bold=="44")
                            <tr>
                               <td align="left" valign="top"><b>{{$kode}}</b></td> 
                               <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{$x}}{{rupiah($sel)}}{{$y}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td>
                            </tr>
                        @elseif($bold=="5")
                            <tr>
                               <td align="left" valign="top">{{$kode}}</td> 
                               <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td> 
                               <td align="right" valign="top">{{rupiah($anggaran)}}</td> 
                               <td align="right" valign="top">{{rupiah($realisasi)}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{$x}}{{rupiah($sel)}}{{$y}}</td> 
                               <td align="right" valign="top">{{rupiah($persen)}}</td>
                            </tr>
                        @elseif($bold=="888")
                            <tr>
                               <td align="left" valign="top"><b></b></td> 
                               <td align="right"  valign="top"><b>{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($ang_surplus)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($nil_surplus)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x_surplus}}{{rupiah($sel_surplus)}}{{$y_surplus}}</b></td> 
                               <td align="right" valign="top"><b>{{$x_surplus}}{{rupiah($sel_surplus)}}{{$y_surplus}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen_surplus)}}</b></td>
                            </tr>
                        @elseif($bold=="999")
                            <tr>
                               <td align="left" valign="top"><b></b></td> 
                               <td align="right"  valign="top"><b>{{$nama}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($ang_neto)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($nil_neto)}}</b></td> 
                               <td align="right" valign="top"><b>{{$x_neto}}{{rupiah($sel_neto)}}{{$y_neto}}</b></td> 
                               <td align="right" valign="top"><b>{{$x_neto}}{{rupiah($sel_neto)}}{{$y_neto}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen_neto)}}</b></td>
                            </tr>
                        @else
                        @endif
                @endforeach
                
    </table>
    {{-- isi --}}
    @if ($tandatangan !="")
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
                    {{ ucwords(strtolower($tandatangan->jabatan)) }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b><u>{{ $tandatangan->nama }}</u></b></td>
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
    @else
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
                    {{ ucwords(strtolower($tandatangan->jabatan)) }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b><u>{{ $tandatangan->nama }}</u></b></td>
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
    @endif
    {{-- tanda tangan --}}
    
</body>

</html>
