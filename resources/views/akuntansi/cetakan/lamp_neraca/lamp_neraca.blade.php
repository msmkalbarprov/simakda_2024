<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lamp. Neraca</title>
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
    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5" style="border-top:none;border-right:none;border-left:none;font-size:14px" align="center">
                <b>PEMERINTAH PROVINSI KALIMANTAN BARAT</b> <br>
                <b>RINCIAN NERACA {{$namanya->nm_rek3}}</b><BR>
                <b>PER 31 DESEMBER {{$lntahunang}} </b>
                <BR>&nbsp;                                        
            </td>           
        </tr>
    </table>
    
    
    <table style="border-collapse:collapse;font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="5%"  align="left">OPD </td> 
            <td> :</td>
            <td> {{$skpd}} - {{ nama_skpd($skpd) }} <br></td>
        </tr>
        <tr>
            <td width="5%"  align="left">&nbsp;</td> 
        </tr>
    </table>
    
    @if($rek3==1301)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $lokasi = $row->lokasi;
                $alamat = $row->alamat;
                $sert = $row->sert;
                $luas = $row->luas;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                $luas==0 ? $luas = '' : $luas=rupiah($luas);
                $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
        
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$alamat}}</td> 
                    <td align="center" style="font-size:12px">{{$sert}}</td> 
                    <td align="center" style="font-size:12px">{{$luas}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$alamat}}</td> 
                    <td align="center" style="font-size:12px">{{$sert}}</td> 
                    <td align="center" style="font-size:12px">{{$luas}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                   
            if ($koreksi < 0){
               $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }
                       
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL TANAH</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL TANAH</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1303 || $rek3==1306 || $rek3==1304)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $lokasi = $row->lokasi;
                $alamat = $row->alamat;
                $fungsi = $row->fungsi;
                $luas = $row->luas;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                $luas==0 ? $luas = '' : $luas=rupiah($luas);
                $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
        
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                    <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$alamat}}</td> 
                    <td align="center" style="font-size:12px">{{$luas}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                    <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$alamat}}</td> 
                    <td align="center" style="font-size:12px">{{$luas}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
            
            if ($koreksi < 0){
               $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }            
        
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL {{$namanya}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL {{$namanya}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1302)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $merk = $row->merk;
                $no_polisi = $row->no_polisi;
                $jumlah = $row->jumlah;
                $harga_satuan = $row->harga_satuan;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                
                if($jumlah==0){
                    $jumlah='';
                }else if(stripos($jumlah, '.00') !== FALSE){
                    $jumlah=rupiah($jumlah);
                }else{
                    $jumlah=rupiah($jumlah,1);
                }
                       
                $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                       
                $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
        
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
            
            if ($koreksi < 0){
               $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }            
            
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL PERALATAN DAN MESIN</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px"></td> 
                    <td align="left" style="font-size:12px">TOTAL PERALATAN DAN MESIN</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1112)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $merk = $row->merk;
                $jumlah = $row->jumlah;
                $jumlah_akhir = $row->jumlah_akhir;
                $harga_satuan = $row->harga_satuan;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $kondisi_x = $row->kondisi_x;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                
                if($jumlah==0){
                    $jumlah='';
                }else if(stripos($jumlah, '.00') !== FALSE){
                    $jumlah=rupiah($jumlah);
                }else{
                    $jumlah=rupiah($jumlah,1);
                }
                       
                if($jumlah_akhir==0){
                    $jumlah_akhir='';
                }else if(stripos($jumlah_akhir, '.00') !== FALSE){
                    $jumlah_akhir=rupiah($jumlah_akhir);
                }else{
                    $jumlah_akhir=rupiah($jumlah_akhir,1);
                }
                       
                if($kondisi_b==0){
                    $kondisi_b='';
                }else if(stripos($kondisi_b, '.00') !== FALSE){
                    $kondisi_b=rupiah($kondisi_b);
                }else{
                    $kondisi_b=rupiah($kondisi_b,1);
                }
                       
                if($kondisi_rr==0){
                    $kondisi_rr='';
                }else if(stripos($kondisi_rr, '.00') !== FALSE){
                    $kondisi_rr=rupiah($kondisi_rr);
                }else{
                    $kondisi_rr=rupiah($kondisi_rr,1);
                }
                       
                if($kondisi_rb==0){
                    $kondisi_rb='';
                }else if(stripos($kondisi_rb, '.00') !== FALSE){
                    $kondisi_rb=rupiah($kondisi_rb);
                }else{
                    $kondisi_rb=rupiah($kondisi_rb,1);
                }
                       
                if($kondisi_x==0){
                    $kondisi_x='';
                }else if(stripos($kondisi_x, '.00') !== FALSE){
                    $kondisi_x=rupiah($kondisi_x);
                }else{
                    $kondisi_x=rupiah($kondisi_x,1);
                }
                   
                $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
                
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_x}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_x}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
            if ($koreksi < 0){
                $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }            
        
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==2101)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $lokasi = $row->lokasi;
                $jumlah = $row->jumlah;
                $jumlah_akhir = $row->jumlah_akhir;
                $harga_satuan = $row->harga_satuan;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $kondisi_x = $row->kondisi_x;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                
                if($jumlah==0){
                    $jumlah='';
                }else if(stripos($jumlah, '.00') !== FALSE){
                    $jumlah=rupiah($jumlah);
                }else{
                    $jumlah=rupiah($jumlah,1);
                }
                       
                if($jumlah_akhir==0){
                    $jumlah_akhir='';
                }else if(stripos($jumlah_akhir, '.00') !== FALSE){
                    $jumlah_akhir=rupiah($jumlah_akhir);
                }else{
                    $jumlah_akhir=rupiah($jumlah_akhir,1);
                }
                       
                if($kondisi_b==0){
                    $kondisi_b='';
                }else if(stripos($kondisi_b, '.00') !== FALSE){
                    $kondisi_b=rupiah($kondisi_b);
                }else{
                    $kondisi_b=rupiah($kondisi_b,1);
                }
                       
                if($kondisi_rr==0){
                    $kondisi_rr='';
                }else if(stripos($kondisi_rr, '.00') !== FALSE){
                    $kondisi_rr=rupiah($kondisi_rr);
                }else{
                    $kondisi_rr=rupiah($kondisi_rr,1);
                }
                       
                if($kondisi_rb==0){
                    $kondisi_rb='';
                }else if(stripos($kondisi_rb, '.00') !== FALSE){
                    $kondisi_rb=rupiah($kondisi_rb);
                }else{
                    $kondisi_rb=rupiah($kondisi_rb,1);
                }
                       
                if($kondisi_x==0){
                    $kondisi_x='';
                }else if(stripos($kondisi_x, '.00') !== FALSE){
                    $kondisi_x=rupiah($kondisi_x);
                }else{
                    $kondisi_x=rupiah($kondisi_x,1);
                }
                       
                $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
         
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td>
                    <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$lokasi}}</td>
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td>  
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
               
            if ($koreksi < 0){
                $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
                $min001=""; $koreksi; $min002="";
            }            
        
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
                       
            if ($koreksi < 0){
                $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }            
        
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2 "align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td>  
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1305)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                $nama = $row->nama;
                $tahun = $row->tahun;
                $merk = $row->merk;
                $jumlah = $row->jumlah;
                $harga_satuan = $row->harga_satuan;
                $satuan = $row->satuan;
                $sal_awal = $row->sal_awal;
                $kurang = $row->kurang;
                $tambah = $row->tambah;
                $tahun_n = $row->tahun_n;
                $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                $kondisi_b = $row->kondisi_b;
                $kondisi_rr = $row->kondisi_rr;
                $kondisi_rb = $row->kondisi_rb;
                $keterangan = $row->keterangan;
                $koreksi = $row->koreksi;
                $audited = $akhir+$koreksi;
                
                if($jumlah==0){
                    $jumlah='';
                }else if(stripos($jumlah, '.00') !== FALSE){
                    $jumlah=rupiah($jumlah);
                }else{
                    $jumlah=rupiah($jumlah,1);
                }
                        
                $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                    
                $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                $no_lamp = $row->no_lamp;
                $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                if ($koreksi < 0){
                   $min001="("; $koreksi=$koreksi*-1; $min002=")";
                }else {
                   $min001=""; $koreksi; $min002="";
                }            
        
                $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                    </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$merk}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                    <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
            $sal_awal = $query_jum->sal_awal;
            $kurang = $query_jum->kurang;
            $tambah = $query_jum->tambah;
            $tahun_n = $query_jum->tahun_n;
            $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
            $koreksi = $query_jum->koreksi;
            $audited = $akhir+$koreksi;
            $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
            $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
            $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
            $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
            $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
            if ($koreksi < 0){
               $min001="("; $koreksi=$koreksi*-1; $min002=")";
            }else {
               $min001=""; $koreksi; $min002="";
            }            
        
            $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
            $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1111)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
               $nama = $row->nama;
               $tahun = $row->tahun;
               $jenis_aset = $row->jenis_aset;
               $nama_perusahaan = $row->nama_perusahaan;
               $no_polis = $row->no_polis;
               $realisasi_janji = $row->realisasi_janji;
               $tgl_awal = $row->tgl_awal;
               $tgl_akhir = $row->tgl_akhir;
               $jam = $row->jam;
               $sisa_hari = $row->sisa_hari;
               $sal_awal = $row->sal_awal;
               $kurang = $row->kurang;
               $tambah = $row->tambah;
               $tahun_n = $row->tahun_n;
               $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
               $keterangan = $row->keterangan;
               $koreksi = $row->koreksi;
               $audited = $akhir+$koreksi;
               $no_lamp = $row->no_lamp;

               if($jam == ""){
                    $hh = 0;
                    $mm = 0;
               }else{
                   $hh              = substr($jam,0,2);
                   $mm              = substr($jam,3,5);

               }
               $jam_std         = ((12-$hh)*3600)+((-$mm)*60);
               
               
               if($jam_std<=0 && $sisa_hari!=0){
               $sisa_hari=$sisa_hari-0.5;
               } else if($jam_std>0 && $sisa_hari!=0){
               $sisa_hari=$sisa_hari-1;
               } else{
               $sisa_hari=0;
               }
               
               $realisasi_janji==0 ? $realisasi_janji = '' : $realisasi_janji=number_format($realisasi_janji,"2",",",".");
               
               if($no_lamp==''){
               $sisa_hari='';
               }
               else if($no_lamp=='x'){
               $sisa_hari='';
               } else{
               $sisa_hari=number_format($sisa_hari,"1",",",".");
               }
               
               if($tgl_awal==''){
               $tgl_awal='';
               } else{
               $tgl_awal=tanggal_indonesia($tgl_awal);
               }
               
               if($tgl_akhir==''){
               $tgl_akhir='';
               } else{
               $tgl_akhir=tanggal_indonesia($tgl_akhir);
               }
                                   
               $sal_awal==0 ? $sal_awal = '' : $sal_awal=number_format($sal_awal,"2",",",".");
               $kurang==0 ? $kurang = '' : $kurang=number_format($kurang,"2",",",".");
               $tambah==0 ? $tambah = '' : $tambah=number_format($tambah,"2",",",".");
               $tahun_n==0 ? $tahun_n = '' : $tahun_n=number_format($tahun_n,"2",",",".");
               
               $no_lamp=='' ? $akhir = '' : $akhir=number_format($akhir,"2",",",".");
               
               if ($koreksi < 0){
               $min001="("; $koreksi=$koreksi*-1; $min002=")";
               }else {
               $min001=""; $koreksi; $min002="";
               }            

               $koreksi==0 ? $koreksi = '' : $koreksi=number_format($koreksi,"2",",",".");
               $no_lamp=='' ? $audited = '' : $audited=number_format($audited,"2",",",".");
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jenis_aset}}</td> 
                    <td align="center" style="font-size:12px">{{$nama_perusahaan}}</td> 
                    <td align="center" style="font-size:12px">{{$no_polis}}</td> 
                    <td align="right" style="font-size:12px">{{$realisasi_janji}}</td> 
                    <td align="center" style="font-size:12px">{{$tgl_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$tgl_akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$jam}}</td> 
                    <td align="center" style="font-size:12px">{{$sisa_hari}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jenis_aset}}</td> 
                    <td align="center" style="font-size:12px">{{$nama_perusahaan}}</td> 
                    <td align="center" style="font-size:12px">{{$no_polis}}</td> 
                    <td align="right" style="font-size:12px">{{$realisasi_janji}}</td> 
                    <td align="center" style="font-size:12px">{{$tgl_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$tgl_akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$jam}}</td> 
                    <td align="center" style="font-size:12px">{{$sisa_hari}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td>  
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td>  
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td> 
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                    <td align="center" style="font-size:12px">{{$audited}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1109)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $piutang_awal = $row->piutang_awal;
                       $piutang_koreksi = $row->piutang_koreksi;
                       $piutang_sudah = $row->piutang_sudah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $piutang_awal==0 ? $piutang_awal = '' : $piutang_awal=rupiah($piutang_awal);
                       $piutang_koreksi==0 ? $piutang_koreksi = '' : $piutang_koreksi=rupiah($piutang_koreksi);
                       $piutang_sudah==0 ? $piutang_sudah = '' : $piutang_sudah=rupiah($piutang_sudah);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_koreksi}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_sudah}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_koreksi}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_sudah}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1110)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $piutang_awal = $row->piutang_awal;
                       $piutang_koreksi = $row->piutang_koreksi;
                       $piutang_sudah = $row->piutang_sudah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupaiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupaiah($jumlah);
                       }else{
                            $jumlah=rupaiah($jumlah,1);
                       }
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupaiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupaiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupaiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupaiah($tahun_n);
                       $piutang_awal==0 ? $piutang_awal = '' : $piutang_awal=rupaiah($piutang_awal);
                       $piutang_koreksi==0 ? $piutang_koreksi = '' : $piutang_koreksi=rupaiah($piutang_koreksi);
                       $piutang_sudah==0 ? $piutang_sudah = '' : $piutang_sudah=rupaiah($piutang_sudah);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupaiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupaiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupaiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_koreksi}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_sudah}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_koreksi}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_sudah}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=($koreksi);
                       $audited==0 ? $audited = '' : $audited=($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                    <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @else
                <tr>
                    <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px"></td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px"></td> 
                </tr>
        @endif
    @elseif($rek3==1103 || $rek3==1104 || $rek3==1106)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $lokasi = $row->lokasi;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $piutang_awal = $row->piutang_awal;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       
                       if ($skpd=="1.02.0.00.0.00.02.0000" and $rek3==1106) {
                           $koreksi=0;
                           // $audited=$akhir;
                       }
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $piutang_awal==0 ? $piutang_awal = '' : $piutang_awal=rupiah($piutang_awal);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="left" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @else
                <tr>
                    <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                    <td align="left" style="font-size:12px">{{$kode}}</td> 
                    <td align="left" style="font-size:12px">{{$nama}}</td> 
                    <td align="left" style="font-size:12px">{{$lokasi}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun}}</td> 
                    <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                    <td align="center" style="font-size:12px">{{$piutang_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                    <td align="center" style="font-size:12px">{{$kurang}}</td> 
                    <td align="center" style="font-size:12px">{{$tambah}}</td> 
                    <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                    <td align="center" style="font-size:12px">{{$akhir}}</td>
                    <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                    <td align="center" style="font-size:12px">{{$audited}}</td>
                    <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="left" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL $nama</td> 
                               <td align="left" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==1102 || $rek3==1201 || $rek3==1202)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $hukum = $row->hukum;
                       $jumlah = $row->jumlah;
                       $kepemilikan = $row->kepemilikan;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $investasi_awal = $row->investasi_awal;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $investasi_awal==0 ? $investasi_awal = '' : $investasi_awal=rupiah($investasi_awal);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align=left style=font-size:12px>{{$kode}}</td> 
                               <td align=left style=font-size:12px>{{$nama}}</td> 
                               <td align=center style=font-size:12px>{{$tahun}}</td> 
                               <td align=left style=font-size:12px>{{$hukum}}</td> 
                               <td align=left style=font-size:12px>{{$kepemilikan}}</td> 
                               <td align=center style=font-size:12px>{{$jumlah}}</td> 
                               <td align=center style=font-size:12px>{{$investasi_awal}}</td> 
                               <td align=center style=font-size:12px>{{$sal_awal}}</td> 
                               <td align=center style=font-size:12px>{{$kurang}}</td> 
                               <td align=center style=font-size:12px>{{$tambah}}</td> 
                               <td align=center style=font-size:12px>{{$tahun_n}}</td> 
                               <td align=center style=font-size:12px>{{$akhir}}</td>
                               <td align=center style=font-size:12px>{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align=center style=font-size:12px>{{$audited}}</td>
                               <td align=center style=font-size:12px>{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align=left style=font-size:12px>{{$no_lamp}}</td> 
                               <td align=left style=font-size:12px>{{$kode}}</td> 
                               <td align=left style=font-size:12px>{{$nama}}</td> 
                               <td align=center style=font-size:12px>{{$tahun}}</td> 
                               <td align=left style=font-size:12px>{{$hukum}}</td> 
                               <td align=left style=font-size:12px>{{$kepemilikan}}</td> 
                               <td align=center style=font-size:12px>{{$jumlah}}</td> 
                               <td align=center style=font-size:12px>{{$investasi_awal}}</td> 
                               <td align=center style=font-size:12px>{{$sal_awal}}</td> 
                               <td align=center style=font-size:12px>{{$kurang}}</td> 
                               <td align=center style=font-size:12px>{{$tambah}}</td> 
                               <td align=center style=font-size:12px>{{$tahun_n}}</td> 
                               <td align=center style=font-size:12px>{{$akhir}}</td>
                               <td align=center style=font-size:12px>{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align=center style=font-size:12px>{{$audited}}</td>
                               <td align=center style=font-size:12px>{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="left" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="left" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==1101)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}$koreksi$min002</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}$koreksi$min002</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px"></td> 
                               <td align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td align="left" style="font-size:12px"></td> 
                               <td align="left" style="font-size:12px"></td> 
                               <td align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==1401)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $hukum = $row->hukum;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$hukum}}</td>
                               <td align="center" style="font-size:12px">{{$jumlah}}</td>
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$hukum}}</td>
                               <td align="center" style="font-size:12px">{{$jumlah}}</td>
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td>
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td>
                               <td align="center" style="font-size:12px">{{$audited}}</td>
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==1501 || $rek3==1502 || $rek3==1503 || $rek3==1504)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $merk = $row->merk;
                       $no_polisi = $row->no_polisi;
                       $lokasi = $row->lokasi;
                       $fungsi = $row->fungsi;
                       $alamat = $row->alamat;
                       $jumlah = $row->jumlah;
                       $harga_satuan = $row->harga_satuan;
                       $satuan = $row->satuan;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $kondisi_b = $row->kondisi_b;
                       $kondisi_rr = $row->kondisi_rr;
                       $kondisi_rb = $row->kondisi_rb;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       
                       $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                       $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                       $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                       
                       $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$merk}}</td> 
                               <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                               <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$alamat}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$merk}}</td> 
                               <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                               <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$alamat}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==2105)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode        = $row->kode;
                       $nama        = $row->nama;
                       $tahun       = $row->tahun;
                       $lokasi      = $row->lokasi;
                       $jenis_aset  = $row->jenis_aset;
                       $tgl_awal    = $row->tgl_awal;
                       $tgl_akhir   = $row->tgl_akhir;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       $no_lamp = $row->no_lamp;

                       
                       if($tgl_awal==''){
                       $tgl_awal='';
                       } else{
                       $tgl_awal=$this->tukd_model->tanggal_ind($tgl_awal);
                       }
                       
                       if($tgl_akhir==''){
                       $tgl_akhir='';
                       } else{
                       $tgl_akhir=$this->tukd_model->tanggal_ind($tgl_akhir);
                       }
                                           
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       
                       $akhir=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$jenis_aset}}</td> 
                               <td align="center" style="font-size:12px">{{$tgl_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$tgl_akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                       <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$jenis_aset}}</td> 
                               <td align="center" style="font-size:12px">{{$tgl_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$tgl_akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr> 
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td>  
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr> 
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td>
                               <td align="center" style="font-size:12px"></td>  
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==2102 || $rek3==2103 || $rek3==2106 || $rek3==1108 || $rek3==2210 || $rek3==2202)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $no_lamp = $row->no_lamp;
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited=='' ? $audited = '' : $audited=rupiah($audited);
            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align="left" style="font-size:12px">{{$no_lamp}}</td>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $query_jum->nama;
                       $sal_awal = $query_jum->sal_awal;
                       $kurang = $query_jum->kurang;
                       $tambah = $query_jum->tambah;
                       $tahun_n = $query_jum->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $query_jum->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @elseif($rek3==15)
        {!!$head!!}
        @foreach($query as $row)
            @php
                $kode = $row->kode;
                       $nama = $row->nama;
                       $tahun = $row->tahun;
                       $merk = $row->merk;
                       $no_polisi = $row->no_polisi;
                       $lokasi = $row->lokasi;
                       $fungsi = $row->fungsi;
                       $alamat = $row->alamat;
                       $jumlah = $row->jumlah;
                       $harga_satuan = $row->harga_satuan;
                       $satuan = $row->satuan;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $kondisi_b = $row->kondisi_b;
                       $kondisi_rr = $row->kondisi_rr;
                       $kondisi_rb = $row->kondisi_rb;
                       $keterangan = $row->keterangan;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       //$jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       if($jumlah==0){
                            $jumlah='';
                       }else if(stripos($jumlah, '.00') !== FALSE){
                            $jumlah=rupiah($jumlah);
                       }else{
                            $jumlah=rupiah($jumlah,1);
                       }
                       
                       $kondisi_b==0 ? $kondisi_b = '' : $kondisi_b=rupiah($kondisi_b);
                       $kondisi_rr==0 ? $kondisi_rr = '' : $kondisi_rr=rupiah($kondisi_rr);
                       $kondisi_rb==0 ? $kondisi_rb = '' : $kondisi_rb=rupiah($kondisi_rb);
                       
                       $harga_satuan==0 ? $harga_satuan = '' : $harga_satuan=rupiah($harga_satuan);
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $no_lamp = $row->no_lamp;
                       $no_lamp=='' ? $akhir = '' : $akhir=rupiah($akhir);
                       $total_sal_awal=$total_sal_awal + $sal_awal;
                       //$total_sal_awal=rupiah($total_sal_awal);
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $no_lamp=='' ? $audited = '' : $audited=rupiah($audited);

            @endphp
            @if($cetakan=="1")
                <tr>
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$merk}}</td> 
                               <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                               <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$alamat}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @else
                <tr>
                               <td align="left" style="font-size:12px">{{$no_lamp}}</td> 
                               <td align="left" style="font-size:12px">{{$kode}}</td> 
                               <td align="left" style="font-size:12px">{{$nama}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun}}</td> 
                               <td align="center" style="font-size:12px">{{$merk}}</td> 
                               <td align="center" style="font-size:12px">{{$no_polisi}}</td> 
                               <td align="center" style="font-size:12px">{{$fungsi}}</td> 
                               <td align="center" style="font-size:12px">{{$lokasi}}</td> 
                               <td align="center" style="font-size:12px">{{$alamat}}</td> 
                               <td align="center" style="font-size:12px">{{$jumlah}}</td> 
                               <td align="center" style="font-size:12px">{{$satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$harga_satuan}}</td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_b}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rr}}</td> 
                               <td align="center" style="font-size:12px">{{$kondisi_rb}}</td> 
                               <td align="center" style="font-size:12px">{{$keterangan}}</td> 
                            </tr>
            @endif
        @endforeach
        @php
            $nama = $row->nama;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $akhir==0 ? $akhir = '' : $akhir=rupiah($akhir);
                       $total_sal_awal=$total_sal_awal + $sal_awal;
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       $koreksi==0 ? $koreksi = '' : $koreksi=rupiah($koreksi);
                       $audited==0 ? $audited = '' : $audited=rupiah($audited);
        @endphp
        @if($cetakan=="1")
                <tr>
                               <td colspan="2" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @else
                <tr>
                               <td colspan="3" align="left" style="font-size:12px">TOTAL {{$nama}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
                               <td align="center" style="font-size:12px">{{$kurang}}</td> 
                               <td align="center" style="font-size:12px">{{$tambah}}</td> 
                               <td align="center" style="font-size:12px">{{$tahun_n}}</td> 
                               <td align="center" style="font-size:12px">{{$akhir}}</td> 
                               <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
                               <td align="center" style="font-size:12px">{{$audited}}</td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                               <td align="center" style="font-size:12px"></td> 
                            </tr>
        @endif
    @else
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="5" style="border-top:none;border-right:none;border-left:none;font-size:12px" align="center">BELUM ADA CETAKAN</td>           
            </tr>
        </table>
    @endif
        
    
    
    </TABLE>

    
    
</body>

</html>
