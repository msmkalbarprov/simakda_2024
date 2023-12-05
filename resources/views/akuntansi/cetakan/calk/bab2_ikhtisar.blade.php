<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB I IKHTISAR PENCAPAIAN KINERJA KEUANGAN</title>
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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD align="center" ><b>BAB II IKHTISAR PENCAPAIAN KINERJA KEUANGAN</TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="4" align=center>
        <tr>
            <td align="left" width="10px"><b>2.1.</b></td>
            <td align="left"><b>Ikhtisar Realisasi Pencapaian Target Kinerja Keuangan.</b></td>
        </tr>
    </TABLE><br>
    <table style="border-collapse:collapse;{{$spasi}}" width="100%" align="center" border="0">
        <tr>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" colspan="2">KODE</td>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;">BIDANG URUSAN PEMERINTAH DAERAH</td>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;"> <strong>ANGGARAN {{$nm_jns_ang}}</strong></td>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;">REALISASI</td>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;">BERLEBIH/ (BERKURANG) </br>(Rp.)</td>
            <td align="center" style="font-weight:bold;border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;">%</td>
        </tr>
        @foreach($isinya as $rowsc1)
            @php
                $nomor      = $rowsc1->nomor;
                $kode       = $rowsc1->kode;
                $kode2      = $rowsc1->kode2;
                $bidang     = $rowsc1->bidang;
                $anggaran   = $rowsc1->angg_ubah;
                $realisasi  = $rowsc1->real;
                $selisih    = $rowsc1->selisih;
                $persen    = $rowsc1->persen;
                if($rowsc1->nomor!=9){
                    $bold = 'font-weight:bold;';
                }else{
                    $bold='';
                }
            @endphp
            @if($nomor==1 || $nomor==2)
                <tr>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top" colspan="2">{{$kode}}</td>                         
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top">{{$bidang}}</td>                         
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top"></td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top"></td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top"></td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top"></td>                            
                </tr>
            @elseif($nomor==3 || $nomor==4 || $nomor==5 || $nomor==6 ||$nomor==7 || $nomor==8)
                <tr>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top" colspan="2"></td>                         
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top">{{$bidang}}</td>                         
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($anggaran)}}</td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($realisasi)}}</td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($selisih)}}</td>
                     <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($persen)}} </td>                            
                </tr>
            @elseif($nomor==9)
                <tr>
                     <td style="  border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top" colspan="2">{{$kode2}}</td>                         
                     <td style="  border-left:solid 1px black;border-bottom:solid 1px black;" align="left" valign="top">{{$bidang}}</td>                         
                     <td style="  border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($anggaran)}}</td>
                     <td style="  border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($realisasi)}}</td>
                     <td style="  border-left:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($selisih)}}</td>
                     <td style="  border-left:solid 1px black;border-right:solid 1px black;border-bottom:solid 1px black;" align="right" valign="top">{{rupiah($persen)}} </td>                            
                </tr>
            @else
            @endif
        @endforeach
    </table><br>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="4" align=center>
        <tr>
            <td align="left" width="10px"><b>2.1.</b></td>
            <td align="left"><b>Hambatan dan Kendala Yang Ada Dalam Pencapaian Target.</b></td>  
        </tr>
        <tr>
            <td align="left" width="10px"><b></b></td>
            <td align="left">Adapun Hambatan dan Kendala yang ada pada program-program kegiatan {{$nm_skpd}} Provinsi Kalimantan Barat dalam pencapaian target kurang 
            dari 75%, dapat dijelaskan sebagai berikut :</br></br></td>      
        </tr>
    </table>
    <table width="100%" style="border-collapse:collapse;{{$spasi}}"  align="center" border="0" cellspacing="0" cellpadding="1">
        <thead>
            <tr>
                <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" align="center" colspan="1">No</td>                         
                <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" align="center">PROGRAM KEGIATAN</td>
                <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" align="center">ANGGARAN {{$nm_jns_ang}}</td>
                <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" align="center">REALISASI</td>
                <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;" align="center">%</td>
                <td style=" font-weight:bold; border-right:solid 1px black;border-top:solid 1px black;border-left:solid 1px black;" align="center">HAMBATAN DAN KENDALA DALAM PENCAPAIAN TARGET KURANG DARI 75%</td>                           
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="border-top:solid 1px black;" colspan="6"></td>
            </tr>
        </tfoot>
        @php
            $no=1;
        @endphp
        @foreach($isinya as $rowsc2)
            @php 
                $nomor      = $rowsc2->nomor;
                $kode       = $rowsc2->kode;
                $kode2      = $rowsc2->kode2;
                $bidang     = $rowsc2->bidang;
                $anggaran   = $rowsc2->angg_ubah;
                $realisasi  = $rowsc2->real;
                $selisih    = $rowsc2->selisih;
                $persen     = $rowsc2->persen;
                $hambatan   = $rowsc2->hambatan;
            @endphp
            @if($nomor == 10)
                @if($persen<75)
                    @if($skpdunit=="skpd")
                        <tr>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="center" valign="top" >{{$no}}</td>                         
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="left" valign="top">{{$bidang}}</td>                         
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($anggaran)}}</td>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($realisasi)}}</td>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($persen)}}</td>
                            @if($jenis==1)
                            <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;" align="left" valign="top" bgcolor="#FFFF00">{{$hambatan}}</td>  
                            @else
                            <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;" align="left" valign="top" >{{$hambatan}}</td>
                            @endif                          
                        </tr>
                        @php $no++ @endphp
                    @else
                        <tr>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="center" valign="top" >{{$no}}</td>                         
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="left" valign="top">{{$bidang}}</td>                         
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($anggaran)}}</td>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($realisasi)}}</td>
                            <td style=" font-weight:bold; border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;" align="right" valign="top">{{rupiah($persen)}}</td>
                            @if($jenis==1)
                            <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;" align="left" valign="top" bgcolor="#FFFF00">{{$hambatan}}</td>  
                            @else
                            <td style=" font-weight:bold; border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;" align="left" valign="top" >{{$hambatan}}</td>
                            @endif                             
                        </tr>
                        @php $no++ @endphp
                    @endif
                @endif
            @else
            @endif
        @endforeach
    </table><br>
    <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd}}','{{$jns_ang}}','{{$bulan}}')">Edit</button>
    <button type="button" value="Refresh" onClick="window.location.reload()">Reload</button>
</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan) {
        let url             = new URL("{{ route('calk.calkbab2_hambatan') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        window.open(url.toString(), "_blank");
    }

</script>