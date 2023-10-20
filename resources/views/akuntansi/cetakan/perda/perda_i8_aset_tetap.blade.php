<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.1</title>
    <style>
        body {
          font-family: Arial;
        }

        .bordered {
          width: 100%;
          border-collapse: collapse;
        }

        .bordered th,
        .bordered td {
          border: 1px solid black;
          padding: 4px;
        }

        .bordered td:nth-child(n+5) {
          text-align: right;
        }

        .bordered th {
          /* background-color: #cccccc; */
        }

        .bordered {
          font-size: 11px;
        }

        .bold {
          font-weight: bold;
        }

        table {
          width: 100%;
        }

        
    </style>
</head>

<body >
{{-- <body> --}}
    <table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.8 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_perda_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_perda_tentang) }}</TD>
        </TR>
    </table>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>DAFTAR REALISASI PENAMBAHAN DAN PENGURANGAN ASET TETAP DAERAH</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
                <td width="40%" align="center" bgcolor="#CCCCCC" style="font-size:12px"><b>Uraian</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px"><b>Saldo Awal</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px"><b>Penambahan</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px"><b>Pengurangan</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px"><b>Saldo Akhir</b></td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
           </tr>
        </thead>
        @php
            $jum_real_sedia = 0;
            $jum_real_tetap = 0;
            $jum_real_lain = 0;
            $total = 0;
            $no = 0;
        @endphp
        @foreach($map as $row)
            @php
                $no = $no + 1;
                $nama = $row->uraian;
                $seq = $row->seq;
                $bold = $row->bold;
                $normal = $row->normal;
                $kode_1 = $row->rek;
                if ($kode_1 == '') {
                    $kode_1 = 'XXX';
                }

                $nilai_lalu = collect(DB::select("SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                where year(tgl_voucher)<=$thn_ang_1 and (kd_rek6 like '$kode_1%' )"))->first();

                $debet_lalu = $nilai_lalu->debet;
                $kredit_lalu = $nilai_lalu->kredit;

                if ($normal == 1) {
                    $sblm = $debet_lalu - $kredit_lalu;
                } else {
                    $sblm = $kredit_lalu - $debet_lalu;
                }

                $nilai = collect(DB::select("SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                where year(tgl_voucher)=$thn_ang and (kd_rek6 like '$kode_1%' )"))->first();

                $nilai_tambah = $nilai->debet;
                $nilai_kurang = $nilai->kredit;

                $saldo_akhir = $sblm + $nilai_tambah - $nilai_kurang;
            @endphp
            @if($bold==4)
                <tr>
                    <td align="left"  valign="top" style="font-size:12px">&nbsp;</td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                </tr>
            @elseif($bold==1)
                <tr>
                    <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>{{$nama}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                    <td align="right" valign="top" style="font-size:12px"> </td> 
                </tr>
            @elseif($bold==2)
                <tr>
                    <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $nama}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{ rupiah($sblm)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{ rupiah($nilai_tambah)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{ rupiah($nilai_kurang)}}</td> 
                    <td align="right" valign="top" style="font-size:12px">{{ rupiah($saldo_akhir)}}</td> 
                </tr>
            @elseif($bold==3)
                <tr>
                   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>{{$nama}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px">{{rupiah($sblm)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai_tambah)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai_kurang)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($saldo_akhir)}}</td> 
                </tr>
            @endif


        @endforeach
   

    </table>
    {{-- isi --}}
    
    
</body>

</html>
