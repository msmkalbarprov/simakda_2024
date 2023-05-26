<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jurnal Umum</title>
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
    <TABLE width="100%">
        <TR>
            <TD align="center"><B>BADAN KEUANGAN DAN ASET DAERAH </B></TD>
        </TR>
        <TR>
            <TD align="center"><B>JURNAL UMUM</B></TD>
        </TR>
        <TR>
            <TD align="center"><B>{{ tgl_format_oyoy($dcetak) }} s/d {{ tgl_format_oyoy($dcetak2) }}</B></TD>
        </TR>
    </TABLE>
    
    <TABLE style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0"
        cellpadding="4">
        <THEAD>
            <tr>
                <td align="center" rowspan="2">Tanggal</td>
                <td align="center" rowspan="2">Nomor<br>Bukti</td>
                <td colspan="6" align="center" rowspan="2">Kode<br>Rekening</td>
                <td align="center" rowspan="2">Uraian</td>
                <td align="center" rowspan="2">ref</td>
                <td align="center" colspan="2">Jumlah Rp</td>
            </tr>
            <tr>
                <td align="center">Debit</td>
                <td align="center">Kredit</td>
            </tr>
            <tr>
                <td align="center" width="10%">1</td>
                <td align="center" width="10%">2</td>
                <td colspan="6" align="center" width="15%">3</td>
                <td align="center" width="42%">4</td>
                <td align="center" width="3%"></td>
                <td align="center" width="10%">5</td>
                <td align="center" width="10%">6</td>
            </tr>
        </THEAD>
        @php
            $tot = $trh->tot;
            $cnovoc = '';
            $lcno = 0;
        @endphp
        
        @foreach ($query as $res)
            @php
                $kd_rek6 = $res->kd_rek6;
                $nm_rek6 = $res->nm_rek6;
                $tgl_voucher = $res->tgl_voucher;
                $no_voucher = $res->no_voucher;
                $debet = $res->debet;
                $kredit = $res->kredit;
                $lcno = $lcno + 1;
                
            @endphp
            @if($lcno == $tot)
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,0, 1) }}</td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,1, 1) }}</td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,2, 2) }}</td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,4, 2) }}</td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,6, 2) }}</td>
                    <td style="border-bottom:none;">{{substr($kd_rek6,8, 4) }}</td>
                    <td style="border-bottom:none;">{{$nm_rek6 }}</td>
                    <td style="border-bottom:none;"></td>
                @if($debet == 0)
                    <td style="border-bottom:none;"></td>
                    <td style="border-bottom:none;" align="right">{{rupiah($kredit)}}</td>
                @else
                    <td style="border-bottom:none;" align="right">{{rupiah($debet)}}</td>
                    <td style="border-bottom:none;"></td>
                @endif
                </tr>
            @else
                @if($cnovoc == $no_voucher)
                    <tr>
                        <td style="border-bottom:none;border-top:none;">&nbsp;</td>
                        <td style="border-bottom:none;border-top:none;">&nbsp;</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,0, 1)}}</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,1, 1)}}</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,2, 2)}}</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,4, 2)}}</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,6, 2)}}</td>
                        <td style="border-bottom:none;">{{ substr($kd_rek6,8, 4)}}</td>
                        <td style="border-bottom:none;">{{ $nm_rek6}}</td>
                        <td style="border-bottom:none;"></td>
                    @if($debet == 0)
                        <td style=\border-bottom:none;\></td>
                        <td style=\border-bottom:none;\ align=\right\>{{rupiah($kredit)}}</td>
                    @else
                        <td style=\border-bottom:none;\ align=\right\>{{rupiah($debet)}}</td>
                        <td style=\border-bottom:none;\></td>
                    @endif
                    </tr>
                @else
                    <tr>
                        <td style="border-bottom:none">{{ tgl_format_oyoy($tgl_voucher) }}"</td>
                        <td style="border-bottom:none">{{$no_voucher}}</td>
                        <td style="border-bottom:none;">{{substr($kd_rek6,0, 1)}}
                        <td style="border-bottom:none;">{{substr($kd_rek6,1, 1)}}</td>
                        <td style="border-bottom:none;">{{substr($kd_rek6,2, 2)}}</td>
                        <td style="border-bottom:none;">{{substr($kd_rek6,4, 2)}}</td>
                        <td style="border-bottom:none;">{{substr($kd_rek6,6, 2)}}</td>
                        <td style="border-bottom:none;">{{substr($kd_rek6,8, 4)}}</td>
                        <td style="border-bottom:none;">{{$nm_rek6}}</td>
                        <td style="border-bottom:none;"></td>
                    @if($debet == 0)
                        <td style="border-bottom:none;"></td>
                        <td style="border-bottom:none;" align="right">{{rupiah($kredit)}}</td>
                    @else
                        <td style="border-bottom:none;" align="right">{{rupiah($debet)}}</td>
                        <td style="border-bottom:none;"></td>
                    @endif
                    </tr>
                @endif
                @php
                    $cnovoc = $no_voucher;
                @endphp
            @endif
            
        @endforeach
        <tr>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
            <td style="border-top:none"></td>
        </tr>  
    </TABLE>

</body>

</html>
