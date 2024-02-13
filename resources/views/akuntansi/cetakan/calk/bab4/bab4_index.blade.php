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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
            <TR>
                <TD align="center" ><b>BAB IV PENJELASAN ATAS INFORMASI-INFORMASI NON KEUANGAN</TD>
            </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
        <TR>
            <TD align="justify" colspan="4">{{$nm_skpd}} Provinsi Kalimantan Barat Tahun {{$thn_ang}} untuk Informasi Non Keuangan dapat dijelaskan sebagai berikut :</TD>
        </TR>
        <TR>
                <TD valign="top" colspan="4">&nbsp;</TD>
        </TR>
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;                    
                $kd_rek= $row->kd_rek;
                $ket  = $row->ket;
                $nilai_ini   = $row->tahun_ini;
                $nilai_lalu  = $row->tahun_lalu;
                if($jenis==1){ 
                    if($ket==''){
                        $text = "Penjelasan";
                    }else{
                        $text = $ket;
                    }
                }else{
                    if($ket==''){
                        $text = " ";
                    }else{
                        $text = $ket;
                    }
                }
            @endphp
            @if($kd_rek=="4.1")
                <TR>
                    <TD valign="top" width="5%" colspan="1">4.1</TD>
                    <TD align="justify" colspan="3">Gambaran Umum</TD>
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3" bgcolor="yellow">{!! $text !!}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3">{!! $text !!}</TD>
                    </TR>
                @endif
                <TR>
                    <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                    <TD valign="top" colspan="3">&nbsp;</TD>
                </TR>
            @elseif($kd_rek=="4.2")
                <TR>
                    <TD valign="top" width="5%" colspan="1">4.2</TD>
                    <TD align="justify" colspan="3">Tugas Pokok dan Fungsi</TD>
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3" bgcolor="yellow">{!! $text !!}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3">{!! $text !!}</TD>
                    </TR>
                @endif
                <TR>
                    <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                    <TD valign="top" colspan="3">&nbsp;</TD>
                </TR>
            @elseif($kd_rek=="4.3")
                <TR>
                    <TD valign="top" width="5%" colspan="1">4.3</TD>
                    <TD align="justify" colspan="3">Struktur Organisasi</TD>
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3" bgcolor="yellow">{!! $text !!}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3">{!! $text !!}</TD>
                    </TR>
                @endif
                <TR>
                    <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                    <TD valign="top" colspan="3">&nbsp;</TD>
                </TR>
            @elseif($kd_rek=="4.4")
                <TR>
                    <TD valign="top" width="5%" colspan="1">4.4</TD>
                    <TD align="justify" colspan="3">Visi dan Misi</TD>
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3" bgcolor="yellow">{!! $text !!}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                        <TD align="justify" colspan="3">{!! $text !!}</TD>
                    </TR>
                @endif
                <TR>
                    <TD valign="top" width="5%" colspan="1">&nbsp;</TD>
                    <TD valign="top" colspan="3">&nbsp;</TD>
                </TR>
            @elseif($kd_rek=="4.5")
                <TR>
                    <TD valign="top" width="5%">4.5</TD>
                    <TD align="justify">Extracompatable</TD>
                    <TD align="justify" width="15%">Tahun {{$thn_ang}}</TD>
                    <TD align="justify" width="15%">Tahun {{$thn_ang_1}}</TD>
                </TR>
            @elseif($kd_rek=="4.5.a")
                <TR>
                    <TD valign="top" width="5%">&nbsp;</TD>
                    <TD valign="top">a. Persediaan Lain-lain</TD>
                    <TD align="justify"></TD>
                    <TD align="justify"></TD>
                    
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%">&nbsp;</TD>
                        <TD align="justify" bgcolor="yellow">{!! $text !!}</TD>
                        <TD align="justify" bgcolor="yellow">{{rupiah($nilai_ini)}}</TD>
                        <TD align="justify" bgcolor="yellow">{{rupiah($nilai_lalu)}}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%">&nbsp;</TD>
                        <TD align="justify" >{!! $text !!}</TD>
                        <TD align="justify" >{{rupiah($nilai_ini)}}</TD>
                        <TD align="justify" >{{rupiah($nilai_lalu)}}</TD>
                    </TR>
                @endif

                <TR>
                    <TD valign="top" width="5%">&nbsp;</TD>
                    <TD valign="top">&nbsp;</TD>
                    <TD align="justify"></TD>
                    <TD align="justify"></TD>
                </TR>
            @elseif($kd_rek=="4.5.b")
                <TR>
                    <TD valign="top" width="5%">&nbsp;</TD>
                    <TD valign="top">b. Aset Tetap</TD>
                    <TD align="justify"></TD>
                    <TD align="justify"></TD>
                    
                </TR>
                @if($jenis==1)
                    <TR>
                        <TD valign="top" width="5%">&nbsp;</TD>
                        <TD align="justify" bgcolor="yellow">{!! $text !!}</TD>
                        <TD align="justify" bgcolor="yellow">{{rupiah($nilai_ini)}}</TD>
                        <TD align="justify" bgcolor="yellow">{{rupiah($nilai_lalu)}}</TD>
                    </TR>
                @else
                    <TR>
                        <TD valign="top" width="5%">&nbsp;</TD>
                        <TD align="justify" >{!! $text !!}</TD>
                        <TD align="justify" >{{rupiah($nilai_ini)}}</TD>
                        <TD align="justify" >{{rupiah($nilai_lalu)}}</TD>
                    </TR>
                @endif

                <TR>
                    <TD valign="top" width="5%">&nbsp;</TD>
                    <TD valign="top">&nbsp;</TD>
                    <TD align="justify"></TD>
                    <TD align="justify"></TD>
                </TR>
            @else
            @endif
        @endforeach
        @if($jenis==1)
            <tr>
                <td align="justify" colspan="7">
                    <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','4')">Edit</button>
                </td>                         
            </tr>
        @else
        @endif
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
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab4') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>