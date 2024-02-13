<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - LAMP 3</title>
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
            <td align="right">Lampiran 2</td>
        </tr>                         
        <tr>
            <td align="center"><strong>DAFTAR  JAMINAN  PEMELIHARAAN</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>TAHUN ANGGARAN {{$thn_ang}}</strong></td>
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
            <td width="9%" align="center"  ><b>NO</b></td>
            <td width="13%" align="center"  ><b>KODE SKPD</b></td>
            <td width="13%" align="center"  ><b>NAMA SKPD</b></td>
            <td width="13%" align="center"  ><b>NAMA PROGRAM</b></td>
            <td width="13%" align="center"  ><b>NAMA KEGIATAN</b></td>
            <td width="13%" align="center"  ><b>NILAI KONTRAK (Rp.)</b></td>
            <td width="13%" align="center"  ><b>PELAKSANA</b></td>
            <td width="13%" align="center"  ><b>NILAI JAMINAN PEMELIHARAAN (Rp)</b></td>
            <td width="13%" align="center"  ><b>TANGGAL AWAL JAMINAN PEMELIHARAAN</b></td>
            <td width="13%" align="center"  ><b>TANGGAL AKHIR PEMELIHARAAN</b></td>
            <td width="13%" align="center"  ><b>NAMA PENERBIT JAMINAN PEMELIHARAAN</b></td>
        </tr>
        <tr>
           <td align="center" bgcolor="#CCCCCC" >1</td> 
           <td align="center" bgcolor="#CCCCCC" >2</td> 
           <td align="center" bgcolor="#CCCCCC" >3</td> 
           <td align="center" bgcolor="#CCCCCC" >4</td> 
           <td align="center" bgcolor="#CCCCCC" >5</td> 
           <td align="center" bgcolor="#CCCCCC" >6</td> 
           <td align="center" bgcolor="#CCCCCC" >7</td> 
           <td align="center" bgcolor="#CCCCCC" >8</td> 
           <td align="center" bgcolor="#CCCCCC" >9</td> 
           <td align="center" bgcolor="#CCCCCC" >10</td>
           <td align="center" bgcolor="#CCCCCC" >11</td> 
        </tr>
        @php
            $no=1;
            $tot_kon = 0;
            $tot_jam = 0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd    = $row->kd_skpd;
                $nm_skpd    = $row->nm_skpd;
                $nm_program    = $row->nm_program;
                $nm_sub_kegiatan   = $row->nm_sub_kegiatan;
                $nilai_kontrak = $row->nilai;
                $pelaksana     = $row->pelaksana;
                $nilai_jamin   = $row->nilai_jaminan;
                $nm_penerbit   = $row->nm_penerbit;
                $masa_awal     = $row->masa_awal;
                $masa_akhir    = $row->masa_akhir;
                
                $tot_kon = $tot_kon+$nilai_kontrak;
                $tot_jam = $tot_jam+$nilai_jamin;
                
                if($nilai_kontrak<0 || $nilai_jamin<0){
                    $num_nilai_kon = $nilai_kontrak*-1;
                    $num_nilai_jam = $nilai_jamin*-1;
                    $c="(";
                    $d=")";
                }else{
                    $num_nilai_kon = $nilai_kontrak;
                    $num_nilai_jam = $nilai_jamin;
                    $c="";
                    $d="";
                }
            @endphp
            <tr>
                <td align="center" >{{$no++}}</td>
                <td align="left" >{{$kd_skpd}}</td>
                <td align="left" >{{$nm_skpd}}</td>
                <td align="left" >{{$nm_program}}</td>
                <td align="left" >{{$nm_sub_kegiatan}}</td>
                <td align="right" >{{$c}}{{rupiah($num_nilai_kon)}}{{$d}}</td>
                <td align="right" >{{$pelaksana}}</td>
                <td align="right" >{{$c}}{{rupiah($num_nilai_jam)}}{{$d}}</td>
                <td align="right" >{{$masa_awal}}  </td>
                <td align="right" >{{$masa_akhir}} </td>
                <td align="right" >{{$nm_penerbit}}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center" colspan="5"><b>Jumlah</b></td>
            <td align="right" ><b></b>{{rupiah($tot_kon)}}</td>
            <td align="right" ><b></b></td>
            <td align="right" ><b></b>{{rupiah($tot_jam)}}</td>
            <td align="right" ><b></b></td>
            <td align="right" ><b></b></td>
            <td align="right" ><b></b></td>
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
        let url             = new URL("{{ route('calk.calklamp3') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        window.open(url.toString(), "_blank");
    }
</script>