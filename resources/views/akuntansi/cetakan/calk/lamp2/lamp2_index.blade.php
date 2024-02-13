<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - LAMP 2</title>
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
            <td align="center"><strong>PENJELASAN ATAS PENYAJIAN DATA REALISASI BELANJA MODAL TAHUN ANGGARAN {{$thn_ang}}</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>YANG DICATAT SEBAGAI REALISASI PADA NERACA TAHUN</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>&nbsp;</strong></td>
        </tr>
        <tr>
            <td align="left"><strong>SKPD : {{$kd_skpd}} - {{$nm_skpd}}</strong></td>
        </tr>                         
  </table>
  <br>
  <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td rowspan="2" width="5%" align="center"><b>NO</b></td>
            <td rowspan="2" width="15%" align="center"><b>URAIAN</b></td>
            <td colspan="2" width="30%" align="center"><b>REALISASI {{$thn_ang}}</b></td>
            <td rowspan="2" width="20%" align="center"><b>SELISIH</b></td>
            <td colspan="2" width="30%" align="center"><b>KETERANGAN</b></td>
        </tr>
        <tr>
            <td align="center" ><b>BELANJA MODAL</b></td>
            <td align="center"><b>NERACA</b></td>
            <td align="center"><b>Nilai</b></td>
            <td align="center"><b>Penjelasan</b></td>
        </tr>
        @php
            $no=1;
            $tot_belanja_modal=0;
            $tot_neraca=0;
            $tot_neraca=0;
            $tot_selisih=0;
            $tot_nilai=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode          = $row->kode;
                $uraian        = $row->uraian;
                $belanja_modal = $row->tot_belanja_modal;
                $neraca        = $row->tot_neraca;
                $selisih       = $row->selisih;
                $nilai         = $row->nilai;
                $ket           = $row->ket;

                $tot_belanja_modal=$tot_belanja_modal+$belanja_modal;
                $tot_neraca=$tot_neraca+$neraca;
                $tot_selisih=$tot_selisih+$selisih;
                $tot_nilai=$tot_nilai+$nilai;
                
                if($selisih<0){
                    $num_selisih = $selisih*-1;
                    $a="(";
                    $b=")";
                }else{
                    $num_selisih = $selisih;
                    $a="";
                    $b="";
                }
                
                if($nilai<0){
                    $num_nilai = $nilai*-1;
                    $c="(";
                    $d=")";
                }else{
                    $num_nilai = $nilai;
                    $c="";
                    $d="";
                }
            @endphp
            @if($kode=="1")
                <tr>
                    <td align="center" >{{$no++}}</td>
                    <td align="left" >{{$uraian}}</td>
                    <td align="right" >{{rupiah($belanja_modal)}}</td>
                    <td align="right" >{{rupiah($neraca)}}</td>
                    <td align="right" >{{$a}}{{rupiah($num_selisih)}}{{$b}}</td>
                    <td align="right" ></td>
                    <td align="justify" ></td>
                </tr>
            @elseif($kode=="0")
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="left" >{{$uraian}}</td>
                    <td align="right" >&nbsp;</td>
                    <td align="right" >&nbsp;</td>
                    <td align="right" >&nbsp;</td>
                    <td align="right" ></td>
                    <td align="justify" ></td>
                </tr>
            @else
                <tr>
                    <td align="center" style="border-top:hidden;"></td>
                    <td align="left" style="border-top:hidden;"></td>
                    <td align="right" style="border-top:hidden;"></td>
                    <td align="right" style="border-top:hidden;"></td>
                    <td align="right" style="border-top:hidden;"></td>
                    <td align="right" style="border-top:hidden;">{{$c}}{{rupiah($num_nilai)}}{{$d}}</td>
                    <td align="justify" style="border-top:hidden;">{{$ket}}</td>
                </tr>
            @endif
        @endforeach
        @php
            if($tot_selisih<0){
                $num_tot_selisih = $tot_selisih*-1;
                $x="(";
                $y=")";
            }else{
                $num_tot_selisih = $tot_selisih;
                $x="";
                $y="";
            }
        @endphp
        <tr>
            <td align="center" colspan="2"><b>Jumlah</b></td>
            <td align="right" ><b>{{rupiah($tot_belanja_modal)}}</b></td>
            <td align="right" ><b>{{rupiah($tot_neraca)}}</b></td>
            <td align="right" ><b>{{$x}}{{rupiah($num_tot_selisih)}}{{$y}}</b></td>
            <td align="right" ><b>{{rupiah($tot_nilai)}}</b></td>
            <td align="justify" ></td>
        </tr>
    </table>
    @if($jenis==1)
        <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','13')">Edit</button>
    @else
    @endif


</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calklamp2') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>