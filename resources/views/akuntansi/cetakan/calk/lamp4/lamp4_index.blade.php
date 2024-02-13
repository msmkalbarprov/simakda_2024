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
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="right">Lampiran 4</td>
        </tr>                         
        <tr>
            <td align="center"><strong>REKAP PEKERJAAN PERENCANAAN TEKNIS</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>SAMPAI DENGAN 31 DESEMBER {{$thn_ang_1}}</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>&nbsp;</strong></td>
        </tr>
        <tr>
            <td align="left"><strong>SKPD : {{$kd_skpd}} - {{$nm_skpd}}</strong></td>
        </tr>                         
    </table>
    <br>
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <tr>
            <td rowspan="2" width="5%" align="center" style="font-size:12px">No</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Uraian</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Lokasi<br>Kota/Kab.</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Alamat</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Tahun</td>    
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Saldo<br>Awal</td>
            <td colspan="2" width="10%" align="center" style="font-size:12px">Mutasi</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Pengadaan</td>
            <td rowspan="2" width="10%" align="center" style="font-size:12px">Saldo<br>Akhir</td>
        </tr>
        <tr>
            <td  width="5%" align="center" style="font-size:12px">Berkurang</td>
            <td width="5%" align="center" style="font-size:12px">Bertambah</td>
        </tr>
        <tr>
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
           <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td> 
        </tr>
        @php
            $no=1;
            $tot_sawal = 0;
            $tot_berkurang = 0;
            $tot_bertambah = 0;
            $tot_tahun_n = 0;
            $tot_salakhir = 0;
        @endphp
        @foreach($query as $row)
            @php
                $uraian    = $row->uraian;
                $lokasi    = $row->lokasi;
                $alamat    = $row->alamat;
                $tahun     = $row->tahun;
                $saldo_awal = $row->saldo_awal;
                $berkurang   = $row->berkurang;
                $bertambah   = $row->bertambah;
                $pengadaan     = $row->tahun_n;
                $saldo_akhir    = $row->saldo_akhir;

                $tot_sawal = $tot_sawal+$saldo_awal;
                $tot_berkurang = $tot_berkurang+$berkurang;
                $tot_bertambah = $tot_bertambah+$bertambah;
                $tot_tahun_n = $tot_tahun_n+$pengadaan;
                $tot_salakhir = $tot_salakhir+$saldo_akhir;
                
                
                if($saldo_awal<0 || $berkurang<0){
                    $num_saldo_awal = $saldo_awal*-1;
                    $num_berkurang = $berkurang*-1;
                    $c="(";
                    $d=")";
                }else{
                    $num_saldo_awal = $saldo_awal;
                    $num_berkurang = $berkurang;
                    $c="";
                    $d="";
                }
            @endphp
            <tr>
                <td align="center" >{{$no++}}</td>
                <td align="left" >{{$uraian}}</td>
                <td align="left" >{{$lokasi}}</td>
                <td align="left" >{{$alamat}}</td>
                <td align="center" >{{$tahun}}</td>
                <td align="right" >{{$c}}{{rupiah($num_saldo_awal)}}{{$d}}</td>
                <td align="right" >{{$c}}{{rupiah($num_berkurang)}}{{$d}}</td>
                <td align="right" >{{rupiah($bertambah)}}</td>
                <td align="right" >{{rupiah($pengadaan)}}</td>
                <td align="right" >{{rupiah($saldo_akhir)}}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center" colspan="5"><b>Jumlah</b></td>
            <td align="right" ><b>{{rupiah($tot_sawal)}}</b></td>
            <td align="right" ><b>{{rupiah($tot_berkurang)}}</b></td>
            <td align="right" ><b>{{rupiah($tot_bertambah)}}</b></td>
            <td align="right" ><b>{{rupiah($tot_tahun_n)}}</b></td>
            <td align="right" ><b>{{rupiah($tot_salakhir)}}</b></td>
        </tr>
    </table>

    @if($jenis==1)
        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}')">Edit</button>
    @else
    @endif


</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan) {
        let url             = new URL("{{ route('calk.calklamp4') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        window.open(url.toString(), "_blank");
    }
</script>