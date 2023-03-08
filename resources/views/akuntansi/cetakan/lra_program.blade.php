<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA Rinci</title>
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
                <td align="left" style="font-size:18px" width="93%">&nbsp;</td>
                <td rowspan="5" align="left" width="7%">
                   &nbsp;
                </td>
            </tr>
            <tr>
                <td align="center" style="font-size:18px" width="93%"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
            </tr>
            <tr>
                <td align="center" style="font-size:18px" ><strong>LAPORAN REALISASI {{strtoupper(bulan($judul))}} ANGGARAN PENDAPATAN DAN BELANJA DAERAH</strong></td>
            </tr>
            <tr>
                <td align="center" style="font-size:18px" ><strong>PROGNOSIS {{12-$judul}} ({{strtoupper(bulan(12-$judul))}}) BULAN BERIKUTNYA<br> TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>
            <tr>
                <td align="left" style="font-size:18px" ><strong>&nbsp;</strong></td>
            </tr>
        </table>

    <hr>
 
    {{-- isi --}}
    <table style='border-collapse:collapse;;font-family: Open Sans; font-size:14px' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
        <thead>
            <tr>
                <td style="font-size:14px;font-family:Open Sans" width='7%' align='center' bgcolor='#CCCCCC' ><b>KD REK</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='32%' align='center' bgcolor='#CCCCCC' ><b>URAIAN</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='15%' align='center' bgcolor='#CCCCCC' ><b>JUMLAH ANGGARAN</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='15%' align='center' bgcolor='#CCCCCC' ><b>REALISASI <br>{{$pilih}}<br> {{strtoupper(bulan($judul))}}</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='15%' align='center' bgcolor='#CCCCCC' ><b>SISA ANGGARAN</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='15%' align='center' bgcolor='#CCCCCC' ><b>PROGNOSIS</b></td>
                <td style="font-size:14px;font-family:Open Sans" width='10%' align='center' bgcolor='#CCCCCC' ><b>%</b></td>
            </tr>
            <tr>
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >1</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >2</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >3</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >4</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >5</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >6</td> 
               <td style="font-size:14px;font-family:Open Sans" align='center' bgcolor='#CCCCCC' >7</td> 
            </tr>
            </thead>
                @php
                    
					$total_terima   = 0;
					$total_keluar   = 0;
					$nomor          = 0;
                @endphp
                    @foreach ($rincian as $row)
                        @php
                            $kd_sub_kegiatan         = $row->kd_sub_kegiatan;
                            $kd_sub_kegiatan_potong = substr($row->kd_sub_kegiatan, 0, 15);
                            $kd_rek         = $row->kd_rek;
                            $nm_rek         = $row->nm_rek;
                            $anggaran        = $row->anggaran;
                            $realisasi       = $row->sd_bulan_ini;
                            $sisa           = $row->sisa;
                
                            if (($anggaran == 0) || ($anggaran == '')) {
                                $persen = 0;
                            } else {
                                $persen = $realisasi / $anggaran * 100;
                            }

                        @endphp
                        @if(strlen($kd_rek)<=2)
                            <tr>
                               <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}} </b></td> 
                               <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}} </b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($sisa)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($sisa)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                            </tr>
                        @elseif(strlen($kd_sub_kegiatan)>=16)
                            <tr>
                               <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}} </td> 
                               <td align="left"  valign="top">{{$nm_rek}}</td> 
                               <td align="right" valign="top">{{rupiah($anggaran)}} </td> 
                               <td align="right" valign="top">{{rupiah($realisasi)}}</td> 
                               <td align="right" valign="top">{{rupiah($sisa)}}</td> 
                               <td align="right" valign="top">{{rupiah($sisa)}}</td> 
                               <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif($kd_sub_kegiatan=='' && $nm_rek!='')
                            <tr>
                               <td align="left" valign="top"><b></b></td> 
                               <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($anggaran)}} </b></td> 
                               <td align="right" valign="top"><b>{{rupiah($realisasi)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($sisa)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($sisa)}}</b></td> 
                               <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                            </tr>
                        @elseif ($nm_rek=='')
                            <tr>
                               <td align="left" valign="top"><b></b></td> 
                               <td align="left"  valign="top"><b></b></td> 
                               <td align="right" valign="top"><b></b></td> 
                               <td align="right" valign="top"><b></b></td> 
                               <td align="right" valign="top"><b></b></td> 
                               <td align="right" valign="top"><b></b></td> 
                               <td align="right" valign="top"><b></b></td> 
                            </tr>
                        @else
                            <tr>
                               <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}} </td> 
                               <td align="left"  valign="top">{{$nm_rek}}</td> 
                               <td align="right" valign="top">{{rupiah($anggaran)}} </td> 
                               <td align="right" valign="top">{{rupiah($realisasi)}}</td> 
                               <td align="right" valign="top">{{rupiah($sisa)}}</td> 
                               <td align="right" valign="top">{{rupiah($sisa)}}</td> 
                               <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @endif
                    


                        
                        
                @endforeach
                

    </table>
    {{-- isi --}}
    @if ($jenis_ttd !=0)
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
