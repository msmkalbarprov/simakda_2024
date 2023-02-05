<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA SEMESTER 77</title>
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

<body onload="window.print()">
{{-- <body> --}}
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td rowspan="5" align="left" width="7%">
                    <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
                </td>
                <td align="left" style="font-size:14px" width="93%">&nbsp;</td>
                <td rowspan="5" align="left" width="7%">
                   &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center" style="font-size:14px" width="93%"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
            </tr>
            <tr>
                <td align="center" style="font-size:14px" ><strong>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</strong></td>
            </tr>
            <tr>
                <td align="center" style="font-size:14px" ><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER {{ tahun_anggaran() }} DAN {{ tahun_anggaran()-1 }}</strong></td>
            </tr>
            <tr>
                <td align="left" style="font-size:14px" ><strong>&nbsp;</strong></td>
            </tr>
            </table>
    <hr>
 
    {{-- isi --}}
    <table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
        <thead>
            <tr>
                <td width='7%' align='center' bgcolor='#CCCCCC' ><b>KD REK</b></td>
                <td width='32%' align='center' bgcolor='#CCCCCC' ><b>URAIAN</b></td>
                <td width='15%' align='center' bgcolor='#CCCCCC' ><b>JUMLAH ANGGARAN</b></td>
                <td width='15%' align='center' bgcolor='#CCCCCC' ><b>REALISASI <br>{{$pilih}}<br> {{$judul}}</b></td>
                <td width='15%' align='center' bgcolor='#CCCCCC' ><b>SISA ANGGARAN</b></td>
                <td width='15%' align='center' bgcolor='#CCCCCC' ><b>PROGNOSIS</b></td>
                <td width='10%' align='center' bgcolor='#CCCCCC' ><b>%</b></td>
            </tr>
            <tr>
               <td align='center' bgcolor='#CCCCCC' >1</td> 
               <td align='center' bgcolor='#CCCCCC' >2</td> 
               <td align='center' bgcolor='#CCCCCC' >3</td> 
               <td align='center' bgcolor='#CCCCCC' >4</td> 
               <td align='center' bgcolor='#CCCCCC' >5</td> 
               <td align='center' bgcolor='#CCCCCC' >6</td> 
               <td align='center' bgcolor='#CCCCCC' >7</td> 
            </tr>
            </thead>
                @php
                    
					$total_terima   = 0;
					$total_keluar   = 0;
					$nomor          = 0;
                @endphp
                    @foreach ($rincian as $row)
                        @php
                            $kd_rek         = $row->kd_rek;
                            $nm_rek         = $row->nama;
                            $nil_ang        = $row->anggaran;
                            $group_id       = $row->group_id;
                            $bold           = $row->is_bold;
                            $show_kd_rek    = $row->is_show_kd_rek;
                            $right_align    = $row->is_right_align;
                
                            $realisasi   = $row->realisasi;
                            $sisa           = $nil_ang - $realisasi;
                            $persen         = empty($nil_ang) || $nil_ang == 0 ? 0 : ($realisasi / $nil_ang) * 100;
                            $sisa1          = $sisa < 0 ? $sisa * -1 : $sisa;
                            $a              = $sisa < 0 ? '(' : '';
                            $b              = $sisa < 0 ? ')' : '';
                            $leng           = strlen($kd_rek);
                        @endphp
                        @if ($show_kd_rek==1)
                            @php
                                $kd_rek=$kd_rek;
                            @endphp
                        @else
                            @php
                                $kd_rek="";
                            @endphp
                        @endif
            
                        @if ($group_id == 0)
                            @if ($row->kd_rek== 5 || $row->kd_rek== 6)
                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                @if ($right_align==1)
                                    <tr>
                                        <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                        <td align="right"  valign="top"><b>{{$nm_rek}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                    </tr>        
                                @else
                                    <tr>
                                        <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                        <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                    </tr>        
                                @endif
                                
                            @else
                                <tr>
                                    <td align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                    <td align="left"  valign="top"  ><b>{{$nm_rek}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                </tr>    
                            @endif
                            
                    @elseif ($group_id == 1)
                            @if ($row->kd_rek== 5 || $row->kd_rek== 6 || $group_id==0)
                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                @if ($right_align==1)
                                    <tr>
                                        <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                        <td align="right"  valign="top"><b>{{$nm_rek}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                    </tr>        
                                @else
                                    <tr>
                                        <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                        <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                        <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                    </tr>        
                                @endif
                                
                            @else
                                <tr>
                                    <td align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                    <td align="left"  valign="top"  ><b>{{$nm_rek}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                </tr>    
                            @endif
                            
                    @elseif ($group_id == 2)
                            @if ($row->kd_rek== 61 || $group_id==0)
                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                @if ($right_align==1)
                                <tr>
                                    <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                    <td align="right"  valign="top"><b>{{$nm_rek}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                </tr>
                                @else
                                <tr>
                                    <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                    <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                </tr>    
                                @endif
                                        
                            @else
                                <tr>
                                    <td align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                    <td align="left"  valign="top"  style="padding-left:10px"><b>{{$nm_rek}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                    <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                                </tr>    
                            @endif
                    @elseif ($group_id == 3)
                            <tr>
                                <td align="left" valign="top"><b>{{$kd_rek}}</b></td> 
                                <td align="left"  valign="top" style="padding-left:60px"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                            </tr> 
                                
                    @elseif ($group_id == 4)
                        
                            <tr>
                                <td align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td align="left"  valign="top" style="padding-left:20px"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                            </tr>    
                    @elseif ($group_id == 5)
                            <tr>
                                <td align="left" valign="top"><b>{{dotrek($kd_rek)}}</b></td> 
                                <td align="left"  valign="top" style="padding-left:80px"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($nil_ang, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($realisasi, "2", ",", ".")}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{number_format($persen, "2", ",", ".")}}</b></td> 
                            </tr>
                        
                    @elseif ($group_id == 6)
                            <tr>
                                        <td align="left" valign="top">{{dotrek($kd_rek)}}</td> 
                                        <td align="left"  valign="top" style="padding-left:30px">{{$nm_rek}}</td> 
                                        <td align="right" valign="top">{{number_format($nil_ang, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{number_format($realisasi, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{number_format($persen, "2", ",", ".")}}</td> 
                            </tr>
                    @elseif ($group_id == 8)
                            <tr>
                                        <td align="left" valign="top" >{{dotrek($kd_rek)}}</td> 
                                        <td align="left"  valign="top" style="padding-left:40px">{{$nm_rek}}</td> 
                                        <td align="right" valign="top">{{number_format($nil_ang, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{number_format($realisasi, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{number_format($persen, "2", ",", ".")}}</td> 
                            </tr>
                    @else
                            <tr>
                                        <td align="left" valign="top">{{dotrek($kd_rek)}}</td> 
                                        <td align="left"  valign="top"  style="padding-left:50px">{{$nm_rek}}</b></td> 
                                        <td align="right" valign="top">{{number_format($nil_ang, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{number_format($realisasi, "2", ",", ".")}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{$a}} {{number_format($sisa1, "2", ",", ".")}} {{$b}}</td> 
                                        <td align="right" valign="top">{{number_format($persen, "2", ",", ".")}}</td> 
                                        </tr>
                    @endif                     
                @endforeach
                

    </table>
    {{-- isi --}}
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
            <tr>
                <td style="margin: 2px 0px;text-align: center;">
                    Disetujui oleh
                </td>
                <td style="margin: 2px 0px;text-align: center;">
                    {{ $daerah->daerah }},
                        {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;">
                </td>
                <td style="padding-bottom: 50px;text-align: center;">
                    {{ ucwords(strtolower($tandatangan->jabatan)) }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;"><b></b></td>
                <td style="text-align: center;"><b><u>{{ $tandatangan->nama }}</u></b></td>
            </tr>
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: center;">{{ $tandatangan->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: center;">NIP. {{ $tandatangan->nip }}</td>
            </tr>

        </table>
    </div>
</body>

</html>
